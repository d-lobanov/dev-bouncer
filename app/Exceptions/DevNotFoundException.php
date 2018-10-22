<?php

namespace App\Exceptions;

use Exception;
use Throwable;

class DevNotFoundException extends Exception
{
    /**
     * {@inheritdoc}
     */
    public function __construct(int $id, int $code = 0, Throwable $previous = null)
    {
        $message = sprintf('Dev %s doesn\'t exist', $id);

        parent::__construct($message, $code, $previous);
    }
}
