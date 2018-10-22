<?php

namespace App\Exceptions;

use Exception;
use Throwable;

class DevIsOccupiedException extends Exception
{
    /**
     * {@inheritdoc}
     */
    public function __construct(string $name, int $code = 0, Throwable $previous = null)
    {
        $message = sprintf('Dev \'%s\' have been already occupied', $name);

        parent::__construct($message, $code, $previous);
    }
}
