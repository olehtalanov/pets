<?php

namespace App\Models;

use App\Traits\HasUuid;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Translatable\HasTranslations;

/**
 * App\Models\AnimalType
 *
 * @property int $id
 * @property string $uuid
 * @property array $name
 * @property bool $is_visible
 * @property-read Collection<int, \App\Models\Animal> $animals
 * @property-read int|null $animals_count
 * @property-read Collection<int, \App\Models\Breed> $breeds
 * @property-read int|null $breeds_count
 *
 * @method static \Database\Factories\AnimalTypeFactory factory($count = null, $state = [])
 * @method static Builder|AnimalType newModelQuery()
 * @method static Builder|AnimalType newQuery()
 * @method static Builder|AnimalType onlyVisible()
 * @method static Builder|AnimalType query()
 * @method static Builder|AnimalType whereId($value)
 * @method static Builder|AnimalType whereIsVisible($value)
 * @method static Builder|AnimalType whereName($value)
 * @method static Builder|AnimalType whereUuid($value)
 *
 * @mixin Eloquent
 */
class AnimalType extends Model
{
    use HasFactory, HasTranslations, HasUuid;

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

    public function animals(): HasMany
    {
        return $this->hasMany(Animal::class);
    }

    public function breeds(): HasMany
    {
        return $this->hasMany(Breed::class);
    }

    /* Scopes */

    public function scopeOnlyVisible(Builder $builder): void
    {
        $builder->where('is_visible', true);
    }
}
