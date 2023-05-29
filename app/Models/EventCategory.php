<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\EventCategory
 *
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|EventCategory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EventCategory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|EventCategory query()
 * @method static \Illuminate\Database\Eloquent\Builder|EventCategory whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EventCategory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|EventCategory whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class EventCategory extends Model
{
    use HasFactory, HasUuid;

    protected $fillable = [
        //
    ];

    /* Relationships */

    /* Scopes */
}
