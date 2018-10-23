<?php

namespace App\Conversations;

use App\Dev;
use App\Facades\DevBouncer;
use App\Facades\UserInterval;
use BotMan\BotMan\Messages\Conversations\Conversation;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Outgoing\Question;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class OccupyDevConversation extends Conversation
{
    const DEFAULT_INTERVALS = ['2h', '4h', '8h', '2d', '4d'];

    /**
     * @var int. ID of dev
     */
    protected $devId;

    /**
     * @var int. Time when dev is expired in minutes from now.
     */
    protected $expiredAt;

    /**
     * @param Collection $devs
     * @return OccupyDevConversation
     */
    protected function askDevName(Collection $devs)
    {
        $buttons = $devs->map(function (Dev $dev) {
            return Button::create($dev->name)->value($dev->id);
        });

        $question = Question::create('Which one?')
            ->fallback('Unable to ask question')
            ->callbackId('ask_occupy_dev_name')
            ->addButtons($buttons->toArray());

        return $this->ask($question, function (Answer $answer) {
            if ($answer->isInteractiveMessageReply()) {
                $this->devId = (int)$answer->getValue();

                $this->askInterval();
            } else {
                $this->repeat();
            }
        });
    }

    /**
     * @return OccupyDevConversation
     */
    protected function askInterval()
    {
        $buttons = collect(self::DEFAULT_INTERVALS)->map(function ($text) {
            return Button::create($text)->value($text);
        });

        $question = Question::create('For how long?')
            ->fallback('Unable to ask question')
            ->callbackId('ask_occupy_dev_time')
            ->addButtons($buttons->toArray());

        return $this->ask($question, function (Answer $answer) {
            $time = UserInterval::parse((string)$answer->getValue());

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
     * @return OccupyDevConversation
     */
    protected function askComment()
    {
        $question = Question::create('Comment?')
            ->fallback('Unable to ask question')
            ->callbackId('ask_occupy_dev_comment');

        return $this->ask($question, function (Answer $answer) {
            try {
                DevBouncer::occupy($this->devId, $this->bot->getUser(), $this->expiredAt, $answer->getValue());

                $this->say('Dev was occupied (key)');
            } catch (\Exception $e) {
                Log::alert($e->getMessage());
                $this->say('Sorry, error occurred. Try again. (brokenheart)');
            }
        });
    }

    /**
     * Start the conversation
     */
    public function run()
    {
        $devs = Dev::allFree();

        $devs->isEmpty() ? $this->say('All dev servers are occupied right now. Sorry :(') : $this->askDevName($devs);
    }
}
