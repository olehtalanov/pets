<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\PinType
 *
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|PinType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PinType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PinType query()
 * @method static \Illuminate\Database\Eloquent\Builder|PinType whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PinType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PinType whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class PinType extends Model
{
    use HasFactory, HasUuid;

    protected $fillable = [
        //
    ];

    protected $hidden = [
        'id',
    ];

    /* Relationships */

    /* Scopes */
}
