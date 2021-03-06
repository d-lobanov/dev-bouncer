<?php

namespace App\Services;

use App\Dev;
use Carbon\Carbon;

class SkypeMessageFormatter
{
    const SKYPE_NEW_LINE = "\n\n";
    const SKYPE_INVISIBLE_LINE = "‌‌‌‌ " . self::SKYPE_NEW_LINE;

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

        if (!$dev->isReserved()) {
            return "$name – free";
        }

        $time = $this->formatDateDiff($dev->expired_at);
        $comment = $dev->comment ? "\"{$dev->comment}\"" : '';

        return "$name – {$dev->owner_skype_username} for {$time} {$comment}";
    }

    /**
     * @param Carbon $datetime
     *
     * @return string
     */
    public function formatDateDiff(Carbon $datetime): string
    {
        $parts = $datetime->diffInHours() < 1 ? 1 : 2;

        return $datetime->diffForHumans(null, true, true, $parts);
    }
}
