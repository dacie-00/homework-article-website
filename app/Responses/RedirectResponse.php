<?php
declare(strict_types=1);

namespace App\Responses;

class RedirectResponse
{
    private string $url;

    public function __construct(string $url)
    {
        $this->url = $url;
    }

    public function url(): string
    {
        return $this->url;
    }
}