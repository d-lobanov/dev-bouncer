<?php

namespace App;

use Carbon\Carbon;
use DateTime;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;


/**
 * App\Dev
 *
 * @property int $id
 * @property string $name
 * @property string|null $owner_skype_id
 * @property string|null $owner_skype_username
 * @property Carbon|null $expired_at
 * @property Carbon|null $notified_at
 * @property string|null $comment
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|Dev whereComment($value)
 * @method static Builder|Dev whereCreatedAt($value)
 * @method static Builder|Dev whereExpiredAt($value)
 * @method static Builder|Dev whereId($value)
 * @method static Builder|Dev whereName($value)
 * @method static Builder|Dev whereNotifiedAt($value)
 * @method static Builder|Dev whereOwnerSkypeId($value)
 * @method static Builder|Dev whereOwnerSkypeUsername($value)
 * @method static Builder|Dev whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Dev extends Model
{
    /**
     * {@inheritdoc}
     */
    protected $dates = [
        'expired_at',
        'notified_at',
    ];

    /**
     * {@inheritdoc}
     */
    protected $fillable = [
        'name',
        'owner_skype_id',
        'owner_skype_username',
        'expired_at',
        'notified_at',
        'comment',
    ];

    /**
     * @return Dev[]|Collection|Builder
     */
    public static function allFree()
    {
        return Dev::where('expired_at', '<', now())
            ->orWhereNull('expired_at')
            ->get();
    }

    /**
     * @return bool
     */
    public function isReserved(): bool
    {
        return $this->expired_at && $this->expired_at > now();
    }

    /**
     * @param string $ownerId
     * @param string $ownerUsername
     * @param DateTime $expiredAt
     * @param string|null $comment
     * @return bool
     */
    public function reserve(string $ownerId, string $ownerUsername, DateTime $expiredAt, ?string $comment = null)
    {
        $this->owner_skype_id = $ownerId;
        $this->owner_skype_username = $ownerUsername;
        $this->expired_at = $expiredAt;
        $this->comment = $comment;
        $this->notified_at = now();

        return $this->save();
    }

    /**
     * @return bool
     */
    public function unlock()
    {
        $this->owner_skype_id = null;
        $this->owner_skype_username = null;
        $this->expired_at = null;
        $this->comment = null;
        $this->notified_at = null;

        return $this->save();
    }

    /**
     * @return bool
     */
    public function notified()
    {
        $this->notified_at = now();

        return $this->save();
    }

}
