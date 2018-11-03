<?php

namespace Tests\BotMan;

use App\Http\Controllers\ConversationsController;
use Tests\TestCase;

/**
 * @see ConversationsController::menu()
 */
class MenuConversationTest extends TestCase
{
    public function testReceivesNonInteractiveResponse(): void
    {
        $this->bot
            ->receives('menu')
            ->assertQuestion('What do you like to do?')
            ->receives('reserve')
            ->assertQuestion('What do you like to do?');
    }

    public function testReceivesCancel(): void
    {
        $this->bot
            ->receives('menu')
            ->assertQuestion('What do you like to do?')
            ->receivesInteractiveMessage('cancel')
            ->assertReply('canceled');
    }

    public function testReceivesUnknownItem(): void
    {
        $this->bot
            ->receives('menu')
            ->assertQuestion('What do you like to do?')
            ->receives('Balbla')
            ->assertQuestion('What do you like to do?');
    }
}
