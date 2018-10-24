<?php

namespace App\Conversations;

use App\Services\ButtonFactory;
use BotMan\BotMan\Messages\Conversations\Conversation;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Outgoing\Question;

class HelpConversation extends Conversation
{
    use CanBeCanceledTrait;

    /**
     * {@inheritdoc}
     */
    public function run()
    {
        $this->say('Hi, I\'m here to help you with bot reservation');
        $this->showHelp();
    }

    /**
     * @return HelpConversation
     */
    protected function showHelp(): HelpConversation
    {
        $question = Question::create('What do you like to do?')
            ->fallback('Unable to ask question')
            ->callbackId('help_show')
            ->addButtons([
                Button::create('show statuses')->value('status'),
                Button::create('reserve dev')->value('reserve'),
                Button::create('unlock dev')->value('unlock'),
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
