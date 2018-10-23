<?php

namespace App\Http\Middleware\Bot;

use BotMan\BotMan\BotMan;
use BotMan\BotMan\Interfaces\Middleware\Received;
use BotMan\BotMan\Messages\Incoming\IncomingMessage;

class TrimMessage implements Received
{
    /**
     * @param IncomingMessage $message
     * @param callable $next
     * @param BotMan $bot
     *
     * @return mixed
     */
    public function received(IncomingMessage $message, $next, BotMan $bot)
    {
        $trimmedText = trim($message->getText());
        $message->setText($trimmedText);

        return $next($message);
    }
}
