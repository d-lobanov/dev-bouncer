<?php

namespace App\Http\Controllers;

use App\Conversations\ExampleConversation;
use App\Conversations\OccupyDevConversation;
use BotMan\BotMan\BotMan;
use Illuminate\Contracts\View\Factory;
use Illuminate\View\View;

class BotManController extends Controller
{
    /**
     * @var OccupyDevConversation
     */
    private $occupyDevConversation;

    /**
     * @param OccupyDevConversation $occupyDevConversation
     */
    public function __construct(OccupyDevConversation $occupyDevConversation)
    {
        $this->occupyDevConversation = $occupyDevConversation;
    }

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
        $bot->startConversation($this->occupyDevConversation);
    }
}
