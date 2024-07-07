<?php
declare(strict_types=1);

namespace App\Controllers\Comments;

use App\Repositories\Comments\Exceptions\CommentFetchFailedException;
use App\Repositories\Comments\Exceptions\CommentNotFoundException;
use App\Responses\RedirectResponse;
use App\Services\Comments\GetCommentService;
use App\Services\Comments\LikeCommentService;
use Psr\Log\LoggerInterface;

class LikeCommentController
{
    private LikeCommentService $likeCommentService;
    private GetCommentService $getCommentService;
    private LoggerInterface $logger;

    public function __construct(
        LikeCommentService $likeCommentService,
        GetCommentService $getCommentService,
        LoggerInterface $logger
    ) {
        $this->likeCommentService = $likeCommentService;
        $this->getCommentService = $getCommentService;
        $this->logger = $logger;
    }

    public function __invoke($commentId): RedirectResponse
    {
        $this->likeCommentService->execute($commentId);
        try {
            $comment = $this->getCommentService->execute($commentId);
        } catch (CommentFetchFailedException|CommentNotFoundException $e) {
            $this->logger->error("Failed to find comment $commentId - $e");
            return new RedirectResponse("/articles");
        }
        return new RedirectResponse("/articles/{$comment->articleId()}#comment-$commentId");
    }
}