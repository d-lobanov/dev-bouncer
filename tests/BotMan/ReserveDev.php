<?php

namespace Tests\BotMan;

use App\Dev;
use App\Enum\Emoji;
use Carbon\Carbon;

trait ReserveDev
{
    /**
     * @param string $username
     * @param string $userId
     * @param string $name
     * @param int $hours
     * @param string $comment
     */
    private function reserveDev(string $username, string $userId, string $name, int $hours, string $comment)
    {
        $now = now();
        Carbon::setTestNow($now->copy());

        $this->bot
            ->setUser(['username' => $username, 'id' => $userId])
            ->receives("reserve $name {$hours}h $comment")
            ->assertReply(Emoji::DEV_RESERVED . " #$name has been reserved");

        $this->assertDatabaseHas(Dev::TABLE, [
            'name' => $name,
            'owner_skype_id' => $userId,
            'owner_skype_username' => $username,
            'expired_at' => $now->copy()->addHour($hours),
            'notified_at' => $now,
            'comment' => $comment,
        ]);

        Carbon::setTestNow();
    }
}

