<?php
declare(strict_types=1);

namespace App\Repositories\Articles;

use App\Models\Article;
use App\Repositories\Articles\Exceptions\ArticleDeletionFailedException;
use App\Repositories\Articles\Exceptions\ArticleFetchFailedException;
use App\Repositories\Articles\Exceptions\ArticleInsertionFailedException;
use App\Repositories\Articles\Exceptions\ArticleNotFoundException;
use App\Repositories\Articles\Exceptions\ArticleUpdateFailedException;

interface ArticleRepositoryInterface
{
    /**
     * @throws ArticleInsertionFailedException
     */
    public function insert(Article $article): void;

    /**
     * @throws ArticleFetchFailedException
     * @throws ArticleNotFoundException
     */
    public function get(string $articleId): Article;

    /**
     * @return Article[]
     */
    public function getAll(): array;

    /**
     * @throws ArticleDeletionFailedException
     */
    public function delete(string $articleId): void;

    /**
     * @throws ArticleUpdateFailedException
     */
    public function update(Article $article): void;

    /**
     * @throws ArticleUpdateFailedException
     * @throws ArticleNotFoundException
     */
    public function like(string $articleId): void;
}