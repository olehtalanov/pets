<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pin extends Model
{
    use HasFactory, HasUuid;

    protected $fillable = [
        'latitude',
        'longitude',

        'user_id',
        'type_id',
    ];

    protected $hidden = [
        'user_id',
        'type_id',
    ];

    /* Relationships */

    public function type(): BelongsTo
    {
        return $this->belongsTo(PinType::class);
    }

    /* Scopes */
}
