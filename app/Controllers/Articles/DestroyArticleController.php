<?php
declare(strict_types=1);

namespace App\Controllers\Articles;

use App\FlashMessage;
use App\Message;
use App\Responses\RedirectResponse;
use App\Services\Articles\DestroyArticleService;
use App\Services\Articles\Exceptions\ArticleDeletionFailedException;
use App\Services\Articles\Exceptions\ArticleRetrievalFailedException;
use App\Services\Articles\GetArticleService;
use App\Services\Comments\DestroyCommentService;
use App\Services\Comments\ArticleCommentService;
use Psr\Log\LoggerInterface;

class DestroyArticleController
{
    private DestroyArticleService $destroyArticleService;
    private GetArticleService $getArticleService;
    private FlashMessage $flashMessage;
    private LoggerInterface $logger;

    public function __construct(
        DestroyArticleService $destroyArticleService,
        GetArticleService $getArticleService,
        FlashMessage $flashMessage,
        LoggerInterface $logger
    ) {
        $this->destroyArticleService = $destroyArticleService;
        $this->getArticleService = $getArticleService;
        $this->flashMessage = $flashMessage;
        $this->logger = $logger;
    }

    public function __invoke(string $articleId): RedirectResponse
    {
        try {
            $article = $this->getArticleService->execute($articleId);
            $this->destroyArticleService->execute($articleId);
        } catch (ArticleRetrievalFailedException|ArticleDeletionFailedException $e) {
            $this->logger->error($e);
            $this->flashMessage->set(new Message(
                Message::TYPE_ERROR,
                "Oops! Something went wrong!",
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