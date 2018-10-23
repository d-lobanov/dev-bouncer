<?php

namespace App\Http\Controllers;

use App\Conversations\OccupyDevConversation;
use App\Conversations\ReleaseDevConversation;
use App\Dev;
use App\Services\SkypeMessageFormatter;
use BotMan\BotMan\BotMan;
use Illuminate\Contracts\View\Factory;
use Illuminate\View\View;

class BotManController extends Controller
{
    /**
     * @var SkypeMessageFormatter
     */
    private $formatter;

    /**
     * @param SkypeMessageFormatter $formatter
     */
    public function __construct(SkypeMessageFormatter $formatter)
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
        $devs = Dev::all()->map(function (Dev $dev) {
            return [
                $this->formatter->bold($dev->name),
                $dev->isOccupied() ? $dev->expired_at->diffForHumans(null, true) : 'free',
                $dev->owner_skype_username ?? '',
            ];
        })->toArray();

        $bot->reply($this->formatter->table($devs));
    }
}
