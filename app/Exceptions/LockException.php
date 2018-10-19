<?php

namespace App\Exceptions;

use Exception;

class LockException extends Exception
{
    const DEV_NOT_EXISTS = 'Dev doesn\'t exist';
    const DEV_IS_OCCUPIED = 'Dev have been already occupied';
}
