<?php
declare(strict_types=1);

namespace App\Controllers\Articles;

use App\Repositories\Articles\Exceptions\ArticleNotFoundException;
use App\Repositories\Articles\Exceptions\ArticleUpdateFailedException;
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

    public function __invoke(string $id)
    {
        try {
            $this->likeArticleService->execute($id);
            http_response_code(200);
        } catch (ArticleNotFoundException $e) {
            $this->logger->error($e->getMessage());
            http_response_code(404);
        } catch (ArticleUpdateFailedException $e) {
            $this->logger->error($e->getMessage());
            http_response_code(500);
        }
    }
}