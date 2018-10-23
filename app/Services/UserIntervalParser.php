<?php

namespace App\Services;

use Carbon\Carbon;

class UserIntervalParser
{
    const DAYS_REGEX = '/([1-9][0-9]*)\s?(d|D)/';
    const HOURS_REGEX = '/([1-9][0-9]*)\s?(h|H)/';

    const MINUTES_IN_HOUR = 60;
    const MINUTES_IN_DAY = 24 * 60;

    /**
     * Convert user input to timestamp.
     *
     * @param string $userInput
     * @return int|null
     */
    public function parse(string $userInput): ?int
    {
        $days = self::parseDays($userInput);
        $hours = self::parseHours($userInput);

        if (!$days && !$hours) {
            return null;
        }

        if ($days > 8 || $hours > 23) {
            return null;
        }

        return Carbon::now()->addDays($days ?? 0)->addHours($hours ?? 0)->timestamp;
    }

    /**
     * @param string $userInput
     * @return int|null
     */
    private function parseDays(string $userInput): ?int
    {
        if (preg_match('/(\d+)\s?(d|D)/', $userInput, $matches)) {
            return (int)$matches[1];
        }

        return null;
    }

    /**
     * @param string $userInput
     * @return int|null
     */
    private function parseHours(string $userInput): ?int
    {
        if (preg_match('/(\d+)\s?(h|H)/', $userInput, $matches)) {
            return (int)$matches[1];
        }

        return null;
    }
}
