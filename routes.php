<?php
declare(strict_types=1);

use App\Controllers\Articles\ShowArticlesController;

return [
    ["GET", "/articles/{id}", ShowArticlesController::class]
];