<?php

namespace App\Http\Controllers;

use BotMan\BotMan\BotMan;
use Illuminate\Contracts\View\Factory;
use Illuminate\View\View;

class BotManController extends Controller
{
    /**
     * Handle botman connections.
     */
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
}
