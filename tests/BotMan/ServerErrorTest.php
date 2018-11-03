<?php

namespace Tests\BotMan;

use App\Http\Controllers\ConsoleController;
use Exception;
use Prophecy\Argument;
use Tests\TestCase;

class ServerErrorTest extends TestCase
{
    /**
     * Check if bot can handle exception.
     */
    public function testUnexpectedException(): void
    {
        $mock = $this->prophesize(ConsoleController::class);
        $mock->greeting(Argument::any())->willThrow(new Exception());

        $this->app->instance(ConsoleController::class, $mock->reveal());

        $this->bot->receives('hi')->assertReply('Oops, server error');
    }
}
