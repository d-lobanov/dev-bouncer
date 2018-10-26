<?php

namespace App\Http\Controllers;

use App\Conversations\MenuConversation;
use App\Conversations\ReserveDevConversation;
use App\Conversations\StatusConversation;
use App\Conversations\UnlockDevConversation;
use BotMan\BotMan\BotMan;

class ConversationsController extends Controller
{
    /**
     * @param BotMan $bot
     */
    public function menu(BotMan $bot)
    {
        $bot->startConversation(new MenuConversation());
    }

    /**
     * @param BotMan $bot
     */
    public function status(BotMan $bot): void
    {
        $bot->startConversation(new StatusConversation());
    }

    /**
     * @param BotMan $bot
     */
    public function reserve(BotMan $bot): void
    {
        $bot->startConversation(new ReserveDevConversation());
    }

    /**
     * @param BotMan $bot
     */
    public function unlock(BotMan $bot): void
    {
        $bot->startConversation(new UnlockDevConversation());
    }

}
