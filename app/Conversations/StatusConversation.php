<?php

namespace App\Conversations;

use App\Dev;
use App\Facades\SkypeFormatter;
use App\Services\SkypeMessageFormatter;
use BotMan\BotMan\Messages\Conversations\Conversation;

class StatusConversation extends Conversation
{
    /**
     * {@inheritdoc}
     */
    public function run()
    {
        $messages = Dev::all()->map([SkypeFormatter::class, 'devStatus']);

        $this->say($messages->isNotEmpty() ? $messages->implode(SkypeMessageFormatter::SKYPE_NEW_LINE) : 'No devs reserved');
    }
}
