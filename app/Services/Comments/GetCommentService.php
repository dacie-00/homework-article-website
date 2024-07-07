<?php
declare(strict_types=1);

namespace App\Services\Comments;

use App\Models\Comment;
use App\Repositories\Comments\CommentRepositoryInterface;
use App\Repositories\Comments\Exceptions\CommentFetchFailedException;
use App\Repositories\Comments\Exceptions\CommentNotFoundException;

class GetCommentService
{
    private CommentRepositoryInterface $commentRepository;

    public function __construct(CommentRepositoryInterface $commentRepository)
    {
        $this->commentRepository = $commentRepository;
    }

    /**
     * @throws CommentFetchFailedException
     * @throws CommentNotFoundException
     */
    public function execute(string $id): Comment
    {
        return $this->commentRepository->get($id);
    }

}