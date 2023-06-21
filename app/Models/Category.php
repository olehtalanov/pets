<?php

namespace App\Models;

use App\Traits\HasUuid;
use Database\Factories\CategoryFactory;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use Spatie\Translatable\HasTranslations;

/**
 * App\Models\Category
 *
 * @property int $id
 * @property string $uuid
 * @property array $name
 * @property string|null $related_model
 * @property int|null $parent_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection<int, Category> $children
 * @property-read int|null $children_count
 * @property-read Collection<int, Event> $events
 * @property-read int|null $events_count
 * @property-read Collection<int, Event> $notes
 * @property-read int|null $notes_count
 * @property-read Category|null $parent
 *
 * @method static CategoryFactory factory($count = null, $state = [])
 * @method static Builder|Category newModelQuery()
 * @method static Builder|Category newQuery()
 * @method static Builder|Category onlyChildren(string $model)
 * @method static Builder|Category onlyParents(?string $model = null)
 * @method static Builder|Category query()
 * @method static Builder|Category whereCreatedAt($value)
 * @method static Builder|Category whereId($value)
 * @method static Builder|Category whereName($value)
 * @method static Builder|Category whereParentId($value)
 * @method static Builder|Category whereRelatedModel($value)
 * @method static Builder|Category whereUpdatedAt($value)
 * @method static Builder|Category whereUuid($value)
 *
 * @mixin Eloquent
 */
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

    public function events(): BelongsToMany
    {
        return $this->belongsToMany(Event::class, 'categorables');
    }

    public function notes(): BelongsToMany
    {
        return $this->belongsToMany(Event::class, 'categorables');
    }

    /* Scopes */

    public function scopeOnlyParents(Builder $builder, ?string $model = null): void
    {
        $builder
            ->whereNull('parent_id')
            ->when(
                $model,
                fn(Builder $builder, string $model) => $builder->where('related_model', $model),
                fn(Builder $builder) => $builder->whereNull('related_model')
            );
    }

    public function scopeOnlyChildren(Builder $builder, ?string $model = null): void
    {
        $builder
            ->whereNotNull('parent_id')
            ->when($model, fn($builder, string $model) => $builder->where('related_model', $model));
    }

    protected static function booted(): void
    {
        parent::booted();

        static::saving(static function (Category $model) {
            if ($model->parent_id) {
                $model->related_model = $model->parent->related_model;
            }
        });
    }
}
