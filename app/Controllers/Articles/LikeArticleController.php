<?php
declare(strict_types=1);

namespace App\Controllers\Articles;

use App\Repositories\Articles\Exceptions\ArticleNotFoundException;
use App\Repositories\Articles\Exceptions\ArticleUpdateFailedException;
use App\Responses\RedirectResponse;
use App\Services\Articles\LikeArticleService;
use Psr\Log\LoggerInterface;

class LikeArticleController
{
    private LikeArticleService $likeArticleService;
    private LoggerInterface $logger;

    public function __construct(LikeArticleService $likeArticleService, LoggerInterface $logger)
    {
        $this->likeArticleService = $likeArticleService;
        $this->logger = $logger;
    }

    public function __invoke(string $articleId): RedirectResponse
    {
        try {
            $this->likeArticleService->execute($articleId);
            return new RedirectResponse("/articles/{$articleId}");
        } catch (ArticleNotFoundException|ArticleUpdateFailedException $e) {
            $this->logger->error($e->getMessage());
            return new RedirectResponse("/articles/{$articleId}");
        }
    }
}