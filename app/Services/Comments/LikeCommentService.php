<?php
declare(strict_types=1);

namespace App\Services\Comments;

use App\Repositories\Comments\CommentRepositoryInterface;
use App\Repositories\Comments\Exceptions\CommentNotFoundException;
use App\Repositories\Comments\Exceptions\CommentUpdateFailedException;

class LikeCommentService
{
    private CommentRepositoryInterface $commentRepository;

    public function __construct(CommentRepositoryInterface $commentRepository)
    {
        $this->commentRepository = $commentRepository;
    }

    /**
     * @throws CommentNotFoundException
     * @throws CommentUpdateFailedException
     */
    public function execute(string $commentId): void
    {
        $this->commentRepository->like($commentId);
    }
}