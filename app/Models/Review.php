<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

class Review extends Model
{
    use HasFactory, HasUuid;

    protected $fillable = [
        'message',
        'rating',

        'user_id',
        'pin_id',
    ];

    protected $hidden = [
        'user_id',
        'pin_id',
    ];

    /* Relationships */

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function reviewable(): HasOneThrough
    {
        return $this->hasOneThrough(User::class, Pin::class);
    }

    public function pin(): BelongsTo
    {
        return $this->belongsTo(Pin::class);
    }

    /* Scopes */
}
