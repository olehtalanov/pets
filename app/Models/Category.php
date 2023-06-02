<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Spatie\Translatable\HasTranslations;

class Category extends Model
{
    use HasFactory, HasUuid, HasTranslations;

    protected $fillable = [
        'name',
        'related_model',

        'parent_id',
    ];

    protected $translatable = [
        'name',
    ];

    /* Relationships */

    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function events(): MorphToMany
    {
        return $this->morphedByMany(Event::class, 'categorable');
    }

    public function notes(): MorphToMany
    {
        return $this->morphedByMany(Event::class, 'categorable');
    }

    /* Scopes */

    public function scopeOnlyParents(Builder $builder, string $model): void
    {
        $builder->whereNull('parent_id')->where('related_model', $model);
    }

    public function scopeOnlyChildren(Builder $builder, string $model): void
    {
        $builder->whereNotNull('parent_id')->where('related_model', $model);
    }
}
