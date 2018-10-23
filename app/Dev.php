<?php

namespace App;

use Carbon\Carbon;
use DateTime;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $name
 * @property string|null $owner_skype_id
 * @property string|null $comment
 * @property Carbon|null $expired_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static Builder|Dev whereComment($value)
 * @method static Builder|Dev whereCreatedAt($value)
 * @method static Builder|Dev whereExpiredAt($value)
 * @method static Builder|Dev whereId($value)
 * @method static Builder|Dev whereName($value)
 * @method static Builder|Dev whereOwnerSkypeId($value)
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
    ];

    /**
     * {@inheritdoc}
     */
    protected $fillable = [
        'name',
        'owner_skype_id',
        'expired_at',
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
    public function isOccupied(): bool
    {
        return $this->expired_at && $this->expired_at > now();
    }

    /**
     * @param string $ownerId
     * @param DateTime $expiredAt
     * @param string $comment
     * @return bool
     */
    public function occupy(string $ownerId, DateTime $expiredAt, string $comment)
    {
        $this->owner_skype_id = $ownerId;
        $this->expired_at = $expiredAt;
        $this->comment = $comment;

        return $this->save();
    }

    /**
     * @return bool
     */
    public function release()
    {
        $this->owner_skype_id = null;
        $this->expired_at = null;
        $this->comment = null;

        return $this->save();
    }

}
