<?php
declare(strict_types=1);

namespace App\Services\Articles;

use App\Models\Article;
use App\Repositories\Articles\ArticlesRepositoryInterface;
use App\Repositories\Exception\DatabaseInsertionFailedException;
use App\Services\Articles\Exceptions\ArticleCreationFailedException;

class StoreArticlesService
{
    private ArticlesRepositoryInterface $articlesRepository;

    public function __construct(ArticlesRepositoryInterface $articlesRepository)
    {
        $this->articlesRepository = $articlesRepository;
    }

    public function execute(Article $article): void
    {
        try {
            $this->articlesRepository->insert($article);
        } catch (DatabaseInsertionFailedException $e) {
            throw new ArticleCreationFailedException("Failed to create article - " . $e->getMessage());
        }
    }
}