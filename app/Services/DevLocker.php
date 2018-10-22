<?php

namespace App\Services;

use App\Dev;
use App\Exceptions\DevIsOccupiedException;
use App\Exceptions\DevNotFoundException;
use BotMan\BotMan\Interfaces\UserInterface;
use Carbon\Carbon;

class DevLocker
{
    /**
     * @param int $id
     * @param UserInterface $owner
     * @param int $expiredAt
     * @param null|string $comment
     *
     * @return bool
     *
     * @throws DevIsOccupiedException
     * @throws DevNotFoundException
     */
    public function lock(int $id, UserInterface $owner, int $expiredAt, ?string $comment)
    {
        $dev = Dev::find($id);

        if (!$dev) {
            throw new DevNotFoundException($id);
        }

        if ($dev->isOccupied()) {
            throw new DevIsOccupiedException($id);
        }

        return $dev->occupy($owner->getId(), Carbon::createFromTimestamp($expiredAt), $comment);
    }
}
