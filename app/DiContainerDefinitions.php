<?php
declare(strict_types=1);

use App\Repositories\Articles\ArticlesRepositoryInterface;
use App\Repositories\Articles\SqliteArticlesRepository;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;
use Monolog\Logger;
use Psr\Log\LoggerInterface;
use function DI\create;
use function DI\get;

/** @var Logger $logger */
/** @var array $connectionParameters */
return [
    LoggerInterface::class => $logger,
    Connection::class =>
        DriverManager::getConnection($connectionParameters),
    ArticlesRepositoryInterface::class =>
        create(SqliteArticlesRepository::class)->constructor(get(Connection::class)),
];