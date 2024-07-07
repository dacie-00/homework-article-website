<?php
declare(strict_types=1);

namespace App\Repositories\Comments;

use App\Models\Comment;
use App\Repositories\Comments\Exceptions\CommentDeletionFailedException;
use App\Repositories\Comments\Exceptions\CommentFetchFailedException;
use App\Repositories\Comments\Exceptions\CommentInsertionFailedException;
use App\Repositories\Comments\Exceptions\CommentNotFoundException;
use App\Repositories\Comments\Exceptions\CommentUpdateFailedException;

interface CommentRepositoryInterface
{
    /**
     * @throws CommentInsertionFailedException
     */
    public function insert(Comment $comment): void;

    /**
     * @throws CommentFetchFailedException
     * @throws CommentNotFoundException
     */
    public function get(string $commentId): Comment;

    /**
     * @return Comment[]
     * @throws CommentFetchFailedException
     */
    public function getForArticle(string $articleId): array;

    /**
     * @throws CommentDeletionFailedException
     */
    public function delete(string $commentId);

    /**
     * @throws CommentUpdateFailedException
     * @throws CommentNotFoundException
     */
    public function like(string $articleId);
}