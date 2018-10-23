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
        $this->notifyAndReleaseExpired();
        $this->notify(15);
        $this->notify(60);
    }

    /**
     * Notify user that dev has been expired and release dev.
     */
    private function notifyAndReleaseExpired(): void
    {
        $devs = Dev::where('expired_at', '<', now())->get();

        $devs->each(function (Dev $dev) {
            $message = "{$dev->owner_skype_username} #{$dev->name} has been expired and released";
            $this->skype->say($message, $dev->owner_skype_id);

            $dev->release();
        });
    }

    /**
     * Notify user that dev will be expired soon.
     *
     * @param int $minutes. Minutes before expiration.
     */
    private function notify(int $minutes): void
    {
        $time = now()->addMinutes($minutes);
        $devs = Dev::where('expired_at', '<', $time)->get();

        $devs->each(function (Dev $dev) use ($minutes) {
            $diffMinutes = $dev->notified_at->diffInMinutes();

            if ($diffMinutes > $minutes) {
                $message = "{$dev->owner_skype_username} #{$dev->name} will be expired in {$minutes} minutes";
                $this->skype->say($message, $dev->owner_skype_id);

                $dev->notified();
            }
        });
    }
}
