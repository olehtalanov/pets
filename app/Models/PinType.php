<?php

namespace App\Models;

use App\Traits\HasUuid;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Spatie\Translatable\HasTranslations;

/**
 * App\Models\PinType
 *
 * @property int $id
 * @property string $uuid
 * @property array $name
 * @property bool $is_visible
 * @property-read Collection<int, \App\Models\Pin> $pins
 * @property-read int|null $pins_count
 * @property-read Collection<int, \App\Models\User> $users
 * @property-read int|null $users_count
 * @method static \Database\Factories\PinTypeFactory factory($count = null, $state = [])
 * @method static Builder|PinType newModelQuery()
 * @method static Builder|PinType newQuery()
 * @method static Builder|PinType onlyVisible()
 * @method static Builder|PinType query()
 * @method static Builder|PinType whereId($value)
 * @method static Builder|PinType whereIsVisible($value)
 * @method static Builder|PinType whereName($value)
 * @method static Builder|PinType whereUuid($value)
 * @mixin Eloquent
 */
class PinType extends Model
{
    use HasFactory;
    use HasUuid;
    use HasTranslations;

    public $timestamps = false;

    protected $fillable = [
        'name',
        'is_visible',
    ];

    protected $casts = [
        'is_visible' => 'boolean',
    ];

    protected $translatable = [
        'name',
    ];

    /* Relationships */

    public function pins(): HasMany
    {
        return $this->hasMany(Pin::class, 'type_id');
    }

    public function users(): HasManyThrough
    {
        return $this->hasManyThrough(User::class, Pin::class, 'type_id', 'id', 'id', 'type_id');
    }

    /* Scopes */

    public function scopeOnlyVisible(Builder $builder): void
    {
        $builder->where('is_visible', true);
    }
}
