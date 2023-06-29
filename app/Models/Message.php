<?php

namespace App\Models;

use App\Traits\HasUuid;
use Auth;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * App\Models\Message
 *
 * @property int $id
 * @property int $chat_id
 * @property int|null $user_id
 * @property string $message
 * @property object|null $meta
 * @property Carbon|null $read_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string $uuid
 * @property-read \App\Models\Chat $chat
 * @property-read \App\Models\User|null $user
 * @method static Builder|Message newModelQuery()
 * @method static Builder|Message newQuery()
 * @method static Builder|Message query()
 * @method static Builder|Message unread()
 * @method static Builder|Message whereChatId($value)
 * @method static Builder|Message whereCreatedAt($value)
 * @method static Builder|Message whereId($value)
 * @method static Builder|Message whereMessage($value)
 * @method static Builder|Message whereMeta($value)
 * @method static Builder|Message whereReadAt($value)
 * @method static Builder|Message whereUpdatedAt($value)
 * @method static Builder|Message whereUserId($value)
 * @method static Builder|Message whereUuid($value)
 * @mixin Eloquent
 */
final class Message extends Model
{
    use HasUuid;

    protected $fillable = [
        'message',
        'meta',
        'read_at',

        'chat_id',
        'user_id',
    ];

    protected $casts = [
        'meta' => 'object',
        'read_at' => 'datetime',
    ];

    /* Relationships */

    public function chat(): BelongsTo
    {
        return $this->belongsTo(Chat::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class)->withDefault([
            'name' => trans('common.placeholder.unknown'),
        ]);
    }

    /* Scopes */

    public function scopeUnread(Builder $builder): void
    {
        $builder->whereNull('read_at')->where('user_id', '!=', Auth::id());
    }
}
