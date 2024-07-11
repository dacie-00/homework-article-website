<?php
declare(strict_types=1);

namespace App\Repositories\Articles;

use App\Models\Article;
use App\Repositories\Exceptions\DeletionInRepositoryFailedException;
use App\Repositories\Exceptions\InsertionInRepositoryFailedException;
use App\Repositories\Exceptions\ItemInRepositoryNotFoundException;
use App\Repositories\Exceptions\RetrievalInRepositoryFailedException;
use App\Repositories\Exceptions\UpdateInRepositoryFailedException;

interface ArticleRepositoryInterface
{
    /**
     * @throws InsertionInRepositoryFailedException
     */
    public function insert(Article $article): void;

    /**
     * @throws RetrievalInRepositoryFailedException
     * @throws ItemInRepositoryNotFoundException
     */
    public function get(string $articleId): Article;

    /**
     * @return Article[]
     * @throws RetrievalInRepositoryFailedException
     */
    public function getAll(): array;

    /**
     * @throws DeletionInRepositoryFailedException
     */
    public function delete(string $articleId): void;

    /**
     * @throws UpdateInRepositoryFailedException
     */
    public function update(Article $article): void;

    /**
     * @throws UpdateInRepositoryFailedException
     * @throws ItemInRepositoryNotFoundException
     */
    public function like(string $articleId): void;
}