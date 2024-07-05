<?php
declare(strict_types=1);

use App\Controllers\Articles\CreateArticleController;
use App\Controllers\Articles\DestroyArticleController;
use App\Controllers\Articles\EditArticleController;
use App\Controllers\Articles\IndexArticleController;
use App\Controllers\Articles\ShowArticleController;
use App\Controllers\Articles\StoreArticleController;
use App\Controllers\Articles\UpdateArticleController;
use App\Controllers\Error\InternalServerErrorController;
use App\Controllers\Error\NotFoundErrorController;

return [
    ["GET", "/404", NotFoundErrorController::class],
    ["GET", "/500", InternalServerErrorController::class],
    ["GET", "/articles", IndexArticleController::class],
    ["POST", "/articles", StoreArticleController::class],
    ["DELETE", "/articles/{id}", DestroyArticleController::class],
    ["PATCH", "/articles/{id}", UpdateArticleController::class],
    ["GET", "/articles/{id}/edit", EditArticleController::class],
    ["GET", "/articles/write", CreateArticleController::class],
    ["GET", "/articles/{id}", ShowArticleController::class],
];