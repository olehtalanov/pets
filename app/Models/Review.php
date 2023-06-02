<?php

namespace App\Models;

use App\Traits\HasUuid;
use Database\Factories\ReviewFactory;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Support\Carbon;

/**
 * App\Models\Review
 *
 * @property int $id
 * @property string $uuid
 * @property int $user_id
 * @property int $pin_id
 * @property string|null $message
 * @property int $rating
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Pin $pin
 * @property-read User|null $reviewable
 * @property-read User $reviewer
 *
 * @method static ReviewFactory factory($count = null, $state = [])
 * @method static Builder|Review newModelQuery()
 * @method static Builder|Review newQuery()
 * @method static Builder|Review query()
 * @method static Builder|Review whereCreatedAt($value)
 * @method static Builder|Review whereId($value)
 * @method static Builder|Review whereMessage($value)
 * @method static Builder|Review wherePinId($value)
 * @method static Builder|Review whereRating($value)
 * @method static Builder|Review whereUpdatedAt($value)
 * @method static Builder|Review whereUserId($value)
 * @method static Builder|Review whereUuid($value)
 *
 * @mixin Eloquent
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
