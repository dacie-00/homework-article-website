<?php
declare(strict_types=1);

namespace App\Services\Articles;

use App\Models\Article;
use App\Repositories\Articles\ArticleRepositoryInterface;
use App\Repositories\Articles\Exceptions\ArticleUpdateFailedException;
use Carbon\Carbon;

class UpdateArticleService
{
    private ArticleRepositoryInterface $articleRepository;

    public function __construct(ArticleRepositoryInterface $articleRepository)
    {
        $this->articleRepository = $articleRepository;
    }

    /**
     * @throws ArticleUpdateFailedException
     */
    public function execute(Article $article, array $data): void
    {
        if (isset($data["title"])) {
            $article->setTitle($data["title"]);
        }
        if (isset($data["content"])) {
            $article->setContent($data["content"]);
        }
        $article->setUpdatedAt(Carbon::now("UTC"));

        $this->articleRepository->update($article);
    }
}