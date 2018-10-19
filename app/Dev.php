<?php

namespace App;

use DateTime;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $name
 * @property string|null $owner_skype_id
 * @property string|null $comment
 * @property string|null $expired_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static Builder|Dev whereComment($value)
 * @method static Builder|Dev whereCreatedAt($value)
 * @method static Builder|Dev whereExpiredAt($value)
 * @method static Builder|Dev whereId($value)
 * @method static Builder|Dev whereName($value)
 * @method static Builder|Dev whereOwnerSkype($value)
 * @method static Builder|Dev whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Dev extends Model
{
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
     * @return bool
     */
    public function isOccupied(): bool
    {
        return $this->expired_at < now();
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
}
