<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Builder;
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
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\AnimalType|null $type
 *
 * @method static Builder|Breed available()
 * @method static Builder|Breed newModelQuery()
 * @method static Builder|Breed newQuery()
 * @method static Builder|Breed query()
 * @method static Builder|Breed whereAnimalTypeId($value)
 * @method static Builder|Breed whereCreatedAt($value)
 * @method static Builder|Breed whereId($value)
 * @method static Builder|Breed whereName($value)
 * @method static Builder|Breed whereUpdatedAt($value)
 * @method static Builder|Breed whereUuid($value)
 *
 * @mixin \Eloquent
 */
class Breed extends Model
{
    use HasTranslations, HasUuid;

    protected $fillable = [
        'name',
        'is_visible',

        'animal_type_id',
    ];

    protected $casts = [
        'is_visible' => 'boolean',
    ];

    protected $hidden = [
        'animal_type_id',
    ];

    protected $translatable = [
        'name',
    ];

    /* Relationships */

    public function type(): BelongsTo
    {
        return $this->belongsTo(AnimalType::class);
    }

    /* Scopes */

    public function scopeAvailable(Builder $builder): Builder|Breed
    {
        return $builder->where('is_visible', true);
    }
}