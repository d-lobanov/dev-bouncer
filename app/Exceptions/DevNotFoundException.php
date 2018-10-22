<?php

namespace App\Exceptions;

use Exception;
use Throwable;

class DevNotFoundException extends Exception
{
    /**
     * {@inheritdoc}
     */
    public function __construct(string $name, int $code = 0, Throwable $previous = null)
    {
        $message = sprintf('Dev %s doesn\'t exist', $name);

        parent::__construct($message, $code, $previous);
    }
}
