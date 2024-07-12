<?php
declare(strict_types=1);
session_start();

use App\Exceptions\InfrastructureException;
use App\DatabaseInitializer;
use App\Responses\RedirectResponse;
use App\Responses\TemplateResponse;
use DI\ContainerBuilder;
use Doctrine\DBAL\Connection;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Twig\Environment;
use Twig\Extension\CoreExtension;
use Twig\Loader\FilesystemLoader;

require_once __DIR__ . '/vendor/autoload.php';

$loader = new FilesystemLoader("views");
$twig = new Environment($loader);
$twig->getExtension(CoreExtension::class)->setTimezone("Europe/Riga");

$logger = new Logger("app");
$formatter = new LineFormatter(
    null,
    null,
    true,
    true,
    true
);
$streamHandler = new StreamHandler("storage/app.log");
$streamHandler->setFormatter($formatter);
$logger->pushHandler($streamHandler);

$connectionParameters = [
    "driver" => "pdo_sqlite",
    "path" => "storage/database.sqlite",
];

$builder = new ContainerBuilder();
$builder->addDefinitions(
    include "app/DiContainerDefinitions.php"
);
try {
    $container = $builder->build();
} catch (Exception $e) {
    $logger->error($e->getMessage());
}

($container->get(DatabaseInitializer::class))->execute();

$dispatcher = FastRoute\simpleDispatcher(function (FastRoute\RouteCollector $r) {
    $routes = include __DIR__ . "/routes.php";
    foreach ($routes as $route) {
        $r->addRoute($route[0], $route[1], $route[2]);
    }
});

$httpMethod = isset($_POST["_method"]) ? strtoupper($_POST["_method"]) : $_SERVER['REQUEST_METHOD'];

$uri = $_SERVER['REQUEST_URI'];

if (false !== $pos = strpos($uri, '?')) {
    $uri = substr($uri, 0, $pos);
}
$uri = rawurldecode($uri);

$routeInfo = $dispatcher->dispatch($httpMethod, $uri);
switch ($routeInfo[0]) {
    case FastRoute\Dispatcher::NOT_FOUND:
        header("Location: /404");
        break;
    case FastRoute\Dispatcher::FOUND:
        $handle = $routeInfo[1];
        $vars = $routeInfo[2];
        try {
            $connection = $container->get(Connection::class);
            $connection->beginTransaction(); // TODO: check if there's a better way to do this or if this is okay
            $response = $container->get($handle)(...array_values($vars));
            $connection->commit();
        } catch (DomainException|InfrastructureException|\Doctrine\DBAL\Exception $e) {
            $connection->rollBack();
            $logger->error($e);
        }
        if ($response instanceof TemplateResponse) {
            echo $twig->render($response->template() . ".html.twig", $response->data());
        } elseif ($response instanceof RedirectResponse) {
            header("Location: {$response->url()}");
        }
        break;
}