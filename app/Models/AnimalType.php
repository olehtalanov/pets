<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Translatable\HasTranslations;

/**
 * App\Models\AnimalType
 *
 * @property int $id
 * @property array $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Animal> $animals
 * @property-read int|null $animals_count
 * @method static Builder|AnimalType available()
 * @method static Builder|AnimalType newModelQuery()
 * @method static Builder|AnimalType newQuery()
 * @method static Builder|AnimalType query()
 * @method static Builder|AnimalType whereCreatedAt($value)
 * @method static Builder|AnimalType whereId($value)
 * @method static Builder|AnimalType whereName($value)
 * @method static Builder|AnimalType whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class AnimalType extends Model
{
    use HasTranslations, HasUuid;

    protected $fillable = [
        'name',
        'is_visible',
    ];

    protected $casts = [
        'is_visible' => 'boolean'
    ];

    protected $translatable = [
        'name',
    ];

    /* Relationships */

    public function animals(): HasMany
    {
        return $this->hasMany(Animal::class);
    }

    /* Scopes */

    public function scopeAvailable(Builder $builder): Builder|AnimalType
    {
        return $builder->where('is_visible', true);
    }
}
