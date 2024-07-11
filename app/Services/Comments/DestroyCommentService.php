<?php
declare(strict_types=1);

namespace App\Services\Comments;

use App\Repositories\Comments\CommentRepositoryInterface;
use App\Repositories\Exceptions\DeletionInRepositoryFailedException;
use App\Services\Comments\Exceptions\CommentDeletionFailedException;

class DestroyCommentService
{
    private CommentRepositoryInterface $commentRepository;

    public function __construct(CommentRepositoryInterface $commentRepository)
    {
        $this->commentRepository = $commentRepository;
    }

    /**
     * @throws CommentDeletionFailedException
     */
    public function execute(string $commendId): void
    {
        try {
            $this->commentRepository->delete($commendId);
        } catch (DeletionInRepositoryFailedException $e) {
            throw new CommentDeletionFailedException(
                "Failed to delete comment with id ($commendId)",
                0,
                $e
            );
        }
    }
}