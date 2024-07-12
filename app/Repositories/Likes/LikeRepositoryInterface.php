<?php
declare(strict_types=1);

namespace App\Repositories\Likes;

use App\Models\Like;
use App\Repositories\Exceptions\InsertionInRepositoryFailedException;
use App\Repositories\Exceptions\RetrievalInRepositoryFailedException;

interface LikeRepositoryInterface
{
    /**
     * @throws InsertionInRepositoryFailedException
     */
    public function insert(Like $like): void;

    /**
     * @return Like[]
     * @throws RetrievalInRepositoryFailedException
     */
    public function get(string $targetClass, string $targetId): array;

    /**
     * @throws RetrievalInRepositoryFailedException
     */
    public function getCount(string $targetClass, string $targetId): int;
}