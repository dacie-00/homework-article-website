<?php
declare(strict_types=1);

use App\Repositories\Articles\ArticleRepositoryInterface;
use App\Repositories\Articles\DoctrineDbalArticleRepository;
use App\Repositories\Comments\CommentRepositoryInterface;
use App\Repositories\Comments\DoctrineDbalCommentRepository;
use App\Repositories\Likes\DoctrineDbalLikeRepository;
use App\Repositories\Likes\LikeRepositoryInterface;
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
    ArticleRepositoryInterface::class =>
        create(DoctrineDbalArticleRepository::class)->constructor(get(Connection::class)),
    CommentRepositoryInterface::class =>
        create(DoctrineDbalCommentRepository::class)->constructor(get(Connection::class)),
    LikeRepositoryInterface::class =>
        create(DoctrineDbalLikeRepository::class)->constructor(get(Connection::class)),
];