<?php
declare(strict_types=1);

namespace App\Controllers\Articles;

use App\FlashMessage;
use App\Message;
use App\Repositories\Articles\Exceptions\ArticleFetchFailedException;
use App\Repositories\Articles\Exceptions\ArticleNotFoundException;
use App\Repositories\Articles\Exceptions\ArticleUpdateFailedException;
use App\Responses\RedirectResponse;
use App\Services\Articles\ArticleValidationService;
use App\Services\Articles\Exceptions\InvalidArticleContentException;
use App\Services\Articles\Exceptions\InvalidArticleTitleException;
use App\Services\Articles\GetArticleService;
use App\Services\Articles\UpdateArticleService;
use Psr\Log\LoggerInterface;

class UpdateArticleController
{
    private UpdateArticleService $updateArticleService;
    private GetArticleService $getArticleService;
    private ArticleValidationService $articleValidationService;
    private FlashMessage $flashMessage;
    private LoggerInterface $logger;

    public function __construct(
        UpdateArticleService $updateArticleService,
        GetArticleService $getArticleService,
        ArticleValidationService $articleValidationService,
        FlashMessage $flashMessage,
        LoggerInterface $logger
    ) {
        $this->updateArticleService = $updateArticleService;
        $this->getArticleService = $getArticleService;
        $this->articleValidationService = $articleValidationService;
        $this->flashMessage = $flashMessage;
        $this->logger = $logger;
    }

    public function __invoke(string $id): RedirectResponse
    {
        $title = $_POST["title"];
        $content = $_POST["content"];
        try {
            $this->articleValidationService->execute($title, $content);
        } catch (InvalidArticleTitleException|InvalidArticleContentException $e) {
            $this->flashMessage->set(new Message(
                Message::TYPE_ERROR,
                $e->getMessage(),
                [
                    "issue" => $e instanceof InvalidArticleTitleException ? "title" : "content",
                    "title" => "$title",
                    "content" => "$content",
                ]
            ));
            return new RedirectResponse("/articles/$id/edit");
        }
        try {
            $article = $this->getArticleService->execute($id);
        } catch (ArticleNotFoundException $e) {
            $this->logger->error("Attempt to update article '$title' that doesn't exist - {$e->getMessage()}");
            $this->flashMessage->set(new Message(
                Message::TYPE_ERROR,
                "The article you are trying to update does not exist",
            ));
            return new RedirectResponse("/404");
        } catch (ArticleFetchFailedException $e) {
            $this->logger->error("Attempt to fetch article '$title' failed - {$e->getMessage()}");
            $this->flashMessage->set(new Message(
                Message::TYPE_ERROR,
                "failed to fetch article '$title'",
            ));
            return new RedirectResponse("/500");
        }
        try {
            $this->updateArticleService->execute(
                $article, [
                    "title" => $title,
                    "content" => $content,
                ]
            );
        } catch (ArticleUpdateFailedException $e) {
            $this->logger->error("Failed to update article '{$article->title()}' - {$e->getMessage()}");
            $this->flashMessage->set(new Message(
                Message::TYPE_ERROR,
                "Internal Error - failed to update article '{$article->title()}'",
            ));
            return new RedirectResponse("/articles");
        }

        $this->flashMessage->set(new Message(
            Message::TYPE_SUCCESS,
            "Article '{$article->title()}' has been successfully updated",
            ["articleId" => $article->id()]
        ));

        return new RedirectResponse("/articles");
    }
}