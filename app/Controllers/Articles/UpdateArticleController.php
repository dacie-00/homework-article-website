<?php
declare(strict_types=1);

namespace App\Controllers\Articles;

use App\Repositories\Articles\Exceptions\ArticleNotFoundException;
use App\Responses\RedirectResponse;
use App\Services\Articles\GetArticleService;
use App\Services\Articles\UpdateArticleService;

class UpdateArticleController
{
    private UpdateArticleService $updateArticleService;
    private GetArticleService $getArticleService;

    public function __construct(
        UpdateArticleService $updateArticleService,
        GetArticleService $getArticleService
    ) {
        $this->updateArticleService = $updateArticleService;
        $this->getArticleService = $getArticleService;
    }

    public function __invoke(string $id): RedirectResponse
    {
        $title = $_POST["title"];
        $content = $_POST["content"];
        try {
            $article = $this->getArticleService->execute($id);
        } catch (ArticleNotFoundException $e) {
            echo "oopsie! article doesn't exist looool!!! {$e->getMessage()}";die;
        }
        $this->updateArticleService->execute(
            $article, [
                "title" => $title,
                "content" => $content,
            ]
        );

        return new RedirectResponse("{$id}");
    }
}