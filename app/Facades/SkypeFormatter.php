<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class SkypeFormatter extends Facade
{
    /**
     * {@inheritdoc}
     */
    protected static function getFacadeAccessor(): string
    {
        return 'skype_formatter';
    }
}
