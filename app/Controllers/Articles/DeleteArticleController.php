<?php
declare(strict_types=1);

namespace App\Controllers\Articles;

use App\Responses\RedirectResponse;
use App\Services\Articles\DeleteArticleService;

class DeleteArticleController
{
    private DeleteArticleService $deleteArticleService;

    public function __construct(DeleteArticleService $deleteArticleService)
    {
        $this->deleteArticleService = $deleteArticleService;
    }

    public function __invoke(string $id): RedirectResponse
    {
        $this->deleteArticleService->execute($id);
        return new RedirectResponse("/articles");
    }
}