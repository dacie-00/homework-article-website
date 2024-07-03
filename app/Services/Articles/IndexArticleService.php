<?php
declare(strict_types=1);

namespace App\Services\Articles;

use App\Models\Article;
use App\Repositories\Articles\ArticleRepositoryInterface;

class IndexArticleService
{

    private ArticleRepositoryInterface $articlesRepository;

    public function __construct(ArticleRepositoryInterface $articlesRepository)
    {
        $this->articlesRepository = $articlesRepository;
    }

    /**
     * @return Article[]
     */
    public function execute(): array
    {
        return $this->articlesRepository->getAll();
    }
}