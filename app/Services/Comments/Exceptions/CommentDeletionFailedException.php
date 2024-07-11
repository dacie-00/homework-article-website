<?php
declare(strict_types=1);

namespace App\Services\Comments\Exceptions;

use App\Exceptions\DomainException;
use Exception;

class CommentDeletionFailedException extends DomainException
{
}