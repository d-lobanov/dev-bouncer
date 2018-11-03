<?php

namespace App\Services;

use App\Exceptions\IntervalValidationException as ValidationException;
use Carbon\Carbon;

class UserIntervalParser
{
    const REGEX = '/(?:(\d+)\s?(d|h))/i';

    const HOURS_MIN_LIMIT = 1;
    const HOURS_MAX_LIMIT = 48;

    const DEFAULT_TIMEZONE = 'Europe/Minsk';

    /**
     * Convert user input to timestamp.
     *
     * @param string $userInput
     * @return int|null
     * @throws ValidationException
     */
    public function parse(string $userInput): ?int
    {
        if ($userInput === 'till tomorrow') {
            return now()->setTimezone(self::DEFAULT_TIMEZONE)->endOfDay()->timestamp;
        }

        $hours = $this->parseHours($userInput);

        return now()->addHours($hours)->timestamp;
    }

    /**
     * @param string $userInput
     * @return int|null
     * @throws ValidationException
     */
    private function parseHours(string $userInput): ?int
    {
        if (!preg_match_all(self::REGEX, $userInput, $matches, PREG_SET_ORDER)) {
            throw ValidationException::invalidFormat();
        }

        $hours = 0;

        foreach ($matches as [, $value, $type]) {
            if ($value > 0) {
                $hours += strtolower($type) === 'd' ? $value * Carbon::HOURS_PER_DAY : $value;
            }
        }

        if ($hours < self::HOURS_MIN_LIMIT || self::HOURS_MAX_LIMIT < $hours) {
            throw ValidationException::invalidRange(self::HOURS_MIN_LIMIT, self::HOURS_MAX_LIMIT);
        }

        return $hours;
    }
}
