<?php
declare(strict_types=1);

namespace App\Repositories\Articles;

use App\Models\Article;
use App\Repositories\Articles\Exceptions\ArticleDeletionFailedException;
use App\Repositories\Articles\Exceptions\ArticleFetchFailedException;
use App\Repositories\Articles\Exceptions\ArticleInsertionFailedException;
use App\Repositories\Articles\Exceptions\ArticleNotFoundException;
use App\Repositories\Articles\Exceptions\ArticleUpdateFailedException;
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
            throw new ArticleInsertionFailedException($e->getMessage());
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
            throw new ArticleFetchFailedException($e->getMessage());
        }

        if ($articleData === false) {
            throw new ArticleNotFoundException(
                "Article with id $articleId not found"
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
        $articlesData = $this->connection->createQueryBuilder()
            ->select("*")
            ->from("articles")
            ->executeQuery()
            ->fetchAllAssociative();

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
                "Article $articleId deletion failed - {$e->getMessage()}"
            );
        }
        if ($result !== false) { // TODO: check if this works
            throw new ArticleNotFoundException(
                "Can't delete article $articleId as it does not exist"
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
            throw new ArticleUpdateFailedException(
                "Article {$article->id()} update failed - {$e->getMessage()}"
            );
        }
    }

    /**
     * @inheritDoc
     */
    public function like(string $articleId)
    {
        try {
            $result = $this->connection->createQueryBuilder()
                ->update("articles")
                ->where("article_id = :article_id")
                ->set("likes", "likes + 1")
                ->setParameters([
                    "article_id" => $articleId,
                ])
                ->executeQuery()
                ->rowCount();
        } catch (Exception $e) {
            throw new ArticleUpdateFailedException(
                "Article $articleId like increment failed - {$e->getMessage()}"
            );
        }
        if ($result === 0) {
            throw new ArticleNotFoundException("Article {$articleId} doesn't exist");
        }
    }
}