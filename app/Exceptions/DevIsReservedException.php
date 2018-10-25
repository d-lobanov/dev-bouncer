<?php

namespace App\Exceptions;

use Exception;
use Throwable;

class DevIsReservedException extends Exception implements BotResponsible
{
    /**
     * {@inheritdoc}
     */
    public function __construct(string $name, int $code = 0, Throwable $previous = null)
    {
        $message = sprintf('Dev \'%s\' have already been reserved', $name);

        parent::__construct($message, $code, $previous);
    }

    /**
     * {@inheritdoc}
     */
    public function responseMessage(): string
    {
        return $this->message;
    }
}
