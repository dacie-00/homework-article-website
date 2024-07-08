<?php
declare(strict_types=1);

namespace App\Models;

use Carbon\Carbon;
use Ramsey\Uuid\Uuid;

class Article
{
    private string $title;
    private string $content;
    private string $id;
    private int $likes;
    private Carbon $createdAt;
    private Carbon $updatedAt;

    public function __construct(
        string $title,
        string $content,
        string $id = null,
        int $likes = 0,
        Carbon $createdAt = null,
        Carbon $updatedAt = null
    ) {
        $this->title = $title;
        $this->content = $content;
        $this->id = $id ?: Uuid::uuid4()->toString();
        $this->likes = $likes;
        $this->createdAt = $createdAt ?: Carbon::now("UTC");
        $this->updatedAt = $updatedAt ?: Carbon::now("UTC");
    }

    public function title(): string
    {
        return $this->title;
    }

    public function content(): string
    {
        return $this->content;
    }

    public function likes(): int
    {
        return $this->likes;
    }

    public function id(): string
    {
        return $this->id;
    }

    public function createdAt(): Carbon
    {
        return $this->createdAt;
    }

    public function updatedAt(): Carbon
    {
        return $this->updatedAt;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    public function setUpdatedAt(Carbon $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }
}