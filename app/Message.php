<?php
declare(strict_types=1);

namespace App;

class Message
{
    private string $type;
    private string $message;
    private array $parameters;

    public const TYPE_SUCCESS = "success";
    public const TYPE_ERROR = "error";

    public function __construct(string $type, string $message, array $parameters = [])
    {
        $this->type = $type;
        $this->message = $message;
        $this->parameters = $parameters;
    }

    public function message(): string
    {
        return $this->message;
    }

    public function type(): string
    {
        return $this->type;
    }

    public function parameters(): array
    {
        return $this->parameters;
    }
}