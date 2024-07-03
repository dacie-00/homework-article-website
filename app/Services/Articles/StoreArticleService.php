<?php
declare(strict_types=1);

namespace App\Services\Articles;

use App\Models\Article;
use App\Repositories\Articles\ArticleRepositoryInterface;
use App\Repositories\Exception\RepositoryInsertionFailedException;
use App\Services\Articles\Exceptions\ArticleCreationFailedException;

class StoreArticleService
{
    private ArticleRepositoryInterface $articleRepository;

    public function __construct(ArticleRepositoryInterface $articleRepository)
    {
        $this->articleRepository = $articleRepository;
    }

    public function execute(Article $article): void
    {
        try {
            $this->articleRepository->insert($article);
        } catch (RepositoryInsertionFailedException $e) {
            throw new ArticleCreationFailedException("Failed to create article - " . $e->getMessage());
        }
    }
}