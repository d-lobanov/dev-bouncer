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

$botman->hears('take {name} for {time}', BotManController::class . '@take');

$botman->fallback(function ($bot) {
    $bot->reply('Sorry, I did not understand these commands.');
});
