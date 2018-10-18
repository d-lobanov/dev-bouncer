<?php

namespace App\Http\Controllers;

use App\Conversations\ExampleConversation;
use BotMan\BotMan\BotMan;
use Illuminate\Contracts\View\Factory;
use Illuminate\View\View;

class BotManController extends Controller
{
    /**
     * Place your BotMan logic here.
     */
    public function handle()
    {
        $botman = app('botman');

        $botman->listen();
    }

    /**
     * @return Factory|View
     */
    public function tinker()
    {
        return view('tinker');
    }

    public function take(BotMan $bot, $name, $time)
    {

    }

    /**
     * @param  BotMan $bot
     */
    public function startConversation(BotMan $bot)
    {
        $bot->startConversation(new ExampleConversation());
    }
}
