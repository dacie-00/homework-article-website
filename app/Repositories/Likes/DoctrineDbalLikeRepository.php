<?php
declare(strict_types=1);

namespace App\Repositories\Likes;

use App\Models\Like;
use App\Repositories\Exceptions\InsertionInRepositoryFailedException;
use App\Repositories\Exceptions\RetrievalInRepositoryFailedException;
use Carbon\Carbon;
use DateTimeInterface;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;

class DoctrineDbalLikeRepository implements LikeRepositoryInterface
{
    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @inheritDoc
     */
    public function insert(Like $like): void
    {
        try {
            $this->connection->beginTransaction();
            $this->connection->createQueryBuilder()
                ->insert('likes')
                ->values([
                    "like_id" => ":like_id",
                    "target_type" => ":target_type",
                    "target_id" => ":target_id",
                    "created_at" => ":created_at",
                ])
                ->setParameters([
                    "like_id" => $like->id(),
                    "target_type" => $like->targetClass(),
                    "target_id" => $like->targetId(),
                    "created_at" => $like->createdAt()->format(DateTimeInterface::ATOM),
                ])
                ->executeQuery();
            $this->connection->commit();
        } catch (Exception $e) {
            throw new InsertionInRepositoryFailedException(
                "Failed to insert like with id ({$like->id()})",
                0,
                $e
            );
        }
    }

    /**
     * @inheritDoc
     */
    public function get(string $targetClass, string $targetId): array
    {
        try {
            $likesData = $this->connection->createQueryBuilder()
                ->from("likes")
                ->select("*")
                ->where("target_type = :target_type")
                ->andWhere("target_id = :target_id")
                ->setParameters([
                    "target_type" => $targetClass,
                    "target_id" => $targetId,
                ])
                ->executeQuery()
                ->fetchAllAssociative();
        } catch (Exception $e) {
            throw new RetrievalInRepositoryFailedException(
                "Failed to retrieve likes for target class ($targetClass) with id ($targetId)",
                0,
                $e
            );
        }
        $likes = [];
        foreach($likesData as $likeData) {
            $likes[] = new Like(
                $likeData["target_type"],
                $likeData["target_id"],
                $likeData["like_id"],
                Carbon::parse($likeData["created_at"])
            );
        }
        return $likes;
    }

    /**
     * @inheritDoc
     */
    public function getCount(string $targetClass, string $targetId): int
    {
        try {
            $result = $this->connection->createQueryBuilder()
                ->from("likes")
                ->select("like_id")
                ->where("target_type = :target_type")
                ->andWhere("target_id = :target_id")
                ->setParameters([
                    "target_type" => $targetClass,
                    "target_id" => $targetId,
                ])
                ->executeQuery()
                ->fetchAllAssociative();
            return count($result);
        } catch (Exception $e) {
            throw new RetrievalInRepositoryFailedException(
                "Failed to count likes for target class ($targetClass) with id ($targetId)",
                0,
                $e
            );
        }
    }
}