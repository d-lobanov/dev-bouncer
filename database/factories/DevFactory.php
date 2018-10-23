<?php

use App\Dev;
use Faker\Generator as Faker;
use Illuminate\Support\Carbon;

$factory->define(Dev::class, function (Faker $faker) {
    return [
        'name' => $faker->word,
        'owner_skype_id' => $faker->uuid,
        'owner_skype_username' => $faker->userName,
        'expired_at' => Carbon::instance($faker->dateTime),
        'notified_at' => Carbon::instance($faker->dateTime),
        'comment' => $faker->title,
    ];
});
