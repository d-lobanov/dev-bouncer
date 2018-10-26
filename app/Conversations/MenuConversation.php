<?php

namespace App\Conversations;

use App\Services\ButtonFactory;
use BotMan\BotMan\Messages\Conversations\Conversation;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Outgoing\Question;

class MenuConversation extends Conversation
{
    /**
     * {@inheritdoc}
     */
    public function run()
    {
        $this->show();
    }

    /**
     * @return MenuConversation
     */
    protected function show(): MenuConversation
    {
        $question = Question::create('What do you like to do?')
            ->fallback('Unable to ask question')
            ->callbackId('help_show')
            ->addButtons([
                Button::create('status')->value('status'),
                Button::create('reserve')->value('reserve'),
                Button::create('unlock')->value('unlock'),
                ButtonFactory::cancel(),
            ]);

        return $this->ask($question, function (Answer $answer) {
            if ($answer->isInteractiveMessageReply()) {
                $this->processHelp($answer->getValue());
            } else {
                $this->repeat();
            }
        });
    }

    /**
     * @param string $answer
     */
    protected function processHelp(string $answer)
    {
        switch ($answer) {
            case 'status':
                $this->bot->startConversation(new StatusConversation());
                break;

            case 'reserve':
                $this->bot->startConversation(new ReserveDevConversation());
                break;

            case 'unlock':
                $this->bot->startConversation(new UnlockDevConversation());
                break;

            default:
                $this->repeat();
        }
    }
}
