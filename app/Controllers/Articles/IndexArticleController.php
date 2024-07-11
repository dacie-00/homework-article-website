<?php
declare(strict_types=1);

namespace App\Controllers\Articles;

use App\FlashMessage;
use App\Responses\TemplateResponse;
use App\Services\Articles\Exceptions\ArticleRetrievalFailedException;
use App\Services\Articles\IndexArticleService;
use Psr\Log\LoggerInterface;

class IndexArticleController
{
    private IndexArticleService $indexArticleService;
    private FlashMessage $flashMessage;
    private LoggerInterface $logger;

    public function __construct(
        IndexArticleService $indexArticleService,
        FlashMessage $flashMessage,
        LoggerInterface $logger
    ) {
        $this->indexArticleService = $indexArticleService;
        $this->flashMessage = $flashMessage;
        $this->logger = $logger;
    }

    public function __invoke(): TemplateResponse
    {
        try {
            $articles = $this->indexArticleService->execute();
        } catch (ArticleRetrievalFailedException $e) {
            $this->logger->error($e);
            $articles = null;
        }
        $flashMessage = $this->flashMessage->get();

        return new TemplateResponse(
            "articles/index",
            [
                "articles" => $articles,
                "flashMessage" => $flashMessage,
            ]);
    }
}