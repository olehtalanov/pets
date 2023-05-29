<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

/**
 * App\Models\Animal
 *
 * @property int $id
 * @property string $uuid
 * @property int $user_id
 * @property string $name
 * @property string $sex
 * @property \Illuminate\Support\Carbon $birth_date
 * @property int|null $animal_type_id
 * @property string|null $custom_type_name
 * @property int|null $breed_id
 * @property string|null $custom_breed_name
 * @property string|null $breed_name
 * @property bool $metis
 * @property float|null $weight
 * @property string|null $weight_unit
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Breed|null $breed
 * @property-read \Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection<int, Media> $media
 * @property-read int|null $media_count
 * @property-read \App\Models\User $owner
 * @property-read \App\Models\AnimalType|null $type
 * @method static \Illuminate\Database\Eloquent\Builder|Animal newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Animal newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Animal query()
 * @method static \Illuminate\Database\Eloquent\Builder|Animal whereAnimalTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Animal whereBirthDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Animal whereBreedId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Animal whereBreedName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Animal whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Animal whereCustomBreedName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Animal whereCustomTypeName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Animal whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Animal whereMetis($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Animal whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Animal whereSex($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Animal whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Animal whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Animal whereUuid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Animal whereWeight($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Animal whereWeightUnit($value)
 * @mixin \Eloquent
 */
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
