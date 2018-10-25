<?php

namespace App\Exceptions;

interface BotResponsible
{
    /**
     * Return message that should be send to user
     *
     * @return string
     */
    public function responseMessage(): string;
}
