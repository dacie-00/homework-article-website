<?php
declare(strict_types=1);

namespace App\Services\Articles;

use App\Models\Article;
use App\Repositories\Articles\ArticleRepositoryInterface;
use App\Repositories\Exceptions\ItemInRepositoryNotFoundException;
use App\Repositories\Exceptions\RetrievalInRepositoryFailedException;
use App\Services\Articles\Exceptions\ArticleRetrievalFailedException;
use App\Services\Articles\Exceptions\ArticleNotFoundException;

class ShowArticleService
{
    private ArticleRepositoryInterface $articleRepository;

    public function __construct(ArticleRepositoryInterface $articleRepository)
    {
        $this->articleRepository = $articleRepository;
    }

    /**
     * @throws ArticleRetrievalFailedException
     */
    public function execute(string $articleId): Article
    {
        try {
            return $this->articleRepository->get($articleId);
        } catch (ItemInRepositoryNotFoundException|RetrievalInRepositoryFailedException $e) {
            throw new ArticleRetrievalFailedException(
                "Failed to retrieve article with id ($articleId)",
                0,
                $e
            );
        }
    }
}