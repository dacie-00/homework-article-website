<?php
declare(strict_types=1);

namespace App\Services\Likes;

use App\Models\Like;
use App\Repositories\Exceptions\InsertionInRepositoryFailedException;
use App\Repositories\Likes\LikeRepositoryInterface;
use App\Services\Likes\Exceptions\ItemLikeFailedException;

class LikeItemService
{
    private LikeRepositoryInterface $likeRepository;

    public function __construct(
        LikeRepositoryInterface $likeRepository
    ) {
        $this->likeRepository = $likeRepository;
    }

    /**
     * @throws ItemLikeFailedException
     */
    public function execute(string $itemType, string $itemId): void
    {
        try {
            $this->likeRepository->insert(
                new Like(
                    $itemType,
                    $itemId
                )
            );
        } catch (InsertionInRepositoryFailedException $e) {
            throw new ItemLikeFailedException(
                "Failed to like ($itemType) item with id ($itemId)",
                0,
                $e
            );
        }
    }
}