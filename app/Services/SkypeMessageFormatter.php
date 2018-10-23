<?php

namespace App\Services;

use App\Dev;

class SkypeMessageFormatter
{
    const SKYPE_NEW_LINE = "\n\n";

    /**
     * @param string $text
     * @return string
     */
    public function bold(string $text): string
    {
        return "**$text**";
    }

    /**
     * @param Dev $dev
     * @return string
     */
    public function devStatus(Dev $dev): string
    {
        $name = $this->bold($dev->name);

        if (!$dev->isOccupied()) {
            return "$name – free";
        }

        $time = $dev->expired_at->diffForHumans(null, true);
        $comment = $dev->comment ? "\"{$dev->comment}\"" : '';

        return "$name – {$dev->owner_skype_username} for {$time} {$comment}";
    }
}
