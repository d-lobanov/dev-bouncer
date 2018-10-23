<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class DevBouncer extends Facade
{
    /**
     * {@inheritdoc}
     */
    protected static function getFacadeAccessor(): string
    {
        return 'dev_bouncer';
    }
}
