<?php

namespace App\Console\Commands;

use App\Dev;
use App\Services\SkypeBotMan;
use App\Services\SkypeMessageFormatter;
use Illuminate\Console\Command;

class CheckExpiringDevs extends Command
{
    /**
     * {@inheritdoc}
     */
    protected $signature = 'dev-bouncer:check';

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
     * @var SkypeMessageFormatter
     */
    private $formatter;

    /**
     * @param SkypeBotMan $skype
     * @param SkypeMessageFormatter $formatter
     */
    public function __construct(SkypeBotMan $skype, SkypeMessageFormatter $formatter)
    {
        parent::__construct();

        $this->skype = $skype;
        $this->formatter = $formatter;
    }

    /**
     * Execute the command.
     */
    public function handle(): void
    {
        $this->notifyAndUnlockExpired();
        $this->notify(15);
        $this->notify(60);
    }

    /**
     * Notify user that dev has been expired and unlock dev.
     */
    private function notifyAndUnlockExpired(): void
    {
        $devs = Dev::where('expired_at', '<', now())->get();

        $devs->each(function (Dev $dev) {
            $message = "(bomb) {$dev->owner_skype_username} #{$dev->name} has been expired and unlocked";
            $this->skype->say($message, $dev->owner_skype_id);

            $dev->unlock();
        });
    }

    /**
     * Notify user that dev will be expired soon.
     *
     * @param int $minutes . Minutes before expiration.
     */
    private function notify(int $minutes): void
    {
        $time = now()->addMinutes($minutes);
        $devs = Dev::where('expired_at', '<', $time)->get();

        $devs->each(function (Dev $dev) use ($minutes) {
            $diffMinutes = $dev->notified_at->diffInMinutes();

            if ($diffMinutes > $minutes) {
                $time = $this->formatter->formatDateDiff($dev->expired_at);

                $message = "⚠️ {$dev->owner_skype_username} #{$dev->name} will be expired in {$time}";
                $this->skype->say($message, $dev->owner_skype_id);

                $dev->notified();
            }
        });
    }
}
