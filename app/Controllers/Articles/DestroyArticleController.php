<?php
declare(strict_types=1);

namespace App\Controllers\Articles;

use App\FlashMessage;
use App\Message;
use App\Responses\RedirectResponse;
use App\Services\Articles\DestroyArticleService;
use App\Services\Articles\GetArticleService;

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

    public function __invoke(string $id): RedirectResponse
    {
        $article = $this->getArticleService->execute($id);
        $this->destroyArticleService->execute($id);

        $this->flashMessage->set(new Message(
            Message::TYPE_SUCCESS,
            "Article '{$article->title()}' has been deleted successfully!",
        ));

        return new RedirectResponse("/articles");
    }
}