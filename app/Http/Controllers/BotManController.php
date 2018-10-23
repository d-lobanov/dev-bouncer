<?php

namespace App\Http\Controllers;

use App\Conversations\OccupyDevConversation;
use App\Conversations\ReleaseDevConversation;
use App\Dev;
use App\Services\SkypeMessageFormatter as Formatter;
use BotMan\BotMan\BotMan;
use Illuminate\Contracts\View\Factory;
use Illuminate\View\View;

class BotManController extends Controller
{
    /**
     * @var Formatter
     */
    private $formatter;

    /**
     * @param Formatter $formatter
     */
    public function __construct(Formatter $formatter)
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
     * @param BotMan $bot
     */
    public function status(BotMan $bot): void
    {
        $message = Dev::all()->map([$this->formatter, 'devStatus'])->implode(Formatter::SKYPE_NEW_LINE);

        $bot->reply($message);
    }
}
