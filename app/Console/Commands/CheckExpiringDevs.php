<?php

namespace App\Console\Commands;

use App\Dev;
use App\Services\SkypeBotMan;
use Illuminate\Console\Command;

class CheckExpiringDevs extends Command
{
    /**
     * {@inheritdoc}
     */
    protected $signature = 'dev-bot:check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Notify owners if dev will be expired soon';

    /**
     * @var SkypeBotMan
     */
    private $skype;

    /**
     * @param SkypeBotMan $skype
     */
    public function __construct(SkypeBotMan $skype)
    {
        parent::__construct();

        $this->skype = $skype;
    }

    /**
     * Execute the command.
     */
    public function handle(): void
    {
        $this->skype->say('123', '29:13rADbPHr2Ven7MTrrk4tXKKCmugb9Aral9eM-epgddw');

//        $this->notifyAndReleaseExpired();
//        $this->notifyExpiringInFiftyMinutes();
//        $this->notifyExpiringInHour();
    }

    private function notifyAndReleaseExpired(): void
    {
        $devs = Dev::where('expired_at', '<', now())->get();

        $devs->each(function (Dev $dev) {
            $message = "{$dev->owner_skype_username} #{$dev->name} has been expired and released";
            $this->skype->say($message, $dev->owner_skype_id);

            $dev->release();
        });
    }

    private function notifyExpiringInFiftyMinutes(): void
    {
        $devs = Dev::where('expired_at', '<', now()->addMinutes(15))->get();

        $devs->each(function (Dev $dev) {
            $diffMinutes = $dev->notified_at->diff($dev->expired_at)->i;

            if ($diffMinutes > 15) {
                $message = "{$dev->owner_skype_username} #{$dev->name} will be expired in 15 minutes";
                $this->skype->say($message, $dev->owner_skype_id);

                $dev->notified();
            }
        });
    }

    private function notifyExpiringInHour(): void
    {
        $devs = Dev::where('expired_at', '<', now()->addHour())->get();

        $devs->each(function (Dev $dev) {
            $diffMinutes = $dev->notified_at->diff($dev->expired_at)->i;

            if ($diffMinutes > 60) {
                $message = "{$dev->owner_skype_username} #{$dev->name} will be expired in 1 hour";
                $this->skype->say($message, $dev->owner_skype_id);

                $dev->notified();
            }
        });
    }
}
