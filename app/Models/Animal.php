<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Animal extends Model implements HasMedia
{
    use HasUuid, InteractsWithMedia;

    protected $fillable = [
        'name',
        'sex',
        'birth_date',

        'user_id',

        'animal_type_id',
        'custom_type_name',

        'breed_id',
        'custom_breed_name',
        'breed_name',
        'metis',

        'weight',
        'weight_unit'
    ];

    protected $casts = [
        'birth_date' => 'date',
        'metis' => 'boolean',
    ];

    protected $hidden = [
        'id',
        'animal_type_id',
        'breed_id',
    ];

    /* Relationships */

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function type(): BelongsTo
    {
        return $this->belongsTo(AnimalType::class)->withDefault([
            'id' => null,
            'name' => $this->animal_type_name
        ]);
    }

    public function breed(): BelongsTo
    {
        return $this->belongsTo(Breed::class)->withDefault([
            'id' => null,
            'name' => $this->breed_name
        ]);
    }

    /* Media */

    public function registerMediaCollections(): void
    {
        $this
            ->addMediaCollection('avatar')
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/heif'])
            ->registerMediaConversions(function (Media $media) {
                $this
                    ->addMediaConversion('thumb')
                    ->width(120)
                    ->height(120);
            });
    }
}
