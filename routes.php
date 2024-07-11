<?php
declare(strict_types=1);

use App\Controllers\Articles\CreateFormArticleController;
use App\Controllers\Articles\DestroyArticleController;
use App\Controllers\Articles\EditArticleController;
use App\Controllers\Articles\IndexArticleController;
use App\Controllers\Articles\LikeArticleController;
use App\Controllers\Articles\ShowArticleController;
use App\Controllers\Articles\StoreArticleController;
use App\Controllers\Articles\UpdateArticleController;
use App\Controllers\Comments\DestroyCommentController;
use App\Controllers\Comments\LikeCommentController;
use App\Controllers\Comments\StoreCommentController;
use App\Controllers\Error\InternalServerErrorController;
use App\Controllers\Error\NotFoundErrorController;

return [
    ["GET", "/404", NotFoundErrorController::class],
    ["GET", "/500", InternalServerErrorController::class],
    ["GET", "", IndexArticleController::class],
    ["GET", "/", IndexArticleController::class],
    ["GET", "/articles", IndexArticleController::class],
    ["POST", "/articles", StoreArticleController::class],
    ["DELETE", "/articles/{id}", DestroyArticleController::class],
    ["PATCH", "/articles/{id}", UpdateArticleController::class],
    ["POST", "/articles/{id}/likes", LikeArticleController::class],
    ["GET", "/articles/{id}/edit", EditArticleController::class],
    ["GET", "/articles/write", CreateFormArticleController::class],
    ["GET", "/articles/{id}", ShowArticleController::class],
    ["POST", "/articles/{id}/comments", StoreCommentController::class],
    ["DELETE", "/articles/{articleId}/comments/{commentId}", DestroyCommentController::class],
    ["POST", "/comments/{id}/likes", LikeCommentController::class],
];