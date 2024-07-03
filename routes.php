<?php
declare(strict_types=1);

use App\Controllers\Articles\IndexArticleController;
use App\Controllers\Articles\ShowArticleController;
use App\Controllers\Articles\StoreArticleController;
use App\Controllers\Articles\WriteArticleController;

return [
    ["GET", "/articles", IndexArticleController::class],
    ["POST", "/articles", StoreArticleController::class],
    ["GET", "/articles/write", WriteArticleController::class],
    ["GET", "/articles/{id}", ShowArticleController::class],
];