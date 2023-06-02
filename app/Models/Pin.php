<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\Pin
 *
 * @property int $id
 * @property string $uuid
 * @property int $user_id
 * @property int|null $type_id
 * @property float $latitude
 * @property float $longitude
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\PinType|null $type
 *
 * @method static \Database\Factories\PinFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Pin newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Pin newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Pin query()
 * @method static \Illuminate\Database\Eloquent\Builder|Pin whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pin whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pin whereLatitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pin whereLongitude($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pin whereTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pin whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pin whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Pin whereUuid($value)
 *
 * @mixin \Eloquent
 */
class Pin extends Model
{
    use HasFactory, HasUuid;

    protected $fillable = [
        'latitude',
        'longitude',

        'user_id',
        'type_id',
    ];

    protected $hidden = [
        'user_id',
        'type_id',
    ];

    /* Relationships */

    public function type(): BelongsTo
    {
        return $this->belongsTo(PinType::class);
    }

    /* Scopes */
}
