<?php
declare(strict_types=1);

namespace App\Controllers\Comments;

use App\FlashMessage;
use App\Message;
use App\Models\Comment;
use App\Responses\RedirectResponse;
use App\Services\Comments\CommentValidationService;
use App\Services\Comments\Exceptions\CommentStoringFailedException;
use App\Services\Comments\Exceptions\InvalidCommentContentException;
use App\Services\Comments\Exceptions\InvalidCommentUsernameException;
use App\Services\Comments\StoreCommentService;
use Psr\Log\LoggerInterface;

class StoreCommentController
{
    private StoreCommentService $storeCommentService;
    private CommentValidationService $commentValidationService;
    private FlashMessage $flashMessage;
    private LoggerInterface $logger;

    public function __construct(
        StoreCommentService $storeCommentService,
        CommentValidationService $commentValidationService,
        FlashMessage $flashMessage,
        LoggerInterface $logger
    ) {
        $this->storeCommentService = $storeCommentService;
        $this->commentValidationService = $commentValidationService;
        $this->flashMessage = $flashMessage;
        $this->logger = $logger;
    }

    public function __invoke(): RedirectResponse
    {
        $user = $_POST["user"];
        $content = $_POST["content"];
        $articleId = $_POST["article-id"];
        try { // TODO: refactor with validation package
            $this->commentValidationService->execute($user, $content);
        } catch (InvalidCommentUsernameException|InvalidCommentContentException $e) {
            $this->flashMessage->set(new Message(
                Message::TYPE_ERROR,
                $e->getMessage(),
                [
                    "username" => "$user",
                    "content" => "$content",
                ]
            ));
            return new RedirectResponse("/articles/$articleId#comment-form");
        }

        $comment = new Comment($content, $user, $articleId);

        try {
            $this->storeCommentService->execute($comment);
        } catch (CommentStoringFailedException $e) {
            $this->logger->error($e);
            $this->flashMessage->set(new Message(
                Message::TYPE_ERROR,
                "Oops! something went wrong!",
            ));
        }
        return new RedirectResponse("/articles/$articleId#comment-{$comment->id()}");
    }
}