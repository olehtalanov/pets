<?php

namespace App\Models;

use App\Enums\Chat\MessageTypeEnum;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * App\Models\Message
 *
 * @property-read \App\Models\Chat|null $chat
 * @property-read \App\Models\User $user
 * @method static Builder|Message newModelQuery()
 * @method static Builder|Message newQuery()
 * @method static Builder|Message query()
 * @mixin Eloquent
 */
final class Message extends Model
{
    protected $fillable = [
        'content',
        'meta',
        'read_at',

        'chat_id',
        'user_id',
    ];

    protected $casts = [
        'meta' => 'object',
        'read_at' => 'datetime',
    ];

    protected $with = [
        'user:uuid,name',
    ];

    /* Relationships */

    public function chat(): BelongsTo
    {
        return $this->belongsTo(Chat::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class)->withDefault([
            'name' => trans('common.placeholder.deleted'),
        ]);
    }
}
