<?php
declare(strict_types=1);

namespace App\Services\Comments;

use App\Repositories\Comments\CommentRepositoryInterface;
use App\Repositories\Comments\Exceptions\CommentDeletionFailedException;

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
        $this->commentRepository->delete($commendId);
    }
}