<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Translatable\HasTranslations;

class Breed extends Model
{
    use HasTranslations, HasUuid;

    protected $fillable = [
        'name',
        'is_visible',

        'animal_type_id',
    ];

    protected $casts = [
        'is_visible' => 'boolean'
    ];

    protected $hidden = [
        'id',
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
