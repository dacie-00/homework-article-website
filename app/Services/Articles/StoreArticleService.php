<?php
declare(strict_types=1);

namespace App\Services\Articles;

use App\Models\Article;
use App\Repositories\Articles\ArticleRepositoryInterface;
use App\Repositories\Articles\Exceptions\ArticleInsertionFailedException;
use App\Repositories\Exception\RepositoryInsertionFailedException;

class StoreArticleService
{
    private ArticleRepositoryInterface $articleRepository;

    public function __construct(ArticleRepositoryInterface $articleRepository)
    {
        $this->articleRepository = $articleRepository;
    }

    /**
     * @throws ArticleInsertionFailedException
     */
    public function execute(Article $article): void
    {
        $this->articleRepository->insert($article);
    }
}