<?php
declare(strict_types=1);

namespace App\Controllers\Articles;

use App\Repositories\Articles\Exceptions\ArticleFetchFailedException;
use App\Repositories\Articles\Exceptions\ArticleNotFoundException;
use App\Responses\TemplateResponse;
use App\Services\Articles\ShowArticleService;
use App\Services\Comments\IndexForArticleCommentService;

class ShowArticleController
{
    private ShowArticleService $showArticleService;
    private IndexForArticleCommentService $IndexForArticleCommentService;

    public function __construct(
        ShowArticleService $showArticleService,
        IndexForArticleCommentService $IndexForArticleCommentService
    ) {
        $this->showArticleService = $showArticleService;
        $this->IndexForArticleCommentService = $IndexForArticleCommentService;
    }

    public function __invoke(string $id): TemplateResponse
    {
        $comments = [];
        try {
            $article = $this->showArticleService->execute($id);
        } catch (ArticleNotFoundException|ArticleFetchFailedException $e) {
            $article = null;
        }
        if ($article !== null) {
            $comments = $this->IndexForArticleCommentService->execute($id);
        }

        return new TemplateResponse("articles/show", ["article" => $article, "comments" => $comments]);
    }
}