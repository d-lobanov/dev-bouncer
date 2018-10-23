<?php

namespace App\Services;

use BotMan\BotMan\BotMan;
use BotMan\BotMan\Exceptions\Base\BotManException;
use BotMan\Drivers\BotFramework\BotFrameworkDriver as Driver;
use Symfony\Component\HttpFoundation\Response;

class SkypeBotMan
{
    /**
     * @var BotMan
     */
    private $bot;

    /**
     * SkypeBotMan constructor.
     * @param BotMan $botMan
     */
    public function __construct(BotMan $botMan)
    {
        $this->bot = $botMan;
    }

    /**
     * Need specific method for sending messages to skype. Read more here https://botman.io/2.0/sending
     *
     * @param string $message
     * @param string|array $recipients
     *
     * @return Response
     *
     * @throws BotManException
     */
    public function say(string $message, $recipients)
    {
        $params = ['serviceUrl' => 'https://smba.trafficmanager.net/apis/'];

        return $this->bot->say($message, $recipients, Driver::class, $params);
    }
}
