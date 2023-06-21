<?php

namespace App\Models;

use App\Traits\HasUuid;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

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
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Review> $reviews
 * @property-read int|null $reviews_count
 * @property-read \App\Models\PinType|null $type
 * @property-read \App\Models\User $user
 *
 * @method static \Database\Factories\PinFactory factory($count = null, $state = [])
 * @method static Builder|Pin newModelQuery()
 * @method static Builder|Pin newQuery()
 * @method static Builder|Pin query()
 * @method static Builder|Pin whereCreatedAt($value)
 * @method static Builder|Pin whereId($value)
 * @method static Builder|Pin whereLatitude($value)
 * @method static Builder|Pin whereLongitude($value)
 * @method static Builder|Pin whereTypeId($value)
 * @method static Builder|Pin whereUpdatedAt($value)
 * @method static Builder|Pin whereUserId($value)
 * @method static Builder|Pin whereUuid($value)
 *
 * @mixin Eloquent
 */
class Pin extends Model
{
    use HasFactory;
    use HasUuid;

    protected $fillable = [
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

    /* Scopes */
}
