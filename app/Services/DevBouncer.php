<?php

namespace App\Services;

use App\Dev;
use App\Exceptions\DevIsReservedException;
use App\Exceptions\DevNotFoundException;
use BotMan\BotMan\Interfaces\UserInterface;
use Carbon\Carbon;

class DevBouncer
{
    /**
     * @param string $name
     * @return bool
     * @throws DevNotFoundException
     */
    public function unlockByName(string $name): bool
    {
        /** @var Dev $dev */
        if ($dev = Dev::whereName($name)->first()) {
            return $dev->unlock();
        }

        throw new DevNotFoundException($name);
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
