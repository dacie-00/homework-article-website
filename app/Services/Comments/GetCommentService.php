<?php
declare(strict_types=1);

namespace App\Services\Comments;

use App\Models\Comment;
use App\Repositories\Comments\CommentRepositoryInterface;
use App\Repositories\Exceptions\ItemInRepositoryNotFoundException;
use App\Repositories\Exceptions\RetrievalInRepositoryFailedException;
use App\Services\Comments\Exceptions\CommentRetrievalFailedException;

class GetCommentService
{
    private CommentRepositoryInterface $commentRepository;

    public function __construct(CommentRepositoryInterface $commentRepository)
    {
        $this->commentRepository = $commentRepository;
    }

    /**
     * @throws CommentRetrievalFailedException
     */
    public function execute(string $commentId): Comment
    {
        try {
            return $this->commentRepository->get($commentId);
        } catch (ItemInRepositoryNotFoundException|RetrievalInRepositoryFailedException $e) {
            throw new CommentRetrievalFailedException(
                "Failed to retrieve comment with id ($commentId)",
                0,
                $e
            );
        }
    }

}