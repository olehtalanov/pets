<?php

namespace App\Models;

use App\Enums\Animal\EventRepeatSchemeEnum;
use App\Traits\HasUuid;
use Database\Factories\EventFactory;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * App\Models\Event
 *
 * @property int $id
 * @property string $uuid
 * @property int|null $original_id
 * @property int $animal_id
 * @property int $user_id
 * @property string $title
 * @property string|null $description
 * @property Carbon|null $starts_at
 * @property Carbon|null $ends_at
 * @property EventRepeatSchemeEnum $repeat_scheme
 * @property bool $whole_day
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property bool $processable
 * @property-read \App\Models\Animal $animal
 * @property-read Collection<int, \App\Models\Category> $categories
 * @property-read int|null $categories_count
 * @property-read Collection<int, Event> $children
 * @property-read int|null $children_count
 * @property-read Event|null $parent
 * @property-read \App\Models\User $user
 * @method static Builder|Event actual()
 * @method static \Database\Factories\EventFactory factory($count = null, $state = [])
 * @method static Builder|Event newModelQuery()
 * @method static Builder|Event newQuery()
 * @method static Builder|Event query()
 * @method static Builder|Event today()
 * @method static Builder|Event whereAnimalId($value)
 * @method static Builder|Event whereCreatedAt($value)
 * @method static Builder|Event whereDescription($value)
 * @method static Builder|Event whereEndsAt($value)
 * @method static Builder|Event whereId($value)
 * @method static Builder|Event whereOriginalId($value)
 * @method static Builder|Event whereProcessable($value)
 * @method static Builder|Event whereRepeatScheme($value)
 * @method static Builder|Event whereStartsAt($value)
 * @method static Builder|Event whereTitle($value)
 * @method static Builder|Event whereUpdatedAt($value)
 * @method static Builder|Event whereUserId($value)
 * @method static Builder|Event whereUuid($value)
 * @method static Builder|Event whereWholeDay($value)
 * @mixin Eloquent
 */
class Event extends Model
{
    use HasFactory;
    use HasUuid;

    protected $fillable = [
        'title',
        'description',
        'starts_at',
        'ends_at',
        'repeat_scheme',
        'whole_day',
        'processable',

        'original_id',
        'animal_id',
        'user_id',
    ];

    protected $casts = [
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'repeat_scheme' => EventRepeatSchemeEnum::class,
        'whole_day' => 'boolean',
        'processable' => 'boolean',
    ];

    /* Relationships */

    public function animal(): BelongsTo
    {
        return $this->belongsTo(Animal::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'categorables');
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'original_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'original_id');
    }

    /* Scopes */

    public function scopeActual(Builder $builder): void
    {
        $builder->where('ends_at', '>=', now());
    }

    public function scopeToday(Builder $builder): void
    {
        $builder->whereBetween('ends_at', [
            today(),
            today()->endOfDay(),
        ]);
    }
}
