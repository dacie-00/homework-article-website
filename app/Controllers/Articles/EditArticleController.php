<?php
declare(strict_types=1);

namespace App\Controllers\Articles;

use App\Repositories\Articles\Exceptions\ArticleNotFoundException;
use App\Responses\TemplateResponse;
use App\Services\Articles\GetArticleService;

class EditArticleController
{
    private GetArticleService $getArticleService;

    public function __construct(GetArticleService $getArticleService)
    {
        $this->getArticleService = $getArticleService;
    }

    public function __invoke(string $id): TemplateResponse
    {
        try {
            $article = $this->getArticleService->execute($id);
        } catch (ArticleNotFoundException $e) {
            // TODO: redirect to 404 page
        }
        return new TemplateResponse("articles/edit", ["article" => $article]);
    }
}