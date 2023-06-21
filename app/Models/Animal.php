<?php

namespace App\Models;

use App\Enums\Animal\SexEnum;
use App\Enums\Animal\WeightUnitEnum;
use App\Traits\HasUuid;
use App\Traits\UseMedia;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

/**
 * App\Models\Animal
 *
 * @property int $id
 * @property string $uuid
 * @property int $user_id
 * @property string $name
 * @property SexEnum $sex
 * @property Carbon $birth_date
 * @property int|null $animal_type_id
 * @property string|null $custom_type_name
 * @property int|null $breed_id
 * @property string|null $custom_breed_name
 * @property string|null $breed_name
 * @property bool $metis
 * @property float $weight
 * @property WeightUnitEnum $weight_unit
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read \App\Models\Breed|null $breed
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Event> $events
 * @property-read int|null $events_count
 * @property-read MediaCollection<int, Media> $media
 * @property-read int|null $media_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Note> $notes
 * @property-read int|null $notes_count
 * @property-read \App\Models\User $owner
 * @property-read \App\Models\AnimalType|null $type
 *
 * @method static \Database\Factories\AnimalFactory factory($count = null, $state = [])
 * @method static Builder|Animal newModelQuery()
 * @method static Builder|Animal newQuery()
 * @method static Builder|Animal query()
 * @method static Builder|Animal whereAnimalTypeId($value)
 * @method static Builder|Animal whereBirthDate($value)
 * @method static Builder|Animal whereBreedId($value)
 * @method static Builder|Animal whereBreedName($value)
 * @method static Builder|Animal whereCreatedAt($value)
 * @method static Builder|Animal whereCustomBreedName($value)
 * @method static Builder|Animal whereCustomTypeName($value)
 * @method static Builder|Animal whereId($value)
 * @method static Builder|Animal whereMetis($value)
 * @method static Builder|Animal whereName($value)
 * @method static Builder|Animal whereSex($value)
 * @method static Builder|Animal whereUpdatedAt($value)
 * @method static Builder|Animal whereUserId($value)
 * @method static Builder|Animal whereUuid($value)
 * @method static Builder|Animal whereWeight($value)
 * @method static Builder|Animal whereWeightUnit($value)
 *
 * @mixin Eloquent
 */
class Animal extends Model implements HasMedia
{
    use HasFactory;
    use HasUuid;
    use UseMedia;

    protected $fillable = [
        'name',
        'sex',
        'birth_date',

        'animal_type_id',
        'custom_type_name',

        'breed_id',
        'custom_breed_name',
        'breed_name',
        'metis',

        'weight',
        'weight_unit',

        'user_id',
    ];

    protected $casts = [
        'birth_date' => 'date',
        'metis' => 'boolean',
        'sex' => SexEnum::class,
        'weight_unit' => WeightUnitEnum::class,
    ];

    /* Relationships */

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function notes(): HasMany
    {
        return $this->hasMany(Note::class);
    }

    public function events(): HasMany
    {
        return $this->hasMany(Event::class);
    }

    public function type(): BelongsTo
    {
        return $this->belongsTo(AnimalType::class, 'animal_type_id')->withDefault([
            'name' => $this->custom_type_name ?? trans('common.placeholder.unknown'),
        ]);
    }

    public function breed(): BelongsTo
    {
        return $this->belongsTo(Breed::class)->withDefault([
            'name' => $this->custom_breed_name ?? trans('common.placeholder.unknown'),
        ]);
    }

    /* Media */

    public function registerMediaCollections(): void
    {
        $this
            ->addMediaCollection('avatar')
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/heif'])
            ->singleFile()
            ->registerMediaConversions(function (Media $media) {
                $thumb = 200;

                $this
                    ->addMediaConversion('thumb')
                    ->fit(Manipulations::FIT_CROP, $thumb, $thumb)
                    ->width($thumb)
                    ->height($thumb);
            });
    }
}
