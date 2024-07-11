<?php
declare(strict_types=1);

namespace App\Services\Articles;

use App\Models\Article;
use App\Repositories\Articles\ArticleRepositoryInterface;
use App\Repositories\Exceptions\UpdateInRepositoryFailedException;
use App\Services\Articles\Exceptions\ArticleUpdateFailedException;
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
    public function execute(Article $article, array $data): void // TODO: make this less bad
    {
        if (isset($data["title"])) {
            $article->setTitle($data["title"]);
        }
        if (isset($data["content"])) {
            $article->setContent($data["content"]);
        }
        $article->setUpdatedAt(Carbon::now("UTC"));

        try {
            $this->articleRepository->update($article);
        } catch (UpdateInRepositoryFailedException $e) {
            throw new ArticleUpdateFailedException(
                "Failed to update article with id ({$article->id()})",
                0,
                $e
            );
        }
    }
}