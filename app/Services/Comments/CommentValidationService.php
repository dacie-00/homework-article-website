<?php
declare(strict_types=1);

namespace App\Services\Comments;

use App\Services\Comments\Exceptions\InvalidCommentContentException;
use App\Services\Comments\Exceptions\InvalidCommentUsernameException;

class CommentValidationService
{
    public function execute(string $title, string $content): void
    {
        if (strlen($title) < 5) {
            throw new InvalidCommentUsernameException("Username must be at least 5 characters long");
        }
        if (strlen($title) > 20) {
            throw new InvalidCommentUsernameException("Username must be less than 100 characters long");
        }
        if (strlen($content) < 5) {
            throw new InvalidCommentContentException("Comment content must be at least 5 characters long");
        }
        if (strlen($content) > 500) {
            throw new InvalidCommentContentException("Comment content must be less than 500 characters long");
        }
    }

}