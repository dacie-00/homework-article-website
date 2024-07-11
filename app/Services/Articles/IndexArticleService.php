<?php
declare(strict_types=1);

namespace App\Services\Articles;

use App\Models\Article;
use App\Repositories\Articles\ArticleRepositoryInterface;
use App\Repositories\Exceptions\RetrievalInRepositoryFailedException;
use App\Services\Articles\Exceptions\ArticleRetrievalFailedException;

class IndexArticleService
{

    private ArticleRepositoryInterface $articleRepository;

    public function __construct(ArticleRepositoryInterface $articleRepository)
    {
        $this->articleRepository = $articleRepository;
    }

    /**
     * @return Article[]
     * @throws ArticleRetrievalFailedException
     */
    public function execute(): array
    {
        try {
            return $this->articleRepository->getAll();
        } catch (RetrievalInRepositoryFailedException $e) {
            throw new ArticleRetrievalFailedException(
                "Failed to retrieve articles",
                0,
                $e
            );
        }
    }
}