<?php

namespace App\Exceptions;

use Exception;

class IntervalValidationException extends Exception implements UserVisible
{
    /**
     * @param null|string $message
     * @return IntervalValidationException
     */
    public static function invalidFormat(?string $message = null)
    {
        return new self($message ?? "Invalid interval format. Should be for example: 4h");
    }

    /**
     * @param $min
     * @param $max
     * @return IntervalValidationException
     */
    public static function invalidRange($min, $max): self
    {
        return new self("Invalid interval range. Should be: {$min}h <= interval <= {$max}h");
    }
}
