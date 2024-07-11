<?php
declare(strict_types=1);

namespace App\Controllers\Articles;

use App\FlashMessage;
use App\Message;
use App\Responses\RedirectResponse;
use App\Services\Articles\DestroyArticleService;
use App\Services\Articles\Exceptions\ArticleDeletionFailedException;
use App\Services\Articles\Exceptions\ArticleRetrievalFailedException;
use App\Services\Articles\Exceptions\ArticleNotFoundException;
use App\Services\Articles\GetArticleService;
use Psr\Log\LoggerInterface;

class DestroyArticleController
{
    private DestroyArticleService $destroyArticleService;
    private FlashMessage $flashMessage;
    private GetArticleService $getArticleService;

    public function __construct(
        DestroyArticleService $destroyArticleService,
        GetArticleService $getArticleService,
        FlashMessage $flashMessage
    ) {
        $this->destroyArticleService = $destroyArticleService;
        $this->flashMessage = $flashMessage;
        $this->getArticleService = $getArticleService;
    }

    public function __invoke(string $articleId): RedirectResponse
    {
        try {
            $article = $this->getArticleService->execute($articleId);
            $this->destroyArticleService->execute($articleId);
        } catch (ArticleRetrievalFailedException|ArticleDeletionFailedException $e) {
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