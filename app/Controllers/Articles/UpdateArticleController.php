<?php
declare(strict_types=1);

namespace App\Controllers\Articles;

use App\FlashMessage;
use App\Message;
use App\Repositories\Articles\Exceptions\ArticleNotFoundException;
use App\Responses\RedirectResponse;
use App\Services\Articles\GetArticleService;
use App\Services\Articles\UpdateArticleService;

class UpdateArticleController
{
    private UpdateArticleService $updateArticleService;
    private GetArticleService $getArticleService;
    private FlashMessage $flashMessage;

    public function __construct(
        UpdateArticleService $updateArticleService,
        GetArticleService $getArticleService,
        FlashMessage $flashMessage
    ) {
        $this->updateArticleService = $updateArticleService;
        $this->getArticleService = $getArticleService;
        $this->flashMessage = $flashMessage;
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

        $this->flashMessage->set(new Message(
            Message::TYPE_SUCCESS,
            "Article '{$article->title()}' updated successfully!",
            ["articleId" => $article->id()]
        ));

        return new RedirectResponse("/articles");
    }
}