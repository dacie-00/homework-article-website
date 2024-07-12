<?php
declare(strict_types=1);

namespace App\Services\Comments;

use App\Repositories\Comments\CommentRepositoryInterface;
use App\Repositories\Exceptions\DeletionInRepositoryFailedException;
use App\Repositories\Likes\LikeRepositoryInterface;
use App\Services\Comments\Exceptions\CommentDeletionFailedException;

class DestroyCommentService
{
    private CommentRepositoryInterface $commentRepository;
    private LikeRepositoryInterface $likeRepository;

    public function __construct(
        CommentRepositoryInterface $commentRepository,
        LikeRepositoryInterface $likeRepository
    ) {
        $this->commentRepository = $commentRepository;
        $this->likeRepository = $likeRepository;
    }

    /**
     * @throws CommentDeletionFailedException
     */
    public function execute(string $commentId): void
    {
        try {
            $this->commentRepository->delete($commentId);
            $this->likeRepository->deleteForItem($commentId);
        } catch (DeletionInRepositoryFailedException $e) {
            throw new CommentDeletionFailedException(
                "Failed to delete comment with id ($commentId)",
                0,
                $e
            );
        }
    }
}