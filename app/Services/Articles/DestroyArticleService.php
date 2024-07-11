<?php
declare(strict_types=1);

namespace App\Services\Articles;

use App\Repositories\Articles\ArticleRepositoryInterface;
use App\Repositories\Exceptions\DeletionInRepositoryFailedException;
use App\Services\Articles\Exceptions\ArticleDeletionFailedException;

class DestroyArticleService
{
    private ArticleRepositoryInterface $articleRepository;

    public function __construct(ArticleRepositoryInterface $articleRepository)
    {
        $this->articleRepository = $articleRepository;
    }

    /**
     * @throws ArticleDeletionFailedException
     */
    public function execute(string $articleId): void
    {
        try {
            $this->articleRepository->delete($articleId);
        } catch (DeletionInRepositoryFailedException $e) {
            throw new ArticleDeletionFailedException(
                "Failed to destroy article with id ($articleId)",
                0,
                $e
            );
        }
    }
}