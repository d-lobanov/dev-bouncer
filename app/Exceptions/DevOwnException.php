<?php

namespace App\Exceptions;

use Exception;
use Throwable;

class DevOwnException extends Exception implements UserVisible
{
    /**
     * {@inheritdoc}
     */
    public function __construct(?string $name = null, int $code = 0, Throwable $previous = null)
    {
        $message = "Dev #$name doesn't belong current user";

        parent::__construct($message, $code, $previous);
    }
}
