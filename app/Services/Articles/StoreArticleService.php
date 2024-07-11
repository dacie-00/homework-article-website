<?php
declare(strict_types=1);

namespace App\Services\Articles;

use App\Models\Article;
use App\Repositories\Articles\ArticleRepositoryInterface;
use App\Repositories\Exceptions\InsertionInRepositoryFailedException;
use App\Services\Articles\Exceptions\ArticleStoringFailedException;

class StoreArticleService
{
    private ArticleRepositoryInterface $articleRepository;

    public function __construct(ArticleRepositoryInterface $articleRepository)
    {
        $this->articleRepository = $articleRepository;
    }

    /**
     * @throws ArticleStoringFailedException
     */
    public function execute(Article $article): void
    {
        try {
            $this->articleRepository->insert($article);
        } catch (InsertionInRepositoryFailedException $e) {
            throw new ArticleStoringFailedException(
                "Failed to store article with id ({$article->id()})",
            );
        }
    }
}