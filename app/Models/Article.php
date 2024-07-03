<?php
declare(strict_types=1);

namespace App\Models;

use Ramsey\Uuid\Uuid;

class Article
{
    private string $title;
    private string $content;
    private string $id;

    public function __construct(string $title, string $content, string $id = null)
    {
        $this->title = $title;
        $this->content = $content;
        $this->id = $id ?: Uuid::uuid4()->toString();
    }

    public function title(): string
    {
        return $this->title;
    }

    public function content(): string
    {
        return $this->content;
    }

    public function id(): string
    {
        return $this->id;
    }
}