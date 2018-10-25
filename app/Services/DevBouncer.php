<?php

namespace App\Services;

use App\Dev;
use App\Exceptions\DevIsOccupiedException;
use App\Exceptions\DevNotFoundException;
use BotMan\BotMan\Interfaces\UserInterface;
use Carbon\Carbon;

class DevBouncer
{
    /**
     * @param int $id
     * @param UserInterface $owner
     * @param int $expiredAt
     * @param null|string $comment
     *
     * @return bool
     *
     * @throws DevIsOccupiedException|DevNotFoundException
     */
    public function reserve(int $id, UserInterface $owner, int $expiredAt, ?string $comment): bool
    {
        $dev = Dev::find($id);

        if (!$dev) {
            throw new DevNotFoundException($id);
        }

        if ($dev->isReserved()) {
            throw new DevIsOccupiedException($id);
        }

        $username = $owner->getUsername() ?? $owner->getId();
        $time = Carbon::createFromTimestamp($expiredAt);

        return $dev->reserve($owner->getId(), $username, $time, $comment);
    }

    /**
     * @param int $id
     * @return bool
     * @throws DevNotFoundException
     */
    public function unlock(int $id): bool
    {
        $dev = Dev::find($id);

        if (!$dev) {
            throw new DevNotFoundException($id);
        }

        $dev->owner_skype_id = null;
        $dev->expired_at = null;
        $dev->comment = null;

        return $dev->unlock();
    }
}
