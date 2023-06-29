<?php

namespace App\Models;

use App\Traits\HasUuid;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Translatable\HasTranslations;

/**
 * App\Models\Breed
 *
 * @property int $id
 * @property string $uuid
 * @property int $animal_type_id
 * @property array $name
 * @property bool $is_visible
 * @property-read \App\Models\AnimalType $type
 * @method static \Database\Factories\BreedFactory factory($count = null, $state = [])
 * @method static Builder|Breed newModelQuery()
 * @method static Builder|Breed newQuery()
 * @method static Builder|Breed onlyVisible()
 * @method static Builder|Breed query()
 * @method static Builder|Breed whereAnimalTypeId($value)
 * @method static Builder|Breed whereId($value)
 * @method static Builder|Breed whereIsVisible($value)
 * @method static Builder|Breed whereLocale(string $column, string $locale)
 * @method static Builder|Breed whereLocales(string $column, array $locales)
 * @method static Builder|Breed whereName($value)
 * @method static Builder|Breed whereUuid($value)
 * @mixin Eloquent
 */
class Breed extends Model
{
    use HasFactory;
    use HasTranslations;
    use HasUuid;

    public $timestamps = false;

    protected $fillable = [
        'name',
        'is_visible',

        'animal_type_id',
    ];

    protected $casts = [
        'is_visible' => 'boolean',
    ];

    protected $translatable = [
        'name',
    ];

    /* Relationships */

    public function type(): BelongsTo
    {
        return $this->belongsTo(AnimalType::class, 'animal_type_id');
    }

    /* Scopes */

    public function scopeOnlyVisible(Builder $builder): Builder|Breed
    {
        return $builder->where('is_visible', true);
    }
}
