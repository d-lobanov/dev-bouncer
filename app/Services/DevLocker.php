<?php

namespace App\Services;

use App\Dev;
use App\Exceptions\DevIsOccupiedException;
use App\Exceptions\DevNotFoundException;

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
     * @throws DevNotFoundException|DevIsOccupiedException
     */
    public function lock(string $name, string $ownerId, \DateTime $expiredAt, ?string $comment)
    {
        $dev = Dev::whereName($name);

        if (!$dev) {
            throw new DevNotFoundException($name);
        }

        if ($dev->isOccupied()) {
            throw new DevIsOccupiedException($name);
        }

        return $dev->occupy($ownerId, $expiredAt, $comment);
    }
}
