<?php

namespace Tests\BotMan;

use Tests\TestCase;

/**
 * @covers \App\Http\Controllers\ConsoleController::unlock
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
}
