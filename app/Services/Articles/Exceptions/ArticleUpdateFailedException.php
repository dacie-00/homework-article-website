<?php
declare(strict_types=1);

namespace App\Services\Articles\Exceptions;

use App\Exceptions\DomainException;
use Exception;

class ArticleUpdateFailedException extends DomainException
{
}