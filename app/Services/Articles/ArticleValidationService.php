<?php
declare(strict_types=1);

namespace App\Services\Articles;

use App\Services\Articles\Exceptions\InvalidArticleContentException;
use App\Services\Articles\Exceptions\InvalidArticleTitleException;
use Respect\Validation\Exceptions\NestedValidationException;
use Respect\Validation\Validator;

class ArticleValidationService
{
    public function execute(string $title, string $content): void
    {
        try {
            Validator::length(5, 100)->setName("Title")->assert($title);
        } catch (NestedValidationException $e) {
            throw new InvalidArticleTitleException(
                implode("\n", $e->getMessages()),
            );
        }
        try {
            Validator::length(5, 2000)->setName("Content")->assert($content);
        } catch (NestedValidationException $e) {
            throw new InvalidArticleContentException(
                implode("\n", $e->getMessages()),
            );
        }
    }
}