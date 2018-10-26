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

$botman->hears('help', ConversationsController::class . '@help');
$botman->hears('status', ConversationsController::class . '@status');
$botman->hears('reserve', ConversationsController::class . '@reserve');
$botman->hears('unlock', ConversationsController::class . '@unlock');

$botman->hears('ping', ConsoleController::class . '@ping');
$botman->hears('reserve\s+(\w+)\s+(\w+)\s+([\w\s]*\w)?', ConsoleController::class . '@reserve');
$botman->hears('unlock\s+(\w+)\s*', ConsoleController::class . '@unlock');

$botman->hears('stop|cancel', ConsoleController::class . '@cancel')->stopsConversation();
$botman->hears('.*' . ButtonFactory::CANCEL_VALUE . '.*', ConsoleController::class . '@cancel')->stopsConversation();

$botman->fallback(function (BotMan $bot) {
    $bot->reply('Sorry, I did not understand these commands.');
});
