<?php

namespace App\Exceptions;

use Exception;
use Throwable;

class InvalidDevNameException extends Exception implements UserVisible
{
    /**
     * {@inheritdoc}
     */
    public function __construct(string $name, int $code = 0, Throwable $previous = null)
    {
        $message = "'$name' is not valid name for dev. Try something like – dev123";

        parent::__construct($message, $code, $previous);
    }
}
