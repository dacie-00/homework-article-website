<?php
declare(strict_types=1);

namespace App\Repositories\Articles;

use App\Models\Article;
use App\Repositories\Exceptions\InsertionInRepositoryFailedException;
use App\Repositories\Exceptions\ItemInRepositoryNotFoundException;
use App\Repositories\Exceptions\RetrievalInRepositoryFailedException;
use App\Repositories\Exceptions\UpdateInRepositoryFailedException;
use App\Services\Articles\Exceptions\ArticleDeletionFailedException;
use App\Services\Articles\Exceptions\ArticleNotFoundException;
use Carbon\Carbon;
use DateTimeInterface;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;

class DoctrineDbalArticleRepository implements ArticleRepositoryInterface
{
    private Connection $connection;

    public function __construct(
        Connection $connection
    ) {
        $this->connection = $connection;
    }

    public function insert(Article $article): void
    {
        try {
            $this->connection->createQueryBuilder()
                ->insert("articles")
                ->values([
                    "article_id" => ":article_id",
                    "title" => ":title",
                    "content" => ":content",
                    "likes" => ":likes",
                    "created_at" => ":created_at",
                    "updated_at" => ":updated_at",
                ])
                ->setParameters([
                    "article_id" => $article->id(),
                    "title" => $article->title(),
                    "likes" => $article->likes(),
                    "content" => $article->content(),
                    "created_at" => $article->createdAt()->timezone("UTC")->format(DateTimeInterface::ATOM),
                    "updated_at" => $article->updatedAt()->timezone("UTC")->format(DateTimeInterface::ATOM),
                ])
                ->executeQuery();
        } catch (Exception $e) {
            throw new InsertionInRepositoryFailedException(
                "Failed to insert article ({$article->id()})",
                0,
                $e
            );
        }
    }

    public function get(string $articleId): Article
    {
        try {
            $articleData = $this->connection->createQueryBuilder()
                ->select("*")
                ->from("articles")
                ->where("article_id = :article_id")
                ->setParameter("article_id", $articleId)
                ->executeQuery()
                ->fetchAssociative();
        } catch (Exception $e) {
            throw new RetrievalInRepositoryFailedException(
                "Failed to retrieve article with id ($articleId)",
                0,
                $e
            );
        }

        if ($articleData === false) {
            throw new ItemInRepositoryNotFoundException(
                "Failed to find article with id ($articleId)"
            );
        }

        return new Article(
            $articleData["title"],
            $articleData["content"],
            $articleData["article_id"],
            (int)$articleData["likes"],
            Carbon::parse($articleData["created_at"]),
            Carbon::parse($articleData["updated_at"]),
        );
    }

    /**
     * @inheritDoc
     */
    public function getAll(): array
    {
        try {
            $articlesData = $this->connection->createQueryBuilder()
                ->select("*")
                ->from("articles")
                ->executeQuery()
                ->fetchAllAssociative();
        } catch (Exception $e) {
            throw new RetrievalInRepositoryFailedException(
                "Failed to retrieve articles",
                0,
                $e
            );
        }

        $articles = [];
        foreach ($articlesData as $articleData) {
            $articles[] = new Article(
                $articleData["title"],
                $articleData["content"],
                $articleData["article_id"],
                (int)$articleData["likes"],
                Carbon::parse($articleData["created_at"]),
                Carbon::parse($articleData["updated_at"]),
            );
        }
        return $articles;
    }

    /**
     * @inheritDoc
     */
    public function delete(string $articleId): void
    {
        try {
            $result = $this->connection->createQueryBuilder()
                ->delete("articles")
                ->where("article_id = :article_id")
                ->setParameter("article_id", $articleId)
                ->executeQuery()
                ->fetchOne();
        } catch (Exception $e) {
            throw new ArticleDeletionFailedException(
                "Failed to delete article with id ($articleId)",
                0,
                $e
            );
        }
        if ($result !== false) {
            throw new ArticleNotFoundException(
                "Failed to find article with id ($articleId)"
            );
        }
    }

    public function update(Article $article): void
    {
        try {
            $this->connection->createQueryBuilder()
                ->update("articles")
                ->where("article_id = :article_id")
                ->set("title", ":title")
                ->set("content", ":content")
                ->set("updated_at", ":updated_at")
                ->setParameters([
                    "article_id" => $article->id(),
                    "title" => $article->title(),
                    "content" => $article->content(),
                    "updated_at" => $article->updatedAt()->timezone("UTC")->format(DateTimeInterface::ATOM),
                ])
                ->executeQuery();
        } catch (Exception $e) {
            throw new UpdateInRepositoryFailedException(
                "Failed to update article with id ({$article->id()})",
                0,
                $e
            );
        }
    }

    /**
     * @inheritDoc
     */
    public function like(string $articleId): void
    {
        try {
            $result = $this->connection->createQueryBuilder()
                ->update("articles")
                ->where("article_id = :article_id")
                ->set("likes", "likes + 1")
                ->set("updated_at", ":updated_at")
                ->setParameters([
                    "article_id" => $articleId,
                    "updated_at" => Carbon::now("UTC")->format(DateTimeInterface::ATOM),
                ])
                ->executeQuery()
                ->rowCount();
        } catch (Exception $e) {
            throw new UpdateInRepositoryFailedException(
                "Failed to update article with id ({$articleId})",
                0,
                $e
            );
        }
        if ($result === 0) {
            throw new ArticleNotFoundException(
                "Failed to find article with id ($articleId)"
            );
        }
    }
}