<?php
declare(strict_types=1);

namespace App\Controllers\Articles;

use App\Responses\TemplateResponse;
use App\Services\Articles\IndexArticleService;

class IndexArticleController
{
    private IndexArticleService $indexArticlesService;

    public function __construct(IndexArticleService $indexArticlesService)
    {
        $this->indexArticlesService = $indexArticlesService;
    }

    public function __invoke(): TemplateResponse
    {
        $articles = $this->indexArticlesService->execute();

        return new TemplateResponse("articles/index", ["articles" => $articles]);
    }
}