<?php
declare(strict_types=1);

namespace App\Services\Articles;

use App\Repositories\Articles\ArticleRepositoryInterface;

class DeleteArticleService
{
    private ArticleRepositoryInterface $articleRepository;

    public function __construct(ArticleRepositoryInterface $articleRepository)
    {
        $this->articleRepository = $articleRepository;
    }

    public function execute(string $articleId): void
    {
        $this->articleRepository->delete($articleId);
    }
}