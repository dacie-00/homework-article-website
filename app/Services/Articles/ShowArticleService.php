<?php
declare(strict_types=1);

namespace App\Services\Articles;

use App\Models\Article;
use App\Repositories\Articles\ArticleRepositoryInterface;
use App\Services\Articles\Exceptions\ArticleNotFoundException;

class ShowArticleService
{
    private ArticleRepositoryInterface $articleRepository;

    public function __construct(ArticleRepositoryInterface $articleRepository)
    {
        $this->articleRepository = $articleRepository;
    }

    public function execute(string $id): Article
    {
        $article = $this->articleRepository->get($id);
        if (is_null($article)) {
            throw new ArticleNotFoundException("Article with id {$id} not found");
        }
        return $article;
    }
}