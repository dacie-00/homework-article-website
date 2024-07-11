<?php
declare(strict_types=1);

namespace App\Controllers\Articles;

use App\FlashMessage;
use App\Message;
use App\Responses\RedirectResponse;
use App\Services\Articles\ArticleValidationService;
use App\Services\Articles\Exceptions\ArticleRetrievalFailedException;
use App\Services\Articles\Exceptions\ArticleUpdateFailedException;
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
        try { // TODO: refactor with validation package
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
            $this->updateArticleService->execute(
                $article, [
                    "title" => $title,
                    "content" => $content,
                ]
            );
        } catch (ArticleRetrievalFailedException|ArticleUpdateFailedException $e) {
            $this->logger->error($e);
            $this->flashMessage->set(new Message(
                Message::TYPE_ERROR,
                "Oops! Something went wrong!",
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