<?php

namespace App\Conversations;

use App\Services\ButtonFactory;
use BotMan\BotMan\Messages\Incoming\IncomingMessage;

trait CanBeCanceledTrait
{
    /**
     * @param IncomingMessage $message
     * @return bool
     */
    public function stopsConversation(IncomingMessage $message)
    {
        if ($message->getText() === ButtonFactory::CANCEL_VALUE) {
            return true;
        }

        return parent::stopsConversation($message);
    }

}
