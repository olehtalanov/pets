<?php

namespace App\Models;

use App\Traits\HasUuid;
use Auth;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * App\Models\Chat
 *
 * @property-read \App\Models\Message|null $lastMessage
 * @property-read Collection<int, \App\Models\Message> $messages
 * @property-read int|null $messages_count
 * @property-read \App\Models\User|null $owner
 * @property-read \App\Models\User|null $recipient
 *
 * @method static Builder|Chat current()
 * @method static Builder|Chat newModelQuery()
 * @method static Builder|Chat newQuery()
 * @method static Builder|Chat query()
 *
 * @mixin Eloquent
 */
final class Chat extends Model
{
    use HasUuid;

    protected $fillable = [
        'name',
        'is_archived',
        'last_message_at',

        'owner_id',
        'recipient_id',
    ];

    protected $hidden = [
        'owner_id',
        'recipient_id',
    ];

    protected $casts = [
        'is_archived' => 'boolean',
        'last_message_at' => 'datetime',
    ];

    /* Relationships */

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class)->withDefault([
            'name' => trans('common.placeholder.deleted'),
        ]);
    }

    public function recipient(): BelongsTo
    {
        return $this->belongsTo(User::class)->withDefault([
            'name' => trans('common.placeholder.deleted'),
        ]);
    }

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class);
    }

    public function lastMessage(): HasOne
    {
        return $this->hasOne(Message::class)->ofMany()->latest();
    }

    /* Scopes */

    public function scopeCurrent(Builder $builder): Builder
    {
        return $builder
            ->where('owner_id', Auth::id())
            ->orWhere('recipient_id', Auth::id());
    }
}
