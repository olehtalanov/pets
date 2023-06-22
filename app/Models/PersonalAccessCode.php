<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\PersonalAccessCode
 *
 * @property int $id
 * @property int $user_id
 * @property string $code
 * @property \Illuminate\Support\Carbon $valid_until
 * @property-read \App\Models\User $user
 * @method static Builder|PersonalAccessCode active()
 * @method static Builder|PersonalAccessCode newModelQuery()
 * @method static Builder|PersonalAccessCode newQuery()
 * @method static Builder|PersonalAccessCode query()
 * @method static Builder|PersonalAccessCode whereCode($value)
 * @method static Builder|PersonalAccessCode whereId($value)
 * @method static Builder|PersonalAccessCode whereUserId($value)
 * @method static Builder|PersonalAccessCode whereValidUntil($value)
 * @mixin Eloquent
 */
class PersonalAccessCode extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'code',
        'valid_until',

        'user_id',
    ];

    protected $casts = [
        'valid_until' => 'datetime',
    ];

    /* Relationships */

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /* Scopes */

    public function scopeActive(Builder $builder): void
    {
        $builder->whereBetween('valid_until', [
            now(),
            now()->addMinutes(config('app.auth.code_valid_minutes')),
        ]);
    }

    protected static function booted(): void
    {
        parent::booted();

        static::creating(static function (Model $model) {
            $model->code = random_int(100000, 999999);
            $model->valid_until = now()->addMinutes(config('app.auth.code_valid_minutes'));
        });
    }
}
