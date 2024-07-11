<?php
declare(strict_types=1);

namespace App\Services\Comments;

use App\Services\Comments\Exceptions\InvalidCommentContentException;
use App\Services\Comments\Exceptions\InvalidCommentUsernameException;
use Respect\Validation\Exceptions\NestedValidationException;
use Respect\Validation\Validator;

class CommentValidationService
{
    public function execute(string $title, string $content): void
    {
        try {
            Validator::length(5, 20)->setName("Username")->assert($title);
        } catch (NestedValidationException $e) {
            throw new InvalidCommentUsernameException(
                implode("\n", $e->getMessages()),
            );
        }
        try {
            Validator::length(5, 500)->setName("Content")->assert($content);
        } catch (NestedValidationException $e) {
            throw new InvalidCommentContentException(
                implode("\n", $e->getMessages()),
            );
        }
    }

}