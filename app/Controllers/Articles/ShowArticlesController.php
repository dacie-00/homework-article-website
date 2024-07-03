<?php
declare(strict_types=1);

namespace App\Controllers\Articles;

use App\Responses\TemplateResponse;
use App\Services\Articles\Exceptions\ArticleNotFoundException;
use App\Services\Articles\ShowArticleService;

class ShowArticlesController
{
    private ShowArticleService $showArticleService;

    public function __construct(ShowArticleService $showArticleService)
    {
        $this->showArticleService = $showArticleService;
    }

    public function __invoke(string $id): TemplateResponse
    {
        try {
            $article = $this->showArticleService->execute($id);
        } catch (ArticleNotFoundException $e) {
            $article = null;
        }
        return new TemplateResponse("articles/show", ["article" => $article]);
    }
}