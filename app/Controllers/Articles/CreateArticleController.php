<?php
declare(strict_types=1);

namespace App\Controllers\Articles;

use App\Responses\TemplateResponse;

class CreateArticleController
{
    public function __invoke()
    {
        return new TemplateResponse("articles/write");
    }
}