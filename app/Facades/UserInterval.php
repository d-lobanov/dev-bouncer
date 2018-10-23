<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class UserInterval extends Facade
{
    /**
     * {@inheritdoc}
     */
    protected static function getFacadeAccessor(): string
    {
        return 'user_interval_converter';
    }
}
