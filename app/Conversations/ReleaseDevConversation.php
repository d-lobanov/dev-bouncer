<?php

namespace App\Conversations;

use App\Dev;
use App\Facades\DevBouncer;
use BotMan\BotMan\Messages\Conversations\Conversation;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Outgoing\Question;
use Illuminate\Support\Collection;

class ReleaseDevConversation extends Conversation
{
    /**
     * @param Collection $devs
     * @return ReleaseDevConversation
     */
    protected function askDevName(Collection $devs)
    {
        $buttons = $devs->map(function (Dev $dev) {
            return Button::create($dev->name)->value($dev->id);
        });

        $question = Question::create('Which one?')
            ->fallback('Unable to ask question')
            ->callbackId('ask_release_dev_name')
            ->addButtons($buttons->toArray());

        return $this->ask($question, function (Answer $answer) {
            if ($answer->isInteractiveMessageReply()) {
                DevBouncer::release($answer->getValue());

                $this->say('Dev is no longer owned by you');
            } else {
                $this->repeat();
            }
        });
    }

    /**
     * Start the conversation
     */
    public function run()
    {
        $id = $this->bot->getUser()->getId();

        $devs = Dev::whereOwnerSkypeId($id)->get();

        $devs->isEmpty() ? $this->say('You don\'t own any devs') : $this->askDevName($devs);
    }
}