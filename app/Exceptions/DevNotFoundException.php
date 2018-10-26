<?php

namespace App\Exceptions;

use Exception;
use Throwable;

class DevNotFoundException extends Exception implements UserVisible
{
    /**
     * {@inheritdoc}
     */
    public function __construct(?string $name = null, int $code = 0, Throwable $previous = null)
    {
        $message = $name ? "#$name doesn't exist" : 'Dev doesn\'t exist';

        parent::__construct($message, $code, $previous);
    }
}
