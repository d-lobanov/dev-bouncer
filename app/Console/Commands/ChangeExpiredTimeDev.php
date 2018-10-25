<?php

namespace App\Console\Commands;

use App\Dev;
use App\Facades\SkypeFormatter;
use Illuminate\Console\Command;

class ChangeExpiredTimeDev extends Command
{
    /**
     * {@inheritdoc}
     */
    protected $signature = 'dev-bouncer:change-time {name} {expired_minutes} {notified_minutes}';

    /**
     * {@inheritdoc}
     */
    protected $description = 'This one is just for testing. Change expiredAt and notifiedAt for dev.';

    public function handle(): void
    {
        $name = $this->argument('name');
        $dev = Dev::whereName($name)->first();

        if (!$dev) {
            $this->output->error("Dev {$name} does not exist");

            return;
        }

        $ownerId = $dev->owner_skype_id ?? '00001';
        $ownerUsername = $dev->owner_skype_username ?? 'john_doe';
        $expiredMinutes = $this->argument('expired_minutes');
        $expiredAt = now()->addMinutes((int)$expiredMinutes);

        $dev->reserve($ownerId, $ownerUsername, $expiredAt, $dev->comment);

        $notifiedMinutes = $this->argument('notified_minutes');
        $dev->notified_at = now()->subMinute((int)$notifiedMinutes);

        $dev->save();

        $this->output->success("Updated: " . SkypeFormatter::devStatus($dev));
    }
}
