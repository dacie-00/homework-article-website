<?php
declare(strict_types=1);

namespace App\Services\Comments;

use App\Models\Comment;
use App\Repositories\Comments\CommentRepositoryInterface;
use App\Repositories\Exceptions\RetrievalInRepositoryFailedException;
use App\Services\Comments\Exceptions\CommentRetrievalFailedException;

class IndexForArticleCommentService
{
    private CommentRepositoryInterface $commentRepository;

    public function __construct(
        CommentRepositoryInterface $commentRepository
    ) {
        $this->commentRepository = $commentRepository;
    }

    /**
     * @return Comment[]
     * @throws CommentRetrievalFailedException
     */
    public function execute($articleId): array
    {
        try {
            $comments = $this->commentRepository->getForArticle($articleId);
        } catch (RetrievalInRepositoryFailedException $e) {
            throw new CommentRetrievalFailedException(
                "Failed to retrieve comments for article with id ($articleId)",
                0,
                $e
            );
        }
        return $comments;
    }
}