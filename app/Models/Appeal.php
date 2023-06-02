<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\Appeal
 *
 * @property int $id
 * @property string $uuid
 * @property int $user_id
 * @property string|null $message
 * @property int|null $rating
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $user
 *
 * @method static \Database\Factories\AppealFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Appeal newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Appeal newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Appeal query()
 * @method static \Illuminate\Database\Eloquent\Builder|Appeal whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Appeal whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Appeal whereMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Appeal whereRating($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Appeal whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Appeal whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Appeal whereUuid($value)
 *
 * @mixin \Eloquent
 */
class Appeal extends Model
{
    use HasFactory, HasUuid;

    protected $fillable = [
        'message',
        'rating',

        'user_id',
    ];

    protected $hidden = [
        'user_id',
    ];

    /* Relationships */

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /* Scopes */
}
