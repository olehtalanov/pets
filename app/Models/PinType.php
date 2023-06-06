<?php

namespace App\Models;

use App\Traits\HasUuid;
use Database\Factories\PinTypeFactory;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Support\Carbon;
use Spatie\Translatable\HasTranslations;

/**
 * App\Models\PinType
 *
 * @property int $id
 * @property array $name
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection<int, Pin> $pins
 * @property-read int|null $pins_count
 *
 * @method static PinTypeFactory factory($count = null, $state = [])
 * @method static Builder|PinType newModelQuery()
 * @method static Builder|PinType newQuery()
 * @method static Builder|PinType onlyVisible()
 * @method static Builder|PinType query()
 * @method static Builder|PinType whereCreatedAt($value)
 * @method static Builder|PinType whereId($value)
 * @method static Builder|PinType whereName($value)
 * @method static Builder|PinType whereUpdatedAt($value)
 *
 * @mixin Eloquent
 */
class PinType extends Model
{
    use HasFactory, HasUuid, HasTranslations;

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
