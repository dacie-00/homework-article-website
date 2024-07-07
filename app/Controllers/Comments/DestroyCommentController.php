<?php
declare(strict_types=1);

namespace App\Controllers\Comments;

use App\FlashMessage;
use App\Message;
use App\Repositories\Comments\Exceptions\CommentDeletionFailedException;
use App\Repositories\Comments\Exceptions\CommentFetchFailedException;
use App\Repositories\Comments\Exceptions\CommentNotFoundException;
use App\Responses\RedirectResponse;
use App\Services\Comments\DestroyCommentService;
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
        } catch (CommentNotFoundException $e) {
            $this->logger->error("Attempt to delete comment that doesn't exist - {$e->getMessage()}");
            $this->flashMessage->set(new Message(
                Message::TYPE_ERROR,
                "Failed to find and delete comment!",
            ));
            return new RedirectResponse("/articles");
        } catch (CommentFetchFailedException $e) {
            $this->logger->error("Failed to fetch comment - {$e->getMessage()}");
            $this->flashMessage->set(new Message(
                Message::TYPE_ERROR,
                "Failed to delete comment!",
            ));
            return new RedirectResponse("/articles");
        }
        try {
            $this->destroyCommentService->execute($commentId);
        } catch (CommentDeletionFailedException $e) {
            $this->logger->error("Failed to delete comment - {$e->getMessage()}");
            $this->flashMessage->set(new Message(
                Message::TYPE_ERROR,
                "Failed to delete comment!",
            ));
            return new RedirectResponse("/articles");
        }

        $this->flashMessage->set(new Message(
            Message::TYPE_SUCCESS,
            "Comment has been successfully deleted!",
        ));

        return new RedirectResponse("/articles");
    }

}