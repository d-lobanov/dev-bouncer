<?php

use App\Http\Middleware\Bot\TrimMessage;
use App\Http\Controllers\BotManController;
use App\Http\Middleware\Bot\RemoveBotNickname;
use BotMan\BotMan\BotMan;

/** @var BotMan $botman */
$botman = resolve('botman');

$botman->middleware->received(new RemoveBotNickname(), new TrimMessage());

$botman->hears('hi', function (BotMan $bot) {
    $bot->reply($bot->getMessage()->getText());
});

$botman->hears('occupy|lock|take', BotManController::class . '@occupy');
$botman->hears('status', BotManController::class . '@status');
$botman->hears('release|unlock|give', BotManController::class . '@release');

$botman->fallback(function (BotMan $bot) {
    $bot->randomReply([
        'Sorry, I did not understand these commands.',
        'I did not get it',
    ]);
});
