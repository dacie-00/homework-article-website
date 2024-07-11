<?php
declare(strict_types=1);

namespace App\Controllers\Comments;

use App\FlashMessage;
use App\Message;
use App\Responses\RedirectResponse;
use App\Services\Comments\DestroyCommentService;
use App\Services\Comments\Exceptions\CommentDeletionFailedException;
use App\Services\Comments\Exceptions\CommentNotFoundException;
use App\Services\Comments\Exceptions\CommentRetrievalFailedException;
use App\Services\Comments\GetCommentService;
use Psr\Log\LoggerInterface;

class DestroyCommentController
{
    private GetCommentService $getCommentService;
    private DestroyCommentService $destroyCommentService;
    private FlashMessage $flashMessage;
    private LoggerInterface $logger;

    public function __construct(
        GetCommentService $getCommentService,
        DestroyCommentService $destroyCommentService,
        FlashMessage $flashMessage,
        LoggerInterface $logger
    ) {
        $this->getCommentService = $getCommentService;
        $this->destroyCommentService = $destroyCommentService;
        $this->flashMessage = $flashMessage;
        $this->logger = $logger;
    }

    public function __invoke(string $commentId): RedirectResponse
    {
        try {
            $comment = $this->getCommentService->execute($commentId);
            $this->destroyCommentService->execute($commentId);
        } catch (CommentNotFoundException|CommentRetrievalFailedException|CommentDeletionFailedException $e) {
            $this->logger->error($e);
            $this->flashMessage->set(new Message(
                Message::TYPE_ERROR,
                "Oops! Something went wrong!",
            ));
            return new RedirectResponse("/articles/");
        }

        $this->flashMessage->set(new Message(
            Message::TYPE_SUCCESS,
            "Comment has been successfully deleted!",
        ));

        return new RedirectResponse("/articles/{$comment->articleId()}");
    }

}