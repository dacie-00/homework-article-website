<?php
declare(strict_types=1);

namespace App\Controllers\Articles;

use App\FlashMessage;
use App\Responses\TemplateResponse;

class CreateArticleController
{
    private FlashMessage $flashMessage;

    public function __construct(FlashMessage $flashMessage)
    {

        $this->flashMessage = $flashMessage;
    }

    public function __invoke()
    {
        $flashMessage = $this->flashMessage->get();
        return new TemplateResponse("articles/create", ["flashMessage" => $flashMessage]);
    }
}