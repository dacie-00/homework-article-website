<?php
declare(strict_types=1);

namespace App\Services\Articles;

use App\Models\Article;
use App\Repositories\Articles\ArticlesRepositoryInterface;

class IndexArticlesService
{

    private ArticlesRepositoryInterface $articlesRepository;

    public function __construct(ArticlesRepositoryInterface $articlesRepository)
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