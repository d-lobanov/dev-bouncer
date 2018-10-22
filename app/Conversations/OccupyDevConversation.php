<?php

namespace App\Conversations;

use App\Dev;
use App\Services\DevLocker;
use App\Services\UserIntervalConverter;
use BotMan\BotMan\Messages\Conversations\Conversation;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Outgoing\Question;

class OccupyDevConversation extends Conversation
{
    const DEFAULT_INTERVALS = ['2h', '4h', '8h', '2d', '4d'];

    /**
     * @var int. ID of dev
     */
    protected $devId;

    /**
     * @var
     */
    protected $expiredAt;

    /**
     * @return OccupyDevConversation
     */
    public function askDevName()
    {
        $devs = Dev::allFree();

        $buttons = $devs->map(function (Dev $dev, $id) {
            return Button::create($dev->name)->value($id);
        });

        $question = Question::create('Which dev do you want?')
            ->fallback('Unable to ask question')
            ->callbackId('ask_occupy_dev_name')
            ->addButtons($buttons->toArray());

        return $this->ask($question, function (Answer $answer) {
            if ($answer->isInteractiveMessageReply()) {
                $this->devId = (int)$answer->getValue();

                $this->askInterval();
            }
        });
    }

    /**
     * @return OccupyDevConversation
     */
    public function askInterval()
    {
        $buttons = collect(self::DEFAULT_INTERVALS)->map(function ($text) {
            return Button::create($text)->value($text);
        });

        $question = Question::create('For how long?')
            ->fallback('Unable to ask question')
            ->callbackId('ask_occupy_dev_time')
            ->addButtons($buttons->toArray());

        return $this->ask($question, function (Answer $answer) {
            $time = UserIntervalConverter::convert((string)$answer->getValue());

            if ($time) {
                $this->expiredAt = $time;
                $this->askComment();

                return;
            }

            $this->say('Sorry did\'n get it');
            $this->repeat();
        });
    }

    public function askComment()
    {
        $question = Question::create('Comment?')
            ->fallback('Unable to ask question')
            ->callbackId('ask_occupy_dev_comment');

        return $this->ask($question, function (Answer $answer) {
            $comment = $answer->getValue();

            /** @var DevLocker $locker */
            $locker = app()->make(DevLocker::class);

            $locker->lock($this->devId, $this->bot->getUser(), $this->expiredAt, $comment);
        });
    }

    /**
     * Start the conversation
     */
    public function run()
    {
        $this->askDevName();
    }
}
