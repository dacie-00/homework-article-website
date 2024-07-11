<?php
declare(strict_types=1);

namespace App\Services\Comments;

use App\Repositories\Comments\CommentRepositoryInterface;
use App\Repositories\Exceptions\ItemInRepositoryNotFoundException;
use App\Repositories\Exceptions\UpdateInRepositoryFailedException;
use App\Services\Comments\Exceptions\CommentUpdateFailedException;

class LikeCommentService
{
    private CommentRepositoryInterface $commentRepository;

    public function __construct(CommentRepositoryInterface $commentRepository)
    {
        $this->commentRepository = $commentRepository;
    }

    /**
     * @throws CommentUpdateFailedException
     */
    public function execute(string $commentId): void
    {
        try {
            $this->commentRepository->like($commentId);
        } catch (ItemInRepositoryNotFoundException|UpdateInRepositoryFailedException $e) {
            throw new CommentUpdateFailedException(
                "Failed to update like amount for comment with id ($commentId)",
                0,
                $e
            );
        }
    }
}