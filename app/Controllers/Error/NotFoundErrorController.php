<?php
declare(strict_types=1);

namespace App\Controllers\Error;

use App\FlashMessage;
use App\Responses\TemplateResponse;

class NotFoundErrorController
{
    private FlashMessage $flashMessage;

    public function __construct(FlashMessage $flashMessage)
    {
        $this->flashMessage = $flashMessage;
    }

    public function __invoke()
    {
        $flashMessage = $this->flashMessage->get();
        return new TemplateResponse("errors/404", ["flashMessage" => $flashMessage]);
    }

}