<?php

namespace App\Http\Controllers;

use App\Facades\DevBouncer;
use App\Facades\UserInterval;
use App\Services\SkypeMessageFormatter;
use BotMan\BotMan\BotMan;

class ConsoleController extends Controller
{
    /**
     * @param BotMan $bot
     * @param string $name
     * @param string $interval
     * @param null|string $comment
     */
    public function reserve(BotMan $bot, string $name, string $interval, ?string $comment = null): void
    {
        $expiredAt = UserInterval::parse($interval);

        if (!$expiredAt) {
            $bot->reply('You should provide valid time: example \'2h\'');

            return;
        }

        DevBouncer::reserveByName($name, $bot->getUser(), $expiredAt, trim($comment ?? ''));
        $bot->reply("(key) #$name has been reserved");
    }

    /**
     * @param BotMan $bot
     * @param string $name
     */
    public function unlock(BotMan $bot, string $name): void
    {
        DevBouncer::unlockByNameAndOwnerId($name, $bot->getUser()->getId());
        $bot->reply("(dropthemic) #$name has been unlocked");
    }


    /**
     * @param BotMan $bot
     */
    public function ping(BotMan $bot)
    {
        $message = $bot->getMessage()->getText();

        $bot->reply(strtolower($message) === 'ping' ? 'pong' : $message);
    }

    /**
     * @param BotMan $bot
     */
    public function cancel(BotMan $bot)
    {
        $bot->reply('canceled');
    }

    /**
     * @param BotMan $bot
     */
    public function help(BotMan $bot)
    {
        $nl = SkypeMessageFormatter::SKYPE_NEW_LINE;

        $message =
            '**reserve** {name} {interval} {?comment}' . $nl .
            '  {name} – name of dev' . $nl .
            '  {interval} – interval' . $nl . $nl .
            '**unlock** {name}' . $nl .
            '  {name} – name of dev';

        $bot->reply($message);
    }
}
