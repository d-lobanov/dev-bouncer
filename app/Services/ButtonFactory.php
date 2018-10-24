<?php

namespace App\Services;

use BotMan\BotMan\Messages\Outgoing\Actions\Button;

class ButtonFactory
{
    /**
     * @return Button
     */
    public static function cancel(): Button
    {
        return Button::create('cancel')->value('cancel');
    }
}
