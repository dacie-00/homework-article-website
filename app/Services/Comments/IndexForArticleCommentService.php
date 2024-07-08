<?php
declare(strict_types=1);

namespace App\Services\Comments;

use App\Models\Comment;
use App\Repositories\Comments\CommentRepositoryInterface;
use App\Repositories\Comments\Exceptions\CommentFetchFailedException;
use Psr\Log\LoggerInterface;

class IndexForArticleCommentService
{
    private CommentRepositoryInterface $commentRepository;
    private LoggerInterface $logger;

    public function __construct(
        CommentRepositoryInterface $commentRepository,
        LoggerInterface $logger
    ) {
        $this->commentRepository = $commentRepository;
        $this->logger = $logger;
    }

    /**
     * @return Comment[]
     */
    public function execute($articleId): array
    {
        try {
            $comments = $this->commentRepository->getForArticle($articleId);
        } catch (CommentFetchFailedException $e) {
            $this->logger->error($e->getMessage());
            return [];
        }
        return $comments;
    }
}