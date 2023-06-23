<?php

namespace App\Models;

use Akuechler\Geoly;
use App\Traits\HasUuid;
use App\Traits\UseMedia;
use Database\Factories\PinFactory;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

/**
 * App\Models\Pin
 *
 * @property int $id
 * @property string $uuid
 * @property int $user_id
 * @property int|null $type_id
 * @property float $latitude
 * @property float $longitude
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string $name
 * @property string|null $address
 * @property string|null $contact
 * @property-read MediaCollection<int, Media> $media
 * @property-read int|null $media_count
 * @property-read Collection<int, Review> $reviews
 * @property-read int|null $reviews_count
 * @property-read PinType|null $type
 * @property-read User $user
 * @method static PinFactory factory($count = null, $state = [])
 * @method static Builder|Pin newModelQuery()
 * @method static Builder|Pin newQuery()
 * @method static Builder|Pin query()
 * @method static Builder|Pin radius($latitude, $longitude, $radius)
 * @method static Builder|Pin whereAddress($value)
 * @method static Builder|Pin whereContact($value)
 * @method static Builder|Pin whereCreatedAt($value)
 * @method static Builder|Pin whereId($value)
 * @method static Builder|Pin whereLatitude($value)
 * @method static Builder|Pin whereLongitude($value)
 * @method static Builder|Pin whereName($value)
 * @method static Builder|Pin whereTypeId($value)
 * @method static Builder|Pin whereUpdatedAt($value)
 * @method static Builder|Pin whereUserId($value)
 * @method static Builder|Pin whereUuid($value)
 * @mixin Eloquent
 */
class Pin extends Model implements HasMedia
{
    use HasFactory;
    use HasUuid;
    use UseMedia;
    use Geoly;

    protected $fillable = [
        'name',
        'description',
        'address',
        'contact',
        'latitude',
        'longitude',

        'user_id',
        'type_id',
    ];

    /* Relationships */

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function type(): BelongsTo
    {
        return $this->belongsTo(PinType::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
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
