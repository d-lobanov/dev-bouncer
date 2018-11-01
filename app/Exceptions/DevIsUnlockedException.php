<?php

namespace App\Exceptions;

use Exception;
use Throwable;

class DevIsUnlockedException extends Exception implements UserVisible
{
    /**
     * {@inheritdoc}
     */
    public function __construct(string $name, int $code = 0, Throwable $previous = null)
    {
        $message = "#$name have already been unlocked";

        parent::__construct($message, $code, $previous);
    }
}
