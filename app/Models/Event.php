<?php

namespace App\Models;

use App\Enums\User\EventRepeatSchemeEnum;
use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;

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

    protected $hidden = [
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

    public function category(): MorphOne
    {
        return $this->morphOne(Category::class, 'categorable');
    }

    /* Scopes */
}
