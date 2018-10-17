<?php

namespace App\Http\Middleware\Bot;

use BotMan\BotMan\BotMan;
use BotMan\BotMan\Interfaces\Middleware\Received;
use BotMan\BotMan\Messages\Incoming\IncomingMessage;

class RemoveBotNickname implements Received
{
    const BOT_NICKNAME = 'dev_test_1';

    /**
     * @param IncomingMessage $message
     * @param callable $next
     * @param BotMan $bot
     *
     * @return mixed
     */
    public function received(IncomingMessage $message, $next, BotMan $bot)
    {
        $updatedText = str_replace(self::BOT_NICKNAME, '', $message->getText());
        $message->setText($updatedText);

        return $next($message);
    }
}
