<?php

namespace App\Exceptions;

use BotMan\BotMan\BotMan;
use BotMan\BotMan\Handlers\ExceptionHandler;

class BotHandler extends ExceptionHandler
{
    /**
     * {@inheritdoc}
     */
    public function handleException($e, BotMan $bot)
    {
        $bot->removeStoredConversation();

        if ($e instanceof UserVisible) {
            $bot->reply($e->getMessage());

            return;
        }

        try {
            parent::handleException($e, $bot);
        } catch (\Exception $e) {
            $bot->reply('Oops, server error');
        }
    }
}
