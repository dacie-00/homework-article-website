<?php
declare(strict_types=1);

namespace App\Repositories\Articles;

use App\Models\Article;
use App\Repositories\Articles\Exceptions\ArticleDeletionFailedException;
use App\Repositories\Articles\Exceptions\ArticleFetchFailedException;
use App\Repositories\Articles\Exceptions\ArticleInsertionFailedException;
use App\Repositories\Articles\Exceptions\ArticleNotFoundException;
use App\Repositories\Articles\Exceptions\ArticleUpdateFailedException;
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
                    "created_at" => ":created_at",
                    "updated_at" => ":updated_at",
                ])
                ->setParameters([
                    "article_id" => $article->id(),
                    "title" => $article->title(),
                    "content" => $article->content(),
                    "created_at" => $article->createdAt()->timezone("UTC")->format(DateTimeInterface::ATOM),
                    "updated_at" => $article->updatedAt()->timezone("UTC")->format(DateTimeInterface::ATOM),
                ])
                ->executeQuery();
        } catch (Exception $e) {
            throw new ArticleInsertionFailedException($e->getMessage());
        }
    }

    public function get(string $articleId): ?Article
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

        return Article::fromArray($articleData);
    }

    /**
     * @inheritDoc
     */
    public function getAll(): array
    {
        $articleData = $this->connection->createQueryBuilder()
            ->select("*")
            ->from("articles")
            ->executeQuery()
            ->fetchAllAssociative();

        $articles = [];
        foreach ($articleData as $article) {
            $articles[] = Article::fromArray($article);
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
}