<?php

use App\Http\Middleware\Bot\TrimMessage;
use App\Http\Controllers\BotManController;
use App\Http\Middleware\Bot\RemoveBotNickname;
use BotMan\BotMan\BotMan;
use Illuminate\Support\Carbon;

/** @var BotMan $botman */
$botman = resolve('botman');

$botman->middleware->received(new RemoveBotNickname(), new TrimMessage());

$botman->hears('hi', function (BotMan $bot) {
    $bot->reply($bot->getMessage()->getText());
});

$botman->hears('occupy|lock|take', BotManController::class . '@occupy');
$botman->hears('status', BotManController::class . '@status');
$botman->hears('release|unlock|give', BotManController::class . '@release');

$botman->hears('test_change {name} {eMinutes} {nMinutes}', function (BotMan $bot, $name, $eMinutes, $nMinutes) {
    $dev = \App\Dev::whereName($name)->first();

    $dev->occupy(
        $bot->getUser()->getId(),
        $bot->getUser()->getUsername(),
        now()->addMinutes((int)$eMinutes),
        null
    );

    $dev->notified_at = now()->subMinute((int)$nMinutes);
    $dev->save();

    $bot->reply('ok');
});

$botman->fallback(function (BotMan $bot) {
    $bot->randomReply([
        'Sorry, I did not understand these commands.',
        'I did not get it',
    ]);
});
