<?php
declare(strict_types=1);

namespace App\Responses;

class TemplateResponse
{

    private string $template;
    private array $data;

    public function __construct(string $template, array $data)
    {
        $this->template = $template;
        $this->data = $data;
    }

    public function template(): string
    {
        return $this->template;
    }

    public function data(): array
    {
        return $this->data;
    }
}