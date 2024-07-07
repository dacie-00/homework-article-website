<?php
declare(strict_types=1);

namespace App\Services\Articles;

use App\Repositories\Articles\ArticleRepositoryInterface;
use App\Repositories\Articles\Exceptions\ArticleDeletionFailedException;

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
        $this->articleRepository->delete($articleId);
    }
}