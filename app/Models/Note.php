<?php

namespace App\Models;

use App\Traits\HasUuid;
use Database\Factories\NoteFactory;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Carbon;

/**
 * App\Models\Note
 *
 * @property int $id
 * @property string $uuid
 * @property int $animal_id
 * @property int $user_id
 * @property string $title
 * @property string|null $description
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read \App\Models\Animal $animal
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Category> $categories
 * @property-read int|null $categories_count
 * @property-read \App\Models\User $user
 * @method static \Database\Factories\NoteFactory factory($count = null, $state = [])
 * @method static Builder|Note newModelQuery()
 * @method static Builder|Note newQuery()
 * @method static Builder|Note query()
 * @method static Builder|Note whereAnimalId($value)
 * @method static Builder|Note whereCreatedAt($value)
 * @method static Builder|Note whereDescription($value)
 * @method static Builder|Note whereId($value)
 * @method static Builder|Note whereTitle($value)
 * @method static Builder|Note whereUpdatedAt($value)
 * @method static Builder|Note whereUserId($value)
 * @method static Builder|Note whereUuid($value)
 * @mixin Eloquent
 */
class Note extends Model
{
    use HasFactory;
    use HasUuid;

    protected $fillable = [
        'title',
        'description',

        'animal_id',
        'user_id',
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

    /* Scopes */
}
