<?php
declare(strict_types=1);

use App\Responses\TemplateResponse;
use App\Services\Database\InitializeDatabaseService;
use DI\ContainerBuilder;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

require_once __DIR__ . '/vendor/autoload.php';

// TODO: make bootstrap file

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$loader = new FilesystemLoader("views");
$twig = new Environment($loader);

$logger = new Logger("app");
$logger->pushHandler(new StreamHandler("storage/app.log"));

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

($container->get(InitializeDatabaseService::class))->execute();

$dispatcher = FastRoute\simpleDispatcher(function (FastRoute\RouteCollector $r) {
    $routes = include __DIR__ . "/routes.php";
    foreach ($routes as $route) {
        $r->addRoute($route[0], $route[1], $route[2]);
    }
});

$httpMethod = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];

if (false !== $pos = strpos($uri, '?')) {
    $uri = substr($uri, 0, $pos);
}
$uri = rawurldecode($uri);

$routeInfo = $dispatcher->dispatch($httpMethod, $uri);
switch ($routeInfo[0]) {
    case FastRoute\Dispatcher::NOT_FOUND:
        echo "404";
//        header("Location: /404");
        break;
    case FastRoute\Dispatcher::FOUND:
        $handle = $routeInfo[1];
        $vars = $routeInfo[2];
        try {
            $response = $container->get($handle)(...array_values($vars));
        } catch (Exception $e) {
            $logger->error($e->getMessage());
        }
        if ($response instanceof TemplateResponse) {
            echo $twig->render($response->template() . ".html.twig", $response->data());
//        } elseif ($response instanceof RedirectResponse) {
//            header("Location: {$response->url()}");
        }
        break;
}