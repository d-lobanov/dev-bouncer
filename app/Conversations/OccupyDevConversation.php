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
     * @param UserIntervalConverter $intervalConverter
     */
    public function __construct(UserIntervalConverter $intervalConverter)
    {
        $this->intervalConverter = $intervalConverter;
    }

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
            $date = $this->intervalConverter->convert($answer->getText());
            $this->bot->reply($date->hour);
            $this->bot->reply($date->day);
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
