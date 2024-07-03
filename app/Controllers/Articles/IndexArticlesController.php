<?php
declare(strict_types=1);

namespace App\Controllers\Articles;

use App\Responses\TemplateResponse;
use App\Services\Articles\IndexArticlesService;

class IndexArticlesController
{
    private IndexArticlesService $indexArticlesService;

    public function __construct(IndexArticlesService $indexArticlesService)
    {
        $this->indexArticlesService = $indexArticlesService;
    }

    public function __invoke(): TemplateResponse
    {
        $articles = $this->indexArticlesService->execute();

        return new TemplateResponse("articles/index", ["articles" => $articles]);
    }
}