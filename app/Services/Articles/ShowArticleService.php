<?php
declare(strict_types=1);

namespace App\Services\Articles;

use App\Models\Article;
use App\Repositories\Articles\ArticleRepositoryInterface;
use App\Services\Articles\Exceptions\ArticleNotFoundException;

class ShowArticleService
{
    private ArticleRepositoryInterface $articlesRepository;

    public function __construct(ArticleRepositoryInterface $articlesRepository)
    {
        $this->articlesRepository = $articlesRepository;
    }

    public function execute(string $id): Article
    {
        $article = $this->articlesRepository->get($id);
        if (is_null($article)) {
            throw new ArticleNotFoundException("Article with id {$id} not found");
        }
        return $article;
    }
}