<?php
declare(strict_types=1);

namespace App\Models;

use Carbon\Carbon;
use Ramsey\Uuid\Uuid;

class Comment
{
    private string $content;
    private string $userId;
    private string $articleId;
    private string $id;
    private int $likes;
    private Carbon $createdAt;
    private Carbon $updatedAt;

    public function __construct(
        string $content,
        string $userId,
        string $articleId,
        string $id = null,
        int $likes = 0,
        Carbon $createdAt = null,
        Carbon $updatedAt = null
    ) {
        $this->content = $content;
        $this->userId = $userId;
        $this->articleId = $articleId;
        $this->id = $id ?: Uuid::uuid4()->toString();
        $this->likes = $likes;
        $this->createdAt = $createdAt ?: Carbon::now("UTC");
        $this->updatedAt = $updatedAt ?: Carbon::now("UTC");
    }

    public function content(): string
    {
        return $this->content;
    }

    public function userId(): string
    {
        return $this->userId;
    }

    public function articleId(): string
    {
        return $this->articleId;
    }

    public function id(): string
    {
        return $this->id;
    }

    public function likes(): int
    {
        return $this->likes;
    }

    public function createdAt(): Carbon
    {
        return $this->createdAt;
    }

    public function updatedAt(): Carbon
    {
        return $this->updatedAt;
    }

    public static function fromArray(array $arguments): Comment
    {
        // TODO: add validation here?
        return new self(
            $arguments["content"],
            $arguments["user_id"],
            $arguments["article_id"],
            $arguments["comment_id"],
            (int)$arguments["likes"],
            Carbon::parse($arguments["created_at"]),
            Carbon::parse($arguments["updated_at"]),
        );
    }
}