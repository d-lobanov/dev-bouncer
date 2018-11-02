<?php

namespace App\Conversations;

use App\Dev;
use App\Facades\DevBouncer;
use App\Services\ButtonFactory;
use BotMan\BotMan\Messages\Conversations\Conversation;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Outgoing\Question;
use Illuminate\Support\Collection;

class UnlockDevConversation extends Conversation
{
    /**
     * @param Collection $devs
     * @return UnlockDevConversation
     */
    protected function askDevName(Collection $devs)
    {
        $buttons = $devs->map(function (Dev $dev) {
            return Button::create($dev->name)->value($dev->name);
        });

        $question = Question::create('Which one?')
            ->fallback('Unable to ask question')
            ->callbackId('unlock_ask_dev_name')
            ->addButtons($buttons->toArray())
            ->addButton(ButtonFactory::cancel());

        return $this->ask($question, function (Answer $answer) {
            if (!$answer->isInteractiveMessageReply()) {
                $this->repeat();

                return;
            }

            $name = $answer->getValue();
            $userId = $this->bot->getUser()->getId();

            DevBouncer::unlockByNameAndOwnerId($name, $userId);

            $this->say("(dropthemic) #$name has been unlocked");
        });
    }

    /**
     * Start the conversation
     */
    public function run()
    {
        $id = $this->bot->getUser()->getId();

        $devs = Dev::whereOwnerSkypeId($id)->get();

        $devs->isEmpty() ? $this->say('You don\'t have any devs') : $this->askDevName($devs);
    }
}
