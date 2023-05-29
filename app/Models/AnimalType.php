<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Translatable\HasTranslations;

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

    protected $hidden = [
        'id',
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
