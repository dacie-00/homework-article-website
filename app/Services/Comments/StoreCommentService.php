<?php
declare(strict_types=1);

namespace App\Services\Comments;


use App\Models\Comment;
use App\Repositories\Comments\CommentRepositoryInterface;
use App\Repositories\Exceptions\InsertionInRepositoryFailedException;
use App\Services\Comments\Exceptions\CommentInsertionFailedException;
use App\Services\Comments\Exceptions\CommentStoringFailedException;

class StoreCommentService
{
    private CommentRepositoryInterface $commentRepository;

    public function __construct(CommentRepositoryInterface $commentRepository)
    {
        $this->commentRepository = $commentRepository;
    }

    /**
     * @throws CommentStoringFailedException
     */
    public function execute(Comment $comment): void
    {
        try {
            $this->commentRepository->insert($comment);
        } catch (InsertionInRepositoryFailedException $e) {
            throw new CommentStoringFailedException(
                "Failed to store comment with id ({$comment->id()})",
                0,
                $e
            );
        }
    }
}