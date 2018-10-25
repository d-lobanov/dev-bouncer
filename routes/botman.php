<?php

use App\Http\Controllers\BotManController;
use App\Http\Middleware\Bot\RemoveBotNickname;
use App\Http\Middleware\Bot\TrimMessage;
use App\Services\ButtonFactory;
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

$botman->hears(".*" . ButtonFactory::CANCEL_VALUE . ".*", function (BotMan $bot) {
    $bot->reply('canceled');
})->stopsConversation();

$botman->fallback(function (BotMan $bot) {
    $bot->reply('Sorry, I did not understand these commands.');
});

/**
 * Cheat
 * TODO: remove when will be on prod
 */
$botman->hears('test_change {name} {eMinutes} {nMinutes}', function (BotMan $bot, $name, $eMinutes, $nMinutes) {
    $dev = \App\Dev::whereName($name)->first();

    $dev->reserve(
        $bot->getUser()->getId(),
        $bot->getUser()->getUsername(),
        now()->addMinutes((int)$eMinutes),
        null
    );

    $dev->notified_at = now()->subMinute((int)$nMinutes);
    $dev->save();

    $bot->reply('ok');
});
