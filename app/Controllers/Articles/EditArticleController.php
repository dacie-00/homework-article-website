<?php
declare(strict_types=1);

namespace App\Controllers\Articles;

use App\FlashMessage;
use App\Responses\TemplateResponse;
use App\Services\Articles\Exceptions\ArticleRetrievalFailedException;
use App\Services\Articles\Exceptions\ArticleNotFoundException;
use App\Services\Articles\GetArticleService;
use Psr\Log\LoggerInterface;

class EditArticleController
{
    private GetArticleService $getArticleService;
    private FlashMessage $flashMessage;

    public function __construct(
        GetArticleService $getArticleService,
        FlashMessage $flashMessage
    ) {
        $this->getArticleService = $getArticleService;
        $this->flashMessage = $flashMessage;
    }

    /**
     * @throws ArticleRetrievalFailedException
     */
    public function __invoke(string $id): TemplateResponse
    {
        $article = $this->getArticleService->execute($id);

        $flashMessage = $this->flashMessage->get();
        return new TemplateResponse("articles/edit", [
            "article" => $article,
            "flashMessage" => $flashMessage
        ]);
    }
}