<?php

use App\Exceptions\BotHandler as ExceptionHandler;
use App\Http\Controllers\ConsoleController;
use App\Http\Controllers\ConversationsController;
use App\Http\Middleware\Bot\RemoveBotNickname;
use App\Http\Middleware\Bot\TrimMessage;
use App\Services\ButtonFactory;
use BotMan\BotMan\BotMan;

/** @var BotMan $botman */
$botman = resolve('botman');

$botman->middleware->received(new RemoveBotNickname(), new TrimMessage());
$botman->setExceptionHandler(new ExceptionHandler());

$botman->hears('menu', ConversationsController::class . '@menu');
$botman->hears('\b(?:status|s)\b', ConversationsController::class . '@status');
$botman->hears('reserve', ConversationsController::class . '@reserve');
$botman->hears('unlock', ConversationsController::class . '@unlock');

$botman->hears('\b(?:reserve|lock|r|l)\b (\w+) (\w+)(\s+.*)?', ConsoleController::class . '@reserve');
$botman->hears('\b(?:unlock|u)\b {name}', ConsoleController::class . '@unlock');
$botman->hears('ping', ConsoleController::class . '@ping');
$botman->hears('help', ConsoleController::class . '@help');
$botman->hears('hi|hello', ConsoleController::class . '@greeting');

$botman->hears('stop|cancel', ConsoleController::class . '@cancel')->stopsConversation();
$botman->hears('.*' . ButtonFactory::CANCEL_VALUE . '.*', ConsoleController::class . '@cancel')->stopsConversation();

$botman->fallback(function (BotMan $bot) {
    $message = sprintf(
        'M: "%s", U: %s (%s)',
        $bot->getMessage()->getText(),
        $bot->getUser()->getId(),
        $bot->getUser()->getUsername()
    );

    app('log')->channel('unknown_messages')->info($message);

    $bot->reply('Sorry, I did not understand these commands. Say **help** to see the list of commands.');
});
