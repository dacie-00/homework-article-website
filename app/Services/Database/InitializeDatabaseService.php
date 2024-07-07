<?php
declare(strict_types=1);

namespace App\Services\Database;

use App\Services\Database\Exceptions\DatabaseInitializationFailedException;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;
use Doctrine\DBAL\Schema\AbstractSchemaManager;
use Doctrine\DBAL\Schema\SchemaException;
use Doctrine\DBAL\Schema\Table;
use Psr\Log\LoggerInterface;

class InitializeDatabaseService
{
    private AbstractSchemaManager $schemaManager;
    private LoggerInterface $logger;

    public function __construct(
        Connection $connection,
        LoggerInterface $logger
    ) {
        try {
            $this->schemaManager = $connection->createSchemaManager();
        } catch (Exception $e) {
            throw new DatabaseInitializationFailedException("Failed to initialize database - $e");
        }
        $this->logger = $logger;
    }

    public function execute(): void
    {
        try {
            if (!$this->schemaManager->tablesExist(["articles"])) {
                $table = new Table("articles");
                $table->addColumn("article_id", "string");
                $table->setPrimaryKey(["article_id"]);

                $table->addColumn("title", "string");
                $table->addColumn("content", "text");
                $table->addColumn("likes", "integer");
                $table->addColumn("created_at", "string");
                $table->addColumn("updated_at", "string");
                $this->schemaManager->createTable($table);
            }
            if (!$this->schemaManager->tablesExist(["comments"])) {
                $table = new Table("comments");
                $table->addColumn("comment_id", "string");
                $table->setPrimaryKey(["comment_id"]);
                $table->addColumn("user_id", "string");
                $table->addColumn("article_id", "string");
                $table->addColumn("content", "text");
                $table->addColumn("likes", "integer");
                $table->addColumn("created_at", "string");
                $table->addColumn("updated_at", "string");
                $this->schemaManager->createTable($table);
            }
        } catch (SchemaException $e) {
        } catch (Exception $e) {
            throw new DatabaseInitializationFailedException("Failed to initialize database - $e");
        }
    }

}