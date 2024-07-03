<?php
declare(strict_types=1);

namespace App\Controllers\Articles;

use App\Models\Article;
use App\Responses\RedirectResponse;
use App\Services\Articles\Exceptions\ArticleCreationFailedException;
use App\Services\Articles\StoreArticleService;

class StoreArticleController
{
    private StoreArticleService $storeArticlesService;

    public function __construct(StoreArticleService $storeArticlesService)
    {
        $this->storeArticlesService = $storeArticlesService;
    }

    public function __invoke()
    {
        $title = $_POST["title"];
        $content = $_POST["content"];
        $article = new Article($title, $content);
        try {
            $this->storeArticlesService->execute($article);
        } catch (ArticleCreationFailedException $e) {
            echo "oops didn't make article!!!"; // TODO: session error handling
        }
        return new RedirectResponse("/articles");
    }
}