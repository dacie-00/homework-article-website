<?php
declare(strict_types=1);

namespace App\Services\Articles;

use App\Models\Article;
use App\Repositories\Articles\ArticleRepositoryInterface;
use App\Repositories\Articles\Exceptions\ArticleFetchFailedException;
use App\Repositories\Articles\Exceptions\ArticleNotFoundException;

class GetArticleService
{
    private ArticleRepositoryInterface $articleRepository;

    public function __construct(ArticleRepositoryInterface $articleRepository)
    {
        $this->articleRepository = $articleRepository;
    }

    /**
     * @throws ArticleFetchFailedException
     * @throws ArticleNotFoundException
     */
    public function execute(string $id): Article
    {
        return $this->articleRepository->get($id);
    }
}