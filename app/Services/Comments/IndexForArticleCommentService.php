<?php
declare(strict_types=1);

namespace App\Services\Comments;

use App\Models\Comment;
use App\Repositories\Comments\CommentRepositoryInterface;
use App\Repositories\Exceptions\RetrievalInRepositoryFailedException;
use App\Repositories\Likes\LikeRepositoryInterface;
use App\Services\Comments\Exceptions\CommentRetrievalFailedException;
use Psr\Log\LoggerInterface;

class IndexForArticleCommentService
{
    private CommentRepositoryInterface $commentRepository;
    private LikeRepositoryInterface $likeRepository;
    private LoggerInterface $logger;

    public function __construct(
        CommentRepositoryInterface $commentRepository,
        LikeRepositoryInterface $likeRepository,
        LoggerInterface $logger
    ) {
        $this->commentRepository = $commentRepository;
        $this->likeRepository = $likeRepository;
        $this->logger = $logger;
    }

    /**
     * @return Comment[]
     * @throws CommentRetrievalFailedException
     */
    public function execute($articleId): array
    {
        try {
            $comments = $this->commentRepository->getForArticle($articleId);
        } catch (RetrievalInRepositoryFailedException $e) {
            throw new CommentRetrievalFailedException(
                "Failed to retrieve comments for article with id ($articleId)",
                0,
                $e
            );
        }
        foreach ($comments as $comment) {
            try {
                $likes = $this->likeRepository->getCount(Comment::class, $comment->id());
            } catch (RetrievalInRepositoryFailedException $e) {
                $this->logger->error("Failed to retrieve likes for comment with id ({$comment->id()})");
                break;
            }
            $comment->setLikes($likes);
        }
        return $comments;
    }
}