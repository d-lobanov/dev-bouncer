<?php

namespace App\Http\Controllers;

use App\Conversations\OccupyDevConversation;
use App\Conversations\ReleaseDevConversation;
use App\Dev;
use App\Services\DevMessageFormatter;
use BotMan\BotMan\BotMan;
use Illuminate\Contracts\View\Factory;
use Illuminate\View\View;

class BotManController extends Controller
{
    const SKYPE_NEW_LINE = "\n\n";

    /**
     * @var DevMessageFormatter
     */
    private $formatter;

    /**
     * @param DevMessageFormatter $formatter
     */
    public function __construct(DevMessageFormatter $formatter)
    {
        $this->formatter = $formatter;
    }

    public function handle(): void
    {
        /** @var BotMan $bot */
        $bot = app('botman');

        $bot->listen();
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
    public function occupy(BotMan $bot): void
    {
        $bot->startConversation(new OccupyDevConversation());
    }

    /**
     * @param BotMan $bot
     */
    public function release(BotMan $bot): void
    {
        $bot->startConversation(new ReleaseDevConversation());
    }

    /**
     * @param BotMan $botMan
     */
    public function status(BotMan $botMan): void
    {
        $message = Dev::all()->map([$this->formatter, 'toMessage'])->implode(self::SKYPE_NEW_LINE);

        $botMan->reply($message);
    }
}
