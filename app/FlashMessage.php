<?php
declare(strict_types=1);

namespace App;

class FlashMessage
{
    public function set(Message $message): void
    {
        $_SESSION["flashMessage"] = [
            "type" => $message->type(),
            "message" => $message->message(),
            "parameters" => $message->parameters()
        ];
    }

    public function get(): ?Message
    {
        if (!isset($_SESSION["flashMessage"])) {
            return null;
        }
        $messageData = $_SESSION["flashMessage"];
        $message = new Message($messageData["type"], $messageData["message"], $messageData["parameters"]);
        unset($_SESSION["flashMessage"]);
        return $message;
    }
}