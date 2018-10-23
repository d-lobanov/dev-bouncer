<?php

namespace App\Services;

use App\Dev;

class DevMessageFormatter
{
    /**
     * @param Dev $dev
     * @return string
     */
    public function toMessage(Dev $dev): string
    {
        $message = "**{$dev->name}**";

        if (!$dev->isOccupied()) {
            return $message . ' – free';
        }

        return $message . ' – ' . $dev->expired_at->diffForHumans(null, true) . ' – ' . $dev->owner_skype_username;
    }
}
