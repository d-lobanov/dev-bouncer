<?php

namespace App\Conversations;

use App\Dev;
use App\Facades\DevBouncer;
use App\Facades\UserInterval;
use App\Services\ButtonFactory;
use BotMan\BotMan\Messages\Conversations\Conversation;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Outgoing\Question;
use Illuminate\Support\Collection;

class ReserveDevConversation extends Conversation
{
    const DEFAULT_INTERVALS = ['2h', '4h', 'till tomorrow', '2d'];

    /**
     * @var int. Name of dev
     */
    protected $devName;

    /**
     * @var int. Time when dev is expired in minutes from now.
     */
    protected $expiredAt;

    /**
     * @param Collection $devs
     * @return ReserveDevConversation
     */
    protected function askDevName(Collection $devs)
    {
        $buttons = $devs->map(function (Dev $dev) {
            return Button::create($dev->name)->value($dev->name);
        });

        $question = Question::create('Which one?')
            ->fallback('Unable to ask question')
            ->callbackId('reserve_ask_dev_name')
            ->addButtons($buttons->toArray())
            ->addButton(ButtonFactory::cancel());

        return $this->ask($question, function (Answer $answer) {
            if ($answer->isInteractiveMessageReply()) {
                $this->devName = $answer->getValue();
                $this->askInterval();

                return;
            }

            $this->repeat();
        });
    }

    /**
     * @return ReserveDevConversation
     */
    protected function askInterval()
    {
        $buttons = collect(self::DEFAULT_INTERVALS)->map(function ($text) {
            return Button::create($text)->value($text);
        });

        $question = Question::create('For how long?')
            ->fallback('Unable to ask question')
            ->callbackId('reserve_ask_interval')
            ->addButtons($buttons->toArray())
            ->addButton(ButtonFactory::cancel());

        return $this->ask($question, function (Answer $answer) {
            $message = $answer->getValue() ?? $answer->getText();
            $time = UserInterval::parse($message);

            if ($time) {
                $this->expiredAt = $time;
                $this->askComment();

                return;
            }

            $this->say('Sorry did\'n get it');
            $this->repeat();
        });
    }

    /**
     * @return ReserveDevConversation
     */
    protected function askComment()
    {
        return $this->ask('Comment?', function (Answer $answer) {
            DevBouncer::reserveByName($this->devName, $this->bot->getUser(), $this->expiredAt, $answer->getText());

            $this->say("(key) #$this->devName has been reserved");
        });
    }

    /**
     * Start the conversation
     */
    public function run()
    {
        $devs = Dev::allFree();

        $devs->isEmpty() ? $this->say('All dev servers are reserved right now. Sorry :|') : $this->askDevName($devs);
    }
}
