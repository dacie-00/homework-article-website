<?php
declare(strict_types=1);

namespace App\Controllers\Articles;

use App\FlashMessage;
use App\Message;
use App\Models\Article;
use App\Repositories\Articles\Exceptions\ArticleInsertionFailedException;
use App\Responses\RedirectResponse;
use App\Services\Articles\StoreArticleService;
use Psr\Log\LoggerInterface;

class StoreArticleController
{
    private StoreArticleService $storeArticleService;
    private FlashMessage $flashMessage;
    private LoggerInterface $logger;

    public function __construct(
        StoreArticleService $storeArticleService,
        FlashMessage $flashMessage,
        LoggerInterface $logger
    ) {
        $this->storeArticleService = $storeArticleService;
        $this->flashMessage = $flashMessage;
        $this->logger = $logger;
    }

    public function __invoke(): RedirectResponse
    {
        $title = $_POST["title"];
        $content = $_POST["content"];
        // TODO: validate and sanitize input
        $article = new Article($title, $content);
        try {
            $this->storeArticleService->execute($article);
        } catch (ArticleInsertionFailedException $e) {
            $this->logger->error("Failed to create article '{$article->title()}' - {$e->getMessage()}");
            $this->flashMessage->set(new Message(
                Message::TYPE_ERROR,
                "Internal Error - failed to create article '{$article->title()}'",
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