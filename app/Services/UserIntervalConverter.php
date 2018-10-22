<?php

namespace App\Services;

use Carbon\Carbon;

class UserIntervalConverter
{
    const DAYS_REGEX = '/([1-9][0-9]*)\s?(d|D)/';
    const HOURS_REGEX = '/([1-9][0-9]*)\s?(h|H)/';

    /**
     * @param string $userInput
     * @return Carbon|null
     */
    public function convert(string $userInput): ?Carbon
    {
        $days = $this->parseDays($userInput);
        $hours = $this->parseHours($userInput);

        if (!$days && !$hours) {
            return null;
        }

        if ($days > 8 || $hours > 23) {
            return null;
        }

        return Carbon::now()->addDays($days)->addHours($hours);
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
