<?php
declare(strict_types=1);

namespace App\Repositories\Comments;

use App\Models\Comment;
use App\Repositories\Comments\Exceptions\CommentDeletionFailedException;
use App\Repositories\Comments\Exceptions\CommentFetchFailedException;
use App\Repositories\Comments\Exceptions\CommentInsertionFailedException;
use App\Repositories\Comments\Exceptions\CommentNotFoundException;
use App\Repositories\Comments\Exceptions\CommentUpdateFailedException;
use DateTimeInterface;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;

class DoctrineDbalCommentRepository implements CommentRepositoryInterface
{
    private Connection $connection;

    public function __construct(
        Connection $connection
    ) {
        $this->connection = $connection;
    }

    public function insert(Comment $comment): void
    {
        try {
            $this->connection->createQueryBuilder()
                ->insert("comments")
                ->values([
                    "comment_id" => ":comment_id",
                    "user_id" => ":user_id",
                    "article_id" => ":article_id",
                    "content" => ":content",
                    "likes" => ":likes",
                    "created_at" => ":created_at",
                    "updated_at" => ":updated_at",
                ])
                ->setParameters([
                    "comment_id" => $comment->id(),
                    "user_id" => $comment->userId(),
                    "article_id" => $comment->articleId(),
                    "content" => $comment->content(),
                    "likes" => $comment->likes(),
                    "created_at" => $comment->createdAt()->timezone("UTC")->format(DateTimeInterface::ATOM),
                    "updated_at" => $comment->updatedAt()->timezone("UTC")->format(DateTimeInterface::ATOM),
                ])
                ->executeQuery();
        } catch (Exception $e) {
            throw new CommentInsertionFailedException($e->getMessage());
        }
    }

    public function getForArticle(string $articleId): Comment
    {
        try {
            $commentData = $this->connection->createQueryBuilder()
                ->select("*")
                ->from("comments")
                ->where("article_id = :article_id")
                ->setParameter("article_id", $articleId)
                ->executeQuery()
                ->fetchAssociative();
        } catch (Exception $e) {
            throw new CommentFetchFailedException($e->getMessage());
        }

        return Comment::fromArray($commentData);
    }

    /**
     * @inheritDoc
     */
    public function delete(string $commentId): void
    {
        try {
            $result = $this->connection->createQueryBuilder()
                ->delete("comments")
                ->where("comment_id = :comment_id")
                ->setParameter("comment_id", $commentId)
                ->executeQuery()
                ->fetchOne();
        } catch (Exception $e) {
            throw new CommentDeletionFailedException(
                "Comment $commentId deletion failed - {$e->getMessage()}"
            );
        }
        if ($result !== false) { // TODO: check if this works
            throw new CommentNotFoundException(
                "Can't delete article $commentId as it does not exist"
            );
        }
    }

    /**
     * @inheritDoc
     */
    public function like(string $commentId):void
    {
        try {
            $result = $this->connection->createQueryBuilder()
                ->update("comments")
                ->where("comment_id = :comment_id")
                ->set("likes", "likes + 1")
                ->setParameters([
                    "comment_id" => $commentId,
                ])
                ->executeQuery()
                ->rowCount();
        } catch (Exception $e) {
            throw new CommentUpdateFailedException(
                "Comment $commentId like increment failed - {$e->getMessage()}"
            );
        }
        if ($result === 0) {
            throw new CommentNotFoundException("Comment {$commentId} doesn't exist");
        }
    }
}