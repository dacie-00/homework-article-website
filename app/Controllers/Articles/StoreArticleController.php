<?php
declare(strict_types=1);

namespace App\Controllers\Articles;

use App\Models\Article;
use App\Repositories\Articles\Exceptions\ArticleCreationFailedException;
use App\Repositories\Articles\Exceptions\ArticleInsertionFailedException;
use App\Responses\RedirectResponse;
use App\Services\Articles\StoreArticleService;

class StoreArticleController
{
    private StoreArticleService $storeArticleService;

    public function __construct(StoreArticleService $storeArticleService)
    {
        $this->storeArticleService = $storeArticleService;
    }

    public function __invoke(): RedirectResponse
    {
        $title = $_POST["title"];
        $content = $_POST["content"];
        $article = new Article($title, $content);
        try {
            $this->storeArticleService->execute($article);
        } catch (ArticleInsertionFailedException $e) {
            echo "oops didn't make article!!! {$e->getMessage()}"; // TODO: session error handling
        }
        return new RedirectResponse("/articles");
    }
}