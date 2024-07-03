<?php
declare(strict_types=1);

use App\Controllers\Articles\IndexArticlesController;
use App\Controllers\Articles\ShowArticlesController;
use App\Controllers\Articles\StoreArticlesController;
use App\Controllers\Articles\WriteArticlesController;

return [
    ["GET", "/articles", IndexArticlesController::class],
    ["POST", "/articles", StoreArticlesController::class],
    ["GET", "/articles/write", WriteArticlesController::class],
    ["GET", "/articles/{id}", ShowArticlesController::class],
];