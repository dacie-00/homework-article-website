<?php
declare(strict_types=1);

namespace App\Repositories\Articles;

use App\Models\Article;
use App\Repositories\Exception\RepositoryDeletionFailedException;
use App\Repositories\Exception\RepositoryInsertionFailedException;

interface ArticleRepositoryInterface
{
    /**
     * @throws RepositoryInsertionFailedException
     */
    public function insert(Article $article): void;

    public function get(string $articleId): ?Article;

    /**
     * @return Article[]
     */
    public function getAll(): array;

    /**
     * @throws RepositoryDeletionFailedException
     */
    public function delete(string $articleId);
}