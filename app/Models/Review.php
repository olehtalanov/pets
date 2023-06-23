<?php

namespace App\Models;

use App\Traits\HasUuid;
use App\Traits\UseMedia;
use Database\Factories\ReviewFactory;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Support\Carbon;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

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
 * @property-read MediaCollection<int, Media> $media
 * @property-read int|null $media_count
 * @property-read Pin $pin
 * @property-read User|null $reviewable
 * @property-read User $reviewer
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
 * @mixin Eloquent
 */
class Review extends Model implements HasMedia
{
    use HasFactory;
    use HasUuid;
    use UseMedia;

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
        return $this->hasOneThrough(User::class, Pin::class, 'user_id', 'id', 'id', 'user_id');
    }

    public function pin(): BelongsTo
    {
        return $this->belongsTo(Pin::class);
    }

    /* Media */

    public function registerMediaCollections(): void
    {
        $this
            ->addMediaCollection('gallery')
            ->registerMediaConversions(function (Media $media) {
                $this
                    ->addMediaConversion('thumb')
                    ->fit(Manipulations::FIT_CROP, 80, 80)
                    ->width(80)
                    ->height(80);
            });
    }
}
