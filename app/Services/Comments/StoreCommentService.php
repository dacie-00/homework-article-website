<?php
declare(strict_types=1);

namespace App\Services\Comments;


use App\Models\Comment;
use App\Repositories\Comments\CommentRepositoryInterface;
use App\Repositories\Comments\Exceptions\CommentInsertionFailedException;

class StoreCommentService
{
    private CommentRepositoryInterface $commentRepository;

    public function __construct(CommentRepositoryInterface $commentRepository)
    {
        $this->commentRepository = $commentRepository;
    }

    /**
     * @throws CommentInsertionFailedException
     */
    public function execute(Comment $comment): void
    {
        $this->commentRepository->insert($comment);
    }
}