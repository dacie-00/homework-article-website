<?php
declare(strict_types=1);

namespace App\Repositories\Comments;

use App\Models\Comment;
use App\Repositories\Exceptions\DeletionInRepositoryFailedException;
use App\Repositories\Exceptions\InsertionInRepositoryFailedException;
use App\Repositories\Exceptions\ItemInRepositoryNotFoundException;
use App\Repositories\Exceptions\RetrievalInRepositoryFailedException;
use App\Repositories\Exceptions\UpdateInRepositoryFailedException;
use Carbon\Carbon;
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
            throw new InsertionInRepositoryFailedException(
                "Failed to insert comment with id ({$comment->id()})",
                0,
                $e
            );
        }
    }

    /**
     * @inheritDoc
     */
    public function get(string $commentId): Comment
    {
        try {
            $commentData = $this->connection->createQueryBuilder()
                ->select("*")
                ->from("comments")
                ->where("comment_id = :comment_id")
                ->setParameter("comment_id", $commentId)
                ->executeQuery()
                ->fetchAssociative();
        } catch (Exception $e) {
            throw new RetrievalInRepositoryFailedException(
                "Failed to retrieve comment with id ($commentId)",
            );
        }

        if ($commentData === false) {
            throw new ItemInRepositoryNotFoundException(
                "Failed to find comment with id ($commentId)"
            );
        }

        return new Comment(
            $commentData["content"],
            $commentData["user_id"],
            $commentData["article_id"],
            $commentData["comment_id"],
            (int)$commentData["likes"],
            Carbon::parse($commentData["created_at"]),
            Carbon::parse($commentData["updated_at"]),
        );
    }

    /**
     * @inheritDoc
     */
    public function getForArticle(string $articleId): array
    {
        try {
            $commentsData = $this->connection->createQueryBuilder()
                ->select("*")
                ->from("comments")
                ->where("article_id = :article_id")
                ->setParameter("article_id", $articleId)
                ->executeQuery()
                ->fetchAllAssociative();
        } catch (Exception $e) {
            throw new RetrievalInRepositoryFailedException(
                "Failed to retrieve comments for article with id ($articleId)",
                0,
                $e
            );
        }

        $comments = [];
        foreach ($commentsData as $comment) {
            $comments[] = new Comment(
                $comment["content"],
                $comment["user_id"],
                $comment["article_id"],
                $comment["comment_id"],
                (int)$comment["likes"],
                Carbon::parse($comment["created_at"]),
                Carbon::parse($comment["updated_at"]),
            );
        }
        return $comments;
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
            throw new DeletionInRepositoryFailedException(
                "Failed to delete comment with id ($commentId)",
                0,
                $e
            );
        }
        if ($result !== false) {
            throw new ItemInRepositoryNotFoundException(
                "Failed to find comment with id ($commentId)"
            );
        }
    }

    /**
     * @inheritDoc
     */
    public function like(string $commentId): void
    {
        try {
            $result = $this->connection->createQueryBuilder()
                ->update("comments")
                ->where("comment_id = :comment_id")
                ->set("likes", "likes + 1")
                ->set("updated_at", ":updated_at")
                ->setParameters([
                    "comment_id" => $commentId,
                    "updated_at" => Carbon::now("UTC")->format(DateTimeInterface::ATOM),
                ])
                ->executeQuery()
                ->rowCount();
        } catch (Exception $e) {
            throw new UpdateInRepositoryFailedException(
                "Failed to updated comment with id ($commentId)",
                0,
                $e
            );
        }
        if ($result === 0) {
            throw new ItemInRepositoryNotFoundException(
                "Failed to find comment with id ($commentId)"
            );
        }
    }
}