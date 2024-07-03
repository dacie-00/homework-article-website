<?php
declare(strict_types=1);

namespace App\Repositories\Articles;

use App\Models\Article;
use App\Repositories\Exception\DatabaseInsertionFailedException;
use App\Services\Articles\Exceptions\ArticleInsertionFailedException;
use App\Services\Articles\Exceptions\ArticleNotFoundException;

interface ArticlesRepositoryInterface
{
    /**
     * @throws DatabaseInsertionFailedException
     */
    public function insert(Article $article): void;

    public function get(string $articleId): ?Article;
}