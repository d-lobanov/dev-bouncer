<?php

namespace App\Http\Middleware\Bot;

use BotMan\BotMan\BotMan;
use BotMan\BotMan\Interfaces\Middleware\Received;
use BotMan\BotMan\Messages\Incoming\IncomingMessage;

class RemoveBotNickname implements Received
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
        $username = config('botman.config.bot_name');

        $updatedText = preg_replace('/@?' . $username . '/', '', $message->getText());
        $message->setText($updatedText);

        return $next($message);
    }
}
