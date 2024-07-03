<?php
declare(strict_types=1);

namespace App\Repositories\Articles;

use App\Models\Article;
use App\Services\Articles\Exceptions\ArticleInsertionFailedException;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;

class SqliteArticleRepository implements ArticleRepositoryInterface
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
                ])
                ->setParameters([
                    "article_id" => $article->id(),
                    "title" => $article->title(),
                    "content" => $article->content(),
                ])
                ->executeQuery();
        } catch (Exception $e) {
            throw new ArticleInsertionFailedException($e->getMessage());
        }
    }

    public function get(string $articleId): ?Article
    {
        $articleData = $this->connection->createQueryBuilder()
            ->select("*")
            ->from("articles")
            ->where("article_id = :article_id")
            ->setParameter("article_id", $articleId)
            ->executeQuery()
            ->fetchAssociative();

        if ($articleData === false) {
            return null;
        }

        // TODO: think about whether this building should happen here or in model (fromArray static?)
        return new Article(
            $articleData["title"],
            $articleData["content"],
            $articleData["article_id"]
        );
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
        foreach($articleData as $article) {
            $articles[] = new Article(
                $article["title"],
                $article["content"],
                $article["article_id"]
            );
        }
        return $articles;
    }
}