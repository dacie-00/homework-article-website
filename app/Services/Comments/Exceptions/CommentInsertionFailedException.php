<?php
declare(strict_types=1);

namespace App\Services\Comments\Exceptions;

use App\Exceptions\DomainException;

class CommentInsertionFailedException extends DomainException
{
}