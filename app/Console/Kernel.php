<?php

namespace App\Console;

use BotMan\BotMan\BotMan;
use BotMan\Drivers\BotFramework\BotFrameworkDriver as SkypeDriver;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->call(function () {
            /** @var BotMan $bot */
            $bot = app('botman');

            $bot->say('<3',
                '19:11c423587aad4bbdb4a3f272a3876ac2@thread.skype',
                SkypeDriver::class,
                ['serviceUrl' => 'https://smba.trafficmanager.net/apis/']
            );
        })->everyMinute();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
