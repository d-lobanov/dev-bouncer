<?php

use App\Exceptions\BotResponsible;
use App\Http\Controllers\BotManController;
use App\Http\Middleware\Bot\RemoveBotNickname;
use App\Http\Middleware\Bot\TrimMessage;
use BotMan\BotMan\BotMan;

/** @var BotMan $botman */
$botman = resolve('botman');

$botman->middleware->received(new RemoveBotNickname(), new TrimMessage());

$botman->hears('help', BotManController::class . '@help');
$botman->hears('status', BotManController::class . '@status');
$botman->hears('reserve', BotManController::class . '@reserve');
$botman->hears('unlock', BotManController::class . '@unlock');
$botman->hears('hi|ping', BotManController::class . '@ping');

$botman->hears('stop|cancel', function (BotMan $bot) {
    $bot->reply('stopped');
})->stopsConversation();

$botman->fallback(function (BotMan $bot) {
    $bot->reply('Sorry, I did not understand these commands.');
});

$botman->exception(BotResponsible::class, function (BotResponsible $e, BotMan $bot) {
    $bot->reply($e->responseMessage());
});
