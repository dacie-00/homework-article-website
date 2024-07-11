<?php
declare(strict_types=1);

namespace App\Controllers\Articles;

use App\FlashMessage;
use App\Responses\TemplateResponse;
use App\Services\Articles\Exceptions\ArticleRetrievalFailedException;
use App\Services\Articles\Exceptions\ArticleNotFoundException;
use App\Services\Articles\ShowArticleService;
use App\Services\Comments\Exceptions\CommentRetrievalFailedException;
use App\Services\Comments\IndexForArticleCommentService;
use Psr\Log\LoggerInterface;

class ShowArticleController
{
    private ShowArticleService $showArticleService;
    private IndexForArticleCommentService $IndexForArticleCommentService;
    private FlashMessage $flashMessage;
    private LoggerInterface $logger;

    public function __construct(
        ShowArticleService $showArticleService,
        IndexForArticleCommentService $IndexForArticleCommentService,
        FlashMessage $flashMessage,
        LoggerInterface $logger
    ) {
        $this->showArticleService = $showArticleService;
        $this->IndexForArticleCommentService = $IndexForArticleCommentService;
        $this->flashMessage = $flashMessage;
        $this->logger = $logger;
    }

    public function __invoke(string $id): TemplateResponse
    {
        $comments = [];
        try {
            $article = $this->showArticleService->execute($id);
        } catch (ArticleRetrievalFailedException $e) {
            $this->logger->error($e);
            $article = null;
        }
        if ($article) {
            try {
                $comments = $this->IndexForArticleCommentService->execute($id);
            } catch (CommentRetrievalFailedException $e) {
                $this->logger->error($e);
                $comments = null;
            }
        }

        $flashMessage = $this->flashMessage->get();
        return new TemplateResponse("articles/show",
            ["article" => $article, "comments" => $comments, "flashMessage" => $flashMessage]);
    }
}