<?php

namespace App\Models;

use App\Traits\HasUuid;
use Database\Factories\PinFactory;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
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
 * @property-read PinType|null $type
 *
 * @method static PinFactory factory($count = null, $state = [])
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
    use HasFactory, HasUuid;

    protected $fillable = [
        'latitude',
        'longitude',

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
