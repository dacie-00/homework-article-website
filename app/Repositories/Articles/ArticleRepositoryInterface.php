<?php
declare(strict_types=1);

namespace App\Repositories\Articles;

use App\Models\Article;
use App\Repositories\Articles\Exceptions\ArticleCreationFailedException;
use App\Repositories\Articles\Exceptions\ArticleDeletionFailedException;
use App\Repositories\Articles\Exceptions\ArticleInsertionFailedException;
use App\Repositories\Articles\Exceptions\ArticleNotFoundException;
use App\Repositories\Articles\Exceptions\ArticleUpdateFailedException;
use App\Repositories\Exception\RepositoryDeletionFailedException;
use App\Repositories\Exception\RepositoryInsertionFailedException;

interface ArticleRepositoryInterface
{
    /**
     * @throws ArticleInsertionFailedException
     */
    public function insert(Article $article): void;

    /**
     * @throws ArticleNotFoundException
     */
    public function get(string $articleId): ?Article;

    /**
     * @return Article[]
     */
    public function getAll(): array;

    /**
     * @throws ArticleDeletionFailedException
     */
    public function delete(string $articleId);

    /**
     * @throws ArticleUpdateFailedException
     */
    public function update(Article $article);
}