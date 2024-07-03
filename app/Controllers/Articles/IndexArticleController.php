<?php
declare(strict_types=1);

namespace App\Controllers\Articles;

use App\Responses\TemplateResponse;
use App\Services\Articles\IndexArticleService;

class IndexArticleController
{
    private IndexArticleService $indexArticleService;

    public function __construct(IndexArticleService $indexArticleService)
    {
        $this->indexArticleService = $indexArticleService;
    }

    public function __invoke(): TemplateResponse
    {
        $articles = $this->indexArticleService->execute();

        return new TemplateResponse("articles/index", ["articles" => $articles]);
    }
}