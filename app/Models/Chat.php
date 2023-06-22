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
use Illuminate\Support\Carbon;

/**
 * App\Models\Chat
 *
 * @property int $id
 * @property string $uuid
 * @property string $name
 * @property int|null $owner_id
 * @property int|null $recipient_id
 * @property bool $is_archived
 * @property Carbon|null $last_message_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read \App\Models\Message|null $lastMessage
 * @property-read Collection<int, \App\Models\Message> $messages
 * @property-read int|null $messages_count
 * @property-read \App\Models\User|null $owner
 * @property-read \App\Models\User|null $recipient
 * @method static Builder|Chat current()
 * @method static Builder|Chat newModelQuery()
 * @method static Builder|Chat newQuery()
 * @method static Builder|Chat query()
 * @method static Builder|Chat whereCreatedAt($value)
 * @method static Builder|Chat whereId($value)
 * @method static Builder|Chat whereIsArchived($value)
 * @method static Builder|Chat whereLastMessageAt($value)
 * @method static Builder|Chat whereName($value)
 * @method static Builder|Chat whereOwnerId($value)
 * @method static Builder|Chat whereRecipientId($value)
 * @method static Builder|Chat whereUpdatedAt($value)
 * @method static Builder|Chat whereUuid($value)
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
            'name' => trans('common.placeholder.unknown'),
        ]);
    }

    public function recipient(): BelongsTo
    {
        return $this->belongsTo(User::class)->withDefault([
            'name' => trans('common.placeholder.unknown'),
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
