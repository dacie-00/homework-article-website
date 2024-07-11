<?php
declare(strict_types=1);

namespace App\Services\Articles;

use App\Repositories\Articles\ArticleRepositoryInterface;
use App\Repositories\Exceptions\ItemInRepositoryNotFoundException;
use App\Repositories\Exceptions\UpdateInRepositoryFailedException;
use App\Services\Articles\Exceptions\ArticleNotFoundException;
use App\Services\Articles\Exceptions\ArticleUpdateFailedException;
use App\Services\Articles\Exceptions\ArticleLikeUpdateFailedException;

class LikeArticleService
{
    private ArticleRepositoryInterface $articleRepository;

    public function __construct(ArticleRepositoryInterface $articleRepository)
    {
        $this->articleRepository = $articleRepository;
    }

    /**
     * @throws ArticleUpdateFailedException
     */
    public function execute(string $articleId): void
    {
        try {
            $this->articleRepository->like($articleId);
        } catch (ItemInRepositoryNotFoundException|UpdateInRepositoryFailedException $e) {
            throw new ArticleUpdateFailedException(
                "Failed to add like to article with id ($articleId)",
                0,
                $e
            );
        }
    }
}