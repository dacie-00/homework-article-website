<?php
declare(strict_types=1);

namespace App\Repositories\Comments;

use App\Models\Comment;
use App\Repositories\Exceptions\DeletionInRepositoryFailedException;
use App\Repositories\Exceptions\InsertionInRepositoryFailedException;
use App\Repositories\Exceptions\ItemInRepositoryNotFoundException;
use App\Repositories\Exceptions\RetrievalInRepositoryFailedException;
use App\Repositories\Exceptions\UpdateInRepositoryFailedException;

interface CommentRepositoryInterface
{
    /**
     * @throws InsertionInRepositoryFailedException
     */
    public function insert(Comment $comment): void;

    /**
     * @throws RetrievalInRepositoryFailedException
     * @throws ItemInRepositoryNotFoundException
     */
    public function get(string $commentId): Comment;

    /**
     * @return Comment[]
     * @throws RetrievalInRepositoryFailedException
     */
    public function getForArticle(string $articleId): array;

    /**
     * @throws DeletionInRepositoryFailedException
     */
    public function delete(string $commentId): void;

    /**
     * @throws UpdateInRepositoryFailedException
     * @throws ItemInRepositoryNotFoundException
     */
    public function like(string $commentId): void;
}