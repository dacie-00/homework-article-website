<?php
declare(strict_types=1);

namespace App\Controllers\Articles;

use App\FlashMessage;
use App\Message;
use App\Models\Article;
use App\Repositories\Articles\Exceptions\ArticleInsertionFailedException;
use App\Responses\RedirectResponse;
use App\Services\Articles\StoreArticleService;

class StoreArticleController
{
    private StoreArticleService $storeArticleService;
    private FlashMessage $flashMessage;

    public function __construct(
        StoreArticleService $storeArticleService,
        FlashMessage $flashMessage
    ) {
        $this->storeArticleService = $storeArticleService;
        $this->flashMessage = $flashMessage;
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
        $this->flashMessage->set(new Message(
            Message::TYPE_SUCCESS,
            "Article '{$article->title()}' has been successfully created!",
            ["articleId" => $article->id()]
        ));
        return new RedirectResponse("/articles");
    }
}