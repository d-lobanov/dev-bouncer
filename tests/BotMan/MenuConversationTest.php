<?php

namespace Tests\BotMan;

use Tests\TestCase;

class MenuConversationTest extends TestCase
{
    public function testReceivesNonInteractiveResponse()
    {
        $this->bot
            ->receives('menu')
            ->assertQuestion('What do you like to do?')
            ->receives('reserve')
            ->assertQuestion('What do you like to do?');
    }

    public function testReceivesCancel()
    {
        $this->bot
            ->receives('menu')
            ->assertQuestion('What do you like to do?')
            ->receivesInteractiveMessage('cancel')
            ->assertReply('canceled');
    }

    public function testReceivesUnknownItem()
    {
        $this->bot
            ->receives('menu')
            ->assertQuestion('What do you like to do?')
            ->receives('Balbla')
            ->assertQuestion('What do you like to do?');
    }
}
