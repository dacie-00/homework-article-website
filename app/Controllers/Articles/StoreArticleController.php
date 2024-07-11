<?php
declare(strict_types=1);

namespace App\Controllers\Articles;

use App\FlashMessage;
use App\Message;
use App\Models\Article;
use App\Responses\RedirectResponse;
use App\Services\Articles\ArticleValidationService;
use App\Services\Articles\Exceptions\ArticleStoringFailedException;
use App\Services\Articles\Exceptions\InvalidArticleContentException;
use App\Services\Articles\Exceptions\InvalidArticleTitleException;
use App\Services\Articles\StoreArticleService;
use Psr\Log\LoggerInterface;

class StoreArticleController
{
    private StoreArticleService $storeArticleService;
    private ArticleValidationService $articleValidationService;
    private FlashMessage $flashMessage;
    private LoggerInterface $logger;

    public function __construct(
        StoreArticleService $storeArticleService,
        ArticleValidationService $articleValidationService,
        FlashMessage $flashMessage,
        LoggerInterface $logger
    ) {
        $this->storeArticleService = $storeArticleService;
        $this->articleValidationService = $articleValidationService;
        $this->flashMessage = $flashMessage;
        $this->logger = $logger;
    }

    public function __invoke(): RedirectResponse
    {
        $title = $_POST["title"];
        $content = $_POST["content"];
        try { // TODO: refactor using validation package
            $this->articleValidationService->execute($title, $content);
        } catch (InvalidArticleTitleException|InvalidArticleContentException $e) {
            $this->flashMessage->set(new Message(
                Message::TYPE_ERROR,
                $e->getMessage(),
                [
                    "title" => "$title",
                    "content" => "$content",
                ]
            ));
            return new RedirectResponse("/articles/write");
        }

        $article = new Article($title, $content);

        try {
            $this->storeArticleService->execute($article);
        } catch (ArticleStoringFailedException $e) {
            $this->logger->error($e);
            $this->flashMessage->set(new Message(
                Message::TYPE_ERROR,
                "Oops! Something went wrong!",
            ));
            return new RedirectResponse("/articles");
        }

        $this->flashMessage->set(new Message(
            Message::TYPE_SUCCESS,
            "Article '{$article->title()}' has been successfully created",
            ["articleId" => $article->id()]
        ));
        return new RedirectResponse("/articles");
    }
}