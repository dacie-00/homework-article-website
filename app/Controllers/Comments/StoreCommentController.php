<?php
declare(strict_types=1);

namespace App\Controllers\Comments;

use App\FlashMessage;
use App\Message;
use App\Models\Comment;
use App\Repositories\Comments\Exceptions\CommentInsertionFailedException;
use App\Responses\RedirectResponse;
use App\Services\Articles\StoreArticleService;
use App\Services\Comments\StoreCommentService;
use Psr\Log\LoggerInterface;

class StoreCommentController
{
    private StoreCommentService $storeCommentService;
    private FlashMessage $flashMessage;
    private LoggerInterface $logger;

    public function __construct(
        StoreCommentService $storeCommentService,
        FlashMessage $flashMessage,
        LoggerInterface $logger
    ) {
        $this->storeCommentService = $storeCommentService;
        $this->flashMessage = $flashMessage;
        $this->logger = $logger;
    }

    public function __invoke(): RedirectResponse
    {
        $user = $_POST["user"];
        $content = $_POST["content"];
        $articleId = $_POST["article-id"];

        $comment = new Comment($content, $user, $articleId);

        try {
            $this->storeCommentService->execute($comment);
        } catch (CommentInsertionFailedException $e) {
            $this->logger->error("Failed to create comment '{$comment->id()}' - {$e->getMessage()}");
            $this->flashMessage->set(new Message(
                Message::TYPE_ERROR,
                "Internal Error - failed to create comment '{$comment->id()}'",
            ));
            return new RedirectResponse("/articles/$articleId#comment-{$comment->id()}");
        }
        $this->flashMessage->set(new Message(
            Message::TYPE_SUCCESS,
            "Article '{$comment->id()}' has been successfully created",
            ["articleId" => $comment->id()]
        ));
        return new RedirectResponse("/articles/$articleId#comment-{$comment->id()}");
    }
}