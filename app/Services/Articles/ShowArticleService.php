<?php
declare(strict_types=1);

namespace App\Services\Articles;

use App\Models\Article;
use App\Repositories\Articles\ArticleRepositoryInterface;
use App\Repositories\Exceptions\ItemInRepositoryNotFoundException;
use App\Repositories\Exceptions\RetrievalInRepositoryFailedException;
use App\Repositories\Likes\LikeRepositoryInterface;
use App\Services\Articles\Exceptions\ArticleRetrievalFailedException;
use Psr\Log\LoggerInterface;

class ShowArticleService
{
    private ArticleRepositoryInterface $articleRepository;
    private LikeRepositoryInterface $likeRepository;
    private LoggerInterface $logger;

    public function __construct(
        ArticleRepositoryInterface $articleRepository,
        LikeRepositoryInterface $likeRepository,
        LoggerInterface $logger
    ) {
        $this->articleRepository = $articleRepository;
        $this->likeRepository = $likeRepository;
        $this->logger = $logger;
    }

    /**
     * @throws ArticleRetrievalFailedException
     */
    public function execute(string $articleId): Article
    {
        try {
            $article = $this->articleRepository->get($articleId);
        } catch (ItemInRepositoryNotFoundException|RetrievalInRepositoryFailedException $e) {
            throw new ArticleRetrievalFailedException(
                "Failed to retrieve article with id ($articleId)",
                0,
                $e
            );
        }
        try {
            $likes = $this->likeRepository->getCount(Article::class, $articleId);
        } catch (RetrievalInRepositoryFailedException $e) {
            $this->logger->error($e);
            $likes = 0;
        }
        $article->setLikes($likes);
        return $article;
    }
}