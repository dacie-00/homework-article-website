<?php
declare(strict_types=1);

namespace App\Controllers\Articles;

use App\Models\Article;
use App\Responses\RedirectResponse;
use App\Services\Articles\Exceptions\ArticleUpdateFailedException;
use App\Services\Articles\LikeArticleService;
use App\Services\Likes\Exceptions\ItemLikeFailedException;
use App\Services\Likes\LikeItemService;
use Psr\Log\LoggerInterface;

class LikeArticleController
{
    private LikeItemService $likeItemService;
    private LoggerInterface $logger;

    public function __construct(LikeItemService $likeItemService, LoggerInterface $logger)
    {
        $this->likeItemService = $likeItemService;
        $this->logger = $logger;
    }

    public function __invoke(string $articleId): RedirectResponse
    {
        try {
            $this->likeItemService->execute(Article::class, $articleId);
        } catch (ItemLikeFailedException $e) {
            $this->logger->error($e);
        }
        return new RedirectResponse("/articles/$articleId");
    }
}