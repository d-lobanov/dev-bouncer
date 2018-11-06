<?php

namespace Tests\BotMan;

use App\Http\Controllers\ConsoleController;
use Tests\TestCase;

/**
 * @see ConsoleController::greeting()
 */
class GreetingTest extends TestCase
{
    public function testGreeting(): void
    {
        $reply = 'Hi! If you need help just say **help**';

        $this->bot
            ->receives('hi')
            ->assertReply($reply);

        $this->bot
            ->receives('hello')
            ->assertReply($reply);
    }

    public function testEmptyMessage(): void
    {
        $this->bot
            ->receives('')
            ->assertReply('Sorry, I did not understand these commands. Say **help** to see the list of commands.');
    }
}
