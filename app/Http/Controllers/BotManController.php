<?php

namespace App\Http\Controllers;

use App\Conversations\HelpConversation;
use App\Conversations\ReserveDevConversation;
use App\Conversations\StatusConversation;
use App\Conversations\UnlockDevConversation;
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
    public function ping(BotMan $bot)
    {
        $message = $bot->getMessage()->getText();

        $bot->reply($message === 'ping' ? 'pong' : $message);
    }

    /**
     * @param BotMan $bot
     */
    public function help(BotMan $bot)
    {
        $bot->startConversation(new HelpConversation());
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
