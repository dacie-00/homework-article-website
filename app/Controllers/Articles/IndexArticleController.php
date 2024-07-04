<?php
declare(strict_types=1);

namespace App\Controllers\Articles;

use App\FlashMessage;
use App\Responses\TemplateResponse;
use App\Services\Articles\IndexArticleService;

class IndexArticleController
{
    private IndexArticleService $indexArticleService;
    private FlashMessage $flashMessage;

    public function __construct(
        IndexArticleService $indexArticleService,
        FlashMessage $flashMessage
    ) {
        $this->indexArticleService = $indexArticleService;
        $this->flashMessage = $flashMessage;
    }

    public function __invoke(): TemplateResponse
    {
        $articles = $this->indexArticleService->execute();
        $flashMessage = $this->flashMessage->get();

        return new TemplateResponse(
            "articles/index",
            [
                "articles" => $articles,
                "flashMessage" => $flashMessage,
            ]);
    }
}