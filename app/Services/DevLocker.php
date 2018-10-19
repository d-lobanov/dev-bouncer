<?php

namespace App\Services;

use App\Dev;
use App\Exceptions\LockException;

class DevLocker
{
    /**
     * @param string $name
     * @param string $ownerId
     * @param \DateTime $expiredAt
     * @param null|string $comment
     *
     * @return bool
     *
     * @throws \Exception
     */
    public function lock(string $name, string $ownerId, \DateTime $expiredAt, ?string $comment)
    {
        $status = Dev::whereName($name);

        if (!$status) {
            throw new LockException(LockException::DEV_NOT_EXISTS);
        }

        if ($status->isOccupied()) {
            throw new LockException(LockException::DEV_IS_OCCUPIED);
        }

        return $status->occupy($ownerId, $expiredAt, $comment);
    }
}
