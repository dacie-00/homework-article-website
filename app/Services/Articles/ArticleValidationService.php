<?php
declare(strict_types=1);

namespace App\Services\Articles;

use App\Services\Articles\Exceptions\InvalidArticleContentException;
use App\Services\Articles\Exceptions\InvalidArticleTitleException;

class ArticleValidationService
{
    public function execute(string $title, string $content): void
    {
        if (strlen($title) < 10) {
            throw new InvalidArticleTitleException("Title must be at least 10 characters long");
        }
        if (strlen($title) > 100) {
            throw new InvalidArticleTitleException("Title must be less than 100 characters long");
        }
        if (strlen($content) < 50) {
            throw new InvalidArticleContentException("Content must be at least 50 characters long");
        }
        if (strlen($content) > 2000) {
            throw new InvalidArticleContentException("Content must be less than 2000 characters long");
        }
    }

}