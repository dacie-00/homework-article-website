<?php
declare(strict_types=1);

namespace App\Controllers\Comments;

use App\FlashMessage;
use App\Message;
use App\Models\Comment;
use App\Responses\RedirectResponse;
use App\Services\Comments\Exceptions\CommentNotFoundException;
use App\Services\Comments\Exceptions\CommentRetrievalFailedException;
use App\Services\Comments\GetCommentService;
use App\Services\Likes\Exceptions\ItemLikeFailedException;
use App\Services\Likes\LikeItemService;
use Psr\Log\LoggerInterface;

class LikeCommentController
{
    private LikeItemService $likeItemService;
    private GetCommentService $getCommentService;
    private LoggerInterface $logger;
    private FlashMessage $flashMessage;

    public function __construct(
        LikeItemService $likeItemService,
        GetCommentService $getCommentService,
        LoggerInterface $logger,
        FlashMessage $flashMessage
    ) {
        $this->likeItemService = $likeItemService;
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
            $this->likeItemService->execute(Comment::class, $commentId);
        } catch (ItemLikeFailedException $e) {
            $this->logger->error($e);
        }
        return new RedirectResponse("/articles/{$comment->articleId()}#comment-$commentId");
    }
}