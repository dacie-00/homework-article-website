<?php
declare(strict_types=1);

namespace App\Controllers\Articles;

use App\Responses\RedirectResponse;
use App\Services\Articles\DestroyArticleService;

class DestroyArticleController
{
    private DestroyArticleService $destroyArticleService;

    public function __construct(DestroyArticleService $destroyArticleService)
    {
        $this->destroyArticleService = $destroyArticleService;
    }

    public function __invoke(string $id): RedirectResponse
    {
        $this->destroyArticleService->execute($id);
        return new RedirectResponse("/articles");
    }
}