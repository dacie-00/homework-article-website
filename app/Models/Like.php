<?php
declare(strict_types=1);

namespace App\Models;

use Carbon\Carbon;
use Ramsey\Uuid\Uuid;

class Like
{
    private string $targetType;
    private string $targetId;
    private string $id;
    private Carbon $createdAt;

    public function __construct(
        string $targetType,
        string $targetId,
        string $id = null,
        Carbon $createdAt = null
    )
    {
        $this->targetType = $targetType;
        $this->targetId = $targetId;
        $this->id = $id ?: Uuid::uuid4()->toString();
        $this->createdAt = $createdAt ?: Carbon::now("UTC");
    }

    public function targetClass(): string
    {
        return $this->targetType;
    }

    public function targetId(): string
    {
        return $this->targetId;
    }

    public function id(): string
    {
        return $this->id;
    }

    public function createdAt(): Carbon
    {
        return $this->createdAt;
    }

}