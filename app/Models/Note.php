<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class Note extends Model
{
    use HasFactory, HasUuid;

    protected $fillable = [
        'title',
        'description',

        'animal_id',
        'user_id',
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
