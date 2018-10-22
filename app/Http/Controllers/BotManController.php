<?php

namespace App\Http\Controllers;

use App\Conversations\ExampleConversation;
use App\Conversations\OccupyDevConversation;
use BotMan\BotMan\BotMan;
use Illuminate\Contracts\View\Factory;
use Illuminate\View\View;

class BotManController extends Controller
{
    public function handle(): void
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

    /**
     * @param BotMan $bot
     */
    public function take(BotMan $bot): void
    {
        $bot->startConversation(new OccupyDevConversation());
    }
}
