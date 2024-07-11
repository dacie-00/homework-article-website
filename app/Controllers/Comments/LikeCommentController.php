<?php
declare(strict_types=1);

namespace App\Controllers\Comments;

use App\FlashMessage;
use App\Message;
use App\Responses\RedirectResponse;
use App\Services\Comments\Exceptions\CommentNotFoundException;
use App\Services\Comments\Exceptions\CommentRetrievalFailedException;
use App\Services\Comments\Exceptions\CommentUpdateFailedException;
use App\Services\Comments\GetCommentService;
use App\Services\Comments\LikeCommentService;
use Psr\Log\LoggerInterface;

class LikeCommentController
{
    private LikeCommentService $likeCommentService;
    private GetCommentService $getCommentService;
    private LoggerInterface $logger;
    private FlashMessage $flashMessage;

    public function __construct(
        LikeCommentService $likeCommentService,
        GetCommentService $getCommentService,
        LoggerInterface $logger,
        FlashMessage $flashMessage
    ) {
        $this->likeCommentService = $likeCommentService;
        $this->getCommentService = $getCommentService;
        $this->logger = $logger;
        $this->flashMessage = $flashMessage;
    }

    public function __invoke($commentId): RedirectResponse
    {
        try {
            $comment = $this->getCommentService->execute($commentId);
        } catch (CommentNotFoundException|CommentRetrievalFailedException $e) {
            $this->logger->error($e);
            $this->flashMessage->set(new Message(
                Message::TYPE_ERROR,
                "Oops! Something went wrong!",
            ));
            return new RedirectResponse("/articles/");
        }
        try {
            $this->likeCommentService->execute($commentId);
        } catch (CommentUpdateFailedException $e) {
            $this->logger->error($e);
        }
        return new RedirectResponse("/articles/{$comment->articleId()}#comment-$commentId");
    }
}