<?php

namespace App\Services;

use App\Dev;
use App\Exceptions\DevIsReservedException;
use App\Exceptions\DevIsUnlockedException;
use App\Exceptions\DevNotFoundException;
use App\Exceptions\DevOwnException;
use BotMan\BotMan\Interfaces\UserInterface;
use Carbon\Carbon;

class DevBouncer
{
    /**
     * @param string $name
     * @param string $ownerId
     * @return bool
     * @throws DevNotFoundException|DevOwnException|DevIsUnlockedException
     */
    public function unlockByNameAndOwnerId(string $name, string $ownerId): bool
    {
        /** @var Dev $dev */
        $dev = Dev::whereName($name)->first();

        if (!$dev) {
            throw new DevNotFoundException($name);
        }

        if (!$dev->isReserved()) {
            throw new DevIsUnlockedException($name);
        }

        if ($dev->owner_skype_id !== $ownerId) {
            throw new DevOwnException($name);
        }

        return $dev->unlock();
    }

    /**
     * @param string $name
     * @param UserInterface $owner
     * @param int $expiredAt
     * @param null|string $comment
     *
     * @return bool
     *
     * @throws DevIsReservedException|DevNotFoundException
     */
    public function reserveByName(string $name, UserInterface $owner, int $expiredAt, ?string $comment): bool
    {
        /** @var Dev $dev */
        if ($dev = Dev::whereName($name)->first()) {
            return $this->reserve($dev, $owner, $expiredAt, $comment);
        }

        throw new DevNotFoundException($name);
    }

    /**
     * @param Dev $dev
     * @param UserInterface $owner
     * @param int $expiredAt
     * @param null|string $comment
     * @return bool
     * @throws DevIsReservedException
     */
    private function reserve(Dev $dev, UserInterface $owner, int $expiredAt, ?string $comment)
    {
        if ($dev->isReserved()) {
            throw new DevIsReservedException($dev->name);
        }

        $username = $owner->getUsername() ?? $owner->getId();
        $time = Carbon::createFromTimestamp($expiredAt);

        return $dev->reserve($owner->getId(), $username, $time, $comment);
    }
}
