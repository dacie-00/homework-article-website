<?php
declare(strict_types=1);

namespace App\Services\Articles;

use App\Repositories\Articles\ArticleRepositoryInterface;
use App\Repositories\Exceptions\DeletionInRepositoryFailedException;
use App\Repositories\Likes\LikeRepositoryInterface;
use App\Services\Articles\Exceptions\ArticleDeletionFailedException;
use App\Services\Comments\ArticleCommentService;
use App\Services\Comments\DestroyCommentService;
use App\Services\Comments\Exceptions\CommentDeletionFailedException;
use App\Services\Comments\Exceptions\CommentRetrievalFailedException;

class DestroyArticleService
{
    private ArticleRepositoryInterface $articleRepository;
    private LikeRepositoryInterface $likeRepository;
    private ArticleCommentService $articleCommentService;
    private DestroyCommentService $destroyCommentService;

    public function __construct(
        ArticleRepositoryInterface $articleRepository,
        LikeRepositoryInterface $likeRepository,
        ArticleCommentService $ArticleCommentService,
        DestroyCommentService $destroyCommentService
    ) {
        $this->articleRepository = $articleRepository;
        $this->likeRepository = $likeRepository;
        $this->articleCommentService = $ArticleCommentService;
        $this->destroyCommentService = $destroyCommentService;
    }

    /**
     * @throws ArticleDeletionFailedException
     */
    public function execute(string $articleId): void
    {
        try {
            $this->articleRepository->delete($articleId);
            $this->likeRepository->deleteForItem($articleId);
            foreacH($this->articleCommentService->execute($articleId) as $comment){
                $this->destroyCommentService->execute($comment->id());
            }
        } catch (DeletionInRepositoryFailedException|CommentRetrievalFailedException|CommentDeletionFailedException $e) {
            throw new ArticleDeletionFailedException(
                "Failed to destroy article with id ($articleId)",
                0,
                $e
            );
        }
    }
}