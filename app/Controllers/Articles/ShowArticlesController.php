<?php
declare(strict_types=1);

namespace App\Controllers\Articles;

class ShowArticlesController
{
    public function __invoke($name): string
    {
        return "showing article $name";
    }
}