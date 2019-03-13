<?php

namespace App\Services;

use App\Dev;
use App\Exceptions\DevIsReservedException;
use App\Exceptions\DevIsUnlockedException;
use App\Exceptions\DevNotFoundException;
use App\Exceptions\DevOwnException;
use App\Exceptions\InvalidDevNameException;
use BotMan\BotMan\Interfaces\UserInterface;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Validator;

class DevBouncer
{
    /**
     * @param string $name
     * @param string $ownerId
     * @return bool
     * @throws DevNotFoundException|DevOwnException|DevIsUnlockedException|Exception
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

        return $dev->delete();
    }

    /**
     * @param string $name
     * @param UserInterface $owner
     * @param int $expiredAt
     * @param null|string $comment
     *
     * @return bool
     *
     * @throws DevIsReservedException|DevNotFoundException|InvalidDevNameException
     */
    public function reserveByName(string $name, UserInterface $owner, int $expiredAt, ?string $comment): bool
    {
        $name = DevBouncer::validateAndConvertName($name);

        /** @var Dev $dev */
        $dev = Dev::whereName($name)->first() ?? Dev::create(['name' => $name]);

        return $this->reserve($dev, $owner, $expiredAt, $comment);
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

    /**
     * @param string $name
     * @return string
     * @throws InvalidDevNameException
     */
    private static function validateAndConvertName(string $name)
    {
        $validation = Validator::make(['name' => $name], ['name' => ['regex:/^(dev)?\d+$/', 'max:255']]);

        if ($validation->fails()) {
            throw new InvalidDevNameException($name);
        }

        return starts_with($name, 'dev') ? $name : 'dev' . $name;
    }
}
