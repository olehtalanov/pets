<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

/**
 * App\Models\Review
 *
 * @property int $id
 * @property string $uuid
 * @property int $user_id
 * @property int $pin_id
 * @property string|null $message
 * @property int $rating
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Pin $pin
 * @property-read \App\Models\User|null $reviewable
 * @property-read \App\Models\User $reviewer
 *
 * @method static \Database\Factories\ReviewFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Review newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Review newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Review query()
 * @method static \Illuminate\Database\Eloquent\Builder|Review whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Review whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Review whereMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Review wherePinId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Review whereRating($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Review whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Review whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Review whereUuid($value)
 *
 * @mixin \Eloquent
 */
class Review extends Model
{
    use HasFactory, HasUuid;

    protected $fillable = [
        'message',
        'rating',

        'user_id',
        'pin_id',
    ];

    protected $hidden = [
        'user_id',
        'pin_id',
    ];

    /* Relationships */

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function reviewable(): HasOneThrough
    {
        return $this->hasOneThrough(User::class, Pin::class);
    }

    public function pin(): BelongsTo
    {
        return $this->belongsTo(Pin::class);
    }

    /* Scopes */
}
