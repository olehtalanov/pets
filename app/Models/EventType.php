<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventType extends Model
{
    use HasFactory, HasUuid;

    protected $fillable = [
        //
    ];

    protected $hidden = [
        'id',
    ];

    /* Relationships */

    /* Scopes */
}
