<?php
declare(strict_types=1);

namespace App\Controllers\Articles;

use App\Responses\RedirectResponse;
use App\Services\Articles\Exceptions\ArticleUpdateFailedException;
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
        } catch (ArticleUpdateFailedException $e) {
            $this->logger->error($e->getMessage());
        }
        return new RedirectResponse("/articles/$articleId");
    }
}