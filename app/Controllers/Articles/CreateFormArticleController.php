<?php
declare(strict_types=1);

namespace App\Controllers\Articles;

use App\FlashMessage;
use App\Responses\TemplateResponse;

class CreateFormArticleController
{
    private FlashMessage $flashMessage;

    public function __construct(FlashMessage $flashMessage)
    {

        $this->flashMessage = $flashMessage;
    }

    public function __invoke(): TemplateResponse
    {
        $flashMessage = $this->flashMessage->get();
        return new TemplateResponse("articles/create-form", ["flashMessage" => $flashMessage]);
    }
}