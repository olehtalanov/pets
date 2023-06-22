<?php

namespace App\Models;

use App\Traits\HasUuid;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * App\Models\Appeal
 *
 * @property int $id
 * @property string $uuid
 * @property int $user_id
 * @property string|null $message
 * @property int|null $rating
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read \App\Models\User $user
 * @method static \Database\Factories\AppealFactory factory($count = null, $state = [])
 * @method static Builder|Appeal newModelQuery()
 * @method static Builder|Appeal newQuery()
 * @method static Builder|Appeal query()
 * @method static Builder|Appeal whereCreatedAt($value)
 * @method static Builder|Appeal whereId($value)
 * @method static Builder|Appeal whereMessage($value)
 * @method static Builder|Appeal whereRating($value)
 * @method static Builder|Appeal whereUpdatedAt($value)
 * @method static Builder|Appeal whereUserId($value)
 * @method static Builder|Appeal whereUuid($value)
 * @mixin Eloquent
 */
class Appeal extends Model
{
    use HasFactory;
    use HasUuid;

    protected $fillable = [
        'message',
        'rating',

        'user_id',
    ];

    /* Relationships */

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /* Scopes */
}
