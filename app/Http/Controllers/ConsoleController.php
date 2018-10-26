<?php

namespace App\Http\Controllers;

use App\Exceptions\DevIsReservedException;
use App\Exceptions\DevNotFoundException;
use App\Facades\DevBouncer;
use App\Facades\UserInterval;
use BotMan\BotMan\BotMan;

class ConsoleController extends Controller
{
    /**
     * @param BotMan $bot
     * @param string $name
     * @param string $time
     * @param null|string $comment
     * @throws DevIsReservedException|DevNotFoundException
     */
    public function reserve(BotMan $bot, string $name, string $time, ?string $comment = null): void
    {
        $expiredAt = UserInterval::parse($time);

        if (!$expiredAt) {
            $bot->reply('You should provide valid time: example \'2h\'');

            return;
        }

        DevBouncer::reserveByName($name, $bot->getUser(), $expiredAt, $comment);
        $bot->reply("(key) #$name has been reserved");
    }

    /**
     * @param BotMan $bot
     * @param string $name
     * @throws DevNotFoundException
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

        $bot->reply($message === 'ping' ? 'pong' : $message);
    }

    /**
     * @param BotMan $bot
     */
    public function cancel(BotMan $bot)
    {
        $bot->reply('canceled');
    }
}
