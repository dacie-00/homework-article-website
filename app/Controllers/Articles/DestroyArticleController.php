<?php
declare(strict_types=1);

namespace App\Controllers\Articles;

use App\FlashMessage;
use App\Message;
use App\Repositories\Articles\Exceptions\ArticleDeletionFailedException;
use App\Repositories\Articles\Exceptions\ArticleFetchFailedException;
use App\Repositories\Articles\Exceptions\ArticleNotFoundException;
use App\Responses\RedirectResponse;
use App\Services\Articles\DestroyArticleService;
use App\Services\Articles\GetArticleService;
use Psr\Log\LoggerInterface;

class DestroyArticleController
{
    private DestroyArticleService $destroyArticleService;
    private FlashMessage $flashMessage;
    private GetArticleService $getArticleService;
    private LoggerInterface $logger;

    public function __construct(
        DestroyArticleService $destroyArticleService,
        GetArticleService $getArticleService,
        FlashMessage $flashMessage,
        LoggerInterface $logger
    ) {
        $this->destroyArticleService = $destroyArticleService;
        $this->flashMessage = $flashMessage;
        $this->getArticleService = $getArticleService;
        $this->logger = $logger;
    }

    public function __invoke(string $articleId): RedirectResponse
    {
        try {
            $article = $this->getArticleService->execute($articleId);
        } catch (ArticleNotFoundException $e) {
            $this->logger->error("Attempt to delete article that doesn't exist - {$e->getMessage()}");
            $this->flashMessage->set(new Message(
                Message::TYPE_ERROR,
                "Failed to find and delete article!",
            ));
            return new RedirectResponse("/articles");
        } catch (ArticleFetchFailedException $e) {
            $this->logger->error("Failed to fetch article - {$e->getMessage()}");
            $this->flashMessage->set(new Message(
                Message::TYPE_ERROR,
                "Failed to delete article!",
            ));
            return new RedirectResponse("/articles");
        }
        try {
            $this->destroyArticleService->execute($articleId);
        } catch (ArticleDeletionFailedException $e) {
            $this->logger->error("Failed to delete article - {$e->getMessage()}");
            $this->flashMessage->set(new Message(
                Message::TYPE_ERROR,
                "Failed to delete article!",
            ));
            return new RedirectResponse("/articles");
        }

        $this->flashMessage->set(new Message(
            Message::TYPE_SUCCESS,
            "Article '{$article->title()}' has been successfully deleted!",
        ));

        return new RedirectResponse("/articles");
    }
}