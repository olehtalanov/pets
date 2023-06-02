<?php

namespace App\Models;

use App\Enums\User\EventRepeatSchemeEnum;
use App\Traits\HasUuid;
use Database\Factories\EventFactory;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Support\Carbon;

/**
 * App\Models\Event
 *
 * @property int $id
 * @property string $uuid
 * @property int $animal_id
 * @property int $user_id
 * @property string $title
 * @property string|null $description
 * @property Carbon|null $starts_at
 * @property Carbon|null $ends_at
 * @property EventRepeatSchemeEnum $repeat_scheme
 * @property bool $all_day
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Animal $animal
 * @property-read Category|null $category
 * @property-read User $user
 *
 * @method static EventFactory factory($count = null, $state = [])
 * @method static Builder|Event newModelQuery()
 * @method static Builder|Event newQuery()
 * @method static Builder|Event query()
 * @method static Builder|Event whereAllDay($value)
 * @method static Builder|Event whereAnimalId($value)
 * @method static Builder|Event whereCreatedAt($value)
 * @method static Builder|Event whereDescription($value)
 * @method static Builder|Event whereEndsAt($value)
 * @method static Builder|Event whereId($value)
 * @method static Builder|Event whereRepeatScheme($value)
 * @method static Builder|Event whereStartsAt($value)
 * @method static Builder|Event whereTitle($value)
 * @method static Builder|Event whereUpdatedAt($value)
 * @method static Builder|Event whereUserId($value)
 * @method static Builder|Event whereUuid($value)
 *
 * @mixin Eloquent
 */
class Event extends Model
{
    use HasFactory, HasUuid;

    protected $fillable = [
        'title',
        'description',
        'starts_at',
        'ends_at',
        'repeat_scheme',
        'all_day',

        'animal_id',
        'user_id',
    ];

    protected $casts = [
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'repeat_scheme' => EventRepeatSchemeEnum::class,
        'all_day' => 'boolean',
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

    public function category(): MorphOne
    {
        return $this->morphOne(Category::class, 'categorable');
    }

    /* Scopes */
}
