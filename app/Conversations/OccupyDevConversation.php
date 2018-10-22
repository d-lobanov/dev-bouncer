<?php

namespace App\Conversations;

use App\Dev;
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
     * @var int. Time in minutes
     */
    protected $time;

    /**
     * @var UserIntervalConverter
     */
    protected $intervalConverter;

    /**
     * @return OccupyDevConversation
     */
    public function askDevName(): OccupyDevConversation
    {
        $buttons = Dev::allFree()->map(function (Dev $dev, $id) {
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
    public function askInterval(): OccupyDevConversation
    {
        $buttons = collect(self::DEFAULT_INTERVALS)->map(function ($text) {
            return Button::create($text)->value($text);
        });

        $question = Question::create('For how long?')
            ->fallback('Unable to ask question')
            ->callbackId('ask_occupy_dev_time')
            ->addButtons($buttons->toArray());

        return $this->ask($question, function (Answer $answer) {
            $date = UserIntervalConverter::convert((string)$answer->getText());

            if (!$date) {
                $this->say('Sorry did\'n get it');
                $this->repeat();
            }

            $this->bot->reply($date->format('Y-m-d H:i:s'));
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
