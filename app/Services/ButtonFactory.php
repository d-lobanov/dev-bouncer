<?php

namespace App\Services;

use BotMan\BotMan\Messages\Outgoing\Actions\Button;

class ButtonFactory
{
    const CANCEL_VALUE = '~@cancel@~';

    /**
     * @return Button
     */
    public static function cancel(): Button
    {
        return Button::create('cancel')->value(self::CANCEL_VALUE);
    }
}
