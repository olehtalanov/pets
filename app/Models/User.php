<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Akuechler\Geoly;
use App\Enums\User\UserRoleEnum;
use App\Traits\HasUuid;
use App\Traits\UseMedia;
use Database\Factories\UserFactory;
use Eloquent;
use Filament\Models\Contracts\FilamentUser;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Notifications\DatabaseNotificationCollection;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use Laravel\Sanctum\HasApiTokens;
use Laravel\Sanctum\PersonalAccessToken;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

/**
 * App\Models\User
 *
 * @property int $id
 * @property string $uuid
 * @property UserRoleEnum $role
 * @property string|null $first_name
 * @property string|null $last_name
 * @property string|null $email
 * @property string|null $phone
 * @property Carbon|null $email_verified_at
 * @property mixed|null $password
 * @property string|null $device_id
 * @property string|null $provider
 * @property string|null $provider_id
 * @property string|null $provider_token
 * @property string|null $provider_refresh_token
 * @property string|null $remember_token
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read PersonalAccessCode|null $accessCodes
 * @property-read Collection<int, Animal> $animals
 * @property-read int|null $animals_count
 * @property-read Collection<int, Event> $events
 * @property-read int|null $events_count
 * @property-read MediaCollection<int, Media> $media
 * @property-read int|null $media_count
 * @property-read Collection<int, Note> $notes
 * @property-read int|null $notes_count
 * @property-read DatabaseNotificationCollection<int, DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read Collection<int, Pin> $pins
 * @property-read int|null $pins_count
 * @property-read Collection<int, Review> $reviews
 * @property-read int|null $reviews_count
 * @property-read Collection<int, PersonalAccessToken> $tokens
 * @property-read int|null $tokens_count
 * @method static UserFactory factory($count = null, $state = [])
 * @method static Builder|User newModelQuery()
 * @method static Builder|User newQuery()
 * @method static Builder|User query()
 * @method static Builder|User whereCreatedAt($value)
 * @method static Builder|User whereDeviceId($value)
 * @method static Builder|User whereEmail($value)
 * @method static Builder|User whereEmailVerifiedAt($value)
 * @method static Builder|User whereFirstName($value)
 * @method static Builder|User whereId($value)
 * @method static Builder|User whereLastName($value)
 * @method static Builder|User wherePassword($value)
 * @method static Builder|User wherePhone($value)
 * @method static Builder|User whereProvider($value)
 * @method static Builder|User whereProviderId($value)
 * @method static Builder|User whereProviderRefreshToken($value)
 * @method static Builder|User whereProviderToken($value)
 * @method static Builder|User whereRememberToken($value)
 * @method static Builder|User whereRole($value)
 * @method static Builder|User whereUpdatedAt($value)
 * @method static Builder|User whereUuid($value)
 * @mixin Eloquent
 */
final class User extends Authenticatable implements FilamentUser, HasMedia
{
    use HasApiTokens;
    use HasUuid;
    use HasFactory;
    use Notifiable;
    use UseMedia;
    use Geoly;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'device_id',
        'phone',
        'role',

        'provider',
        'provider_id',
        'provider_token',
        'provider_refresh_token',

        'latitude',
        'longitude',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'provider_token',
        'provider_refresh_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'role' => UserRoleEnum::class,
    ];

    /* Relationships */

    public function animals(): HasMany
    {
        return $this->hasMany(Animal::class);
    }

    public function notes(): HasMany
    {
        return $this->hasMany(Note::class);
    }

    public function events(): HasMany
    {
        return $this->hasMany(Event::class);
    }

    public function pins(): HasMany
    {
        return $this->hasMany(Pin::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function accessCodes(): HasOne
    {
        return $this->hasOne(PersonalAccessCode::class)->latestOfMany();
    }

    /* Auth */

    public function canAccessFilament(): bool
    {
        return $this->hasRole(UserRoleEnum::Admin);
    }

    public function hasRole(array|string|UserRoleEnum $role): bool
    {
        if (is_string($role)) {
            $role = UserRoleEnum::tryFrom($role);
        }

        if (!is_array($role)) {
            $role = [$role];
        }

        return in_array($this->role, $role, true);
    }

    /* Accessors & Mutators */

    protected function name(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->first_name ? "$this->first_name $this->last_name" : null
        );
    }

    protected function phone(): Attribute
    {
        return Attribute::make(
            get: static fn($value): string => "+$value",
            set: static fn($value) => preg_replace('/\D/', '', $value)
        );
    }

    /* Media */

    public function registerMediaCollections(): void
    {
        $this
            ->addMediaCollection('avatar')
            ->singleFile()
            ->registerMediaConversions(function (Media $media) {
                $this
                    ->addMediaConversion('thumb')
                    ->fit(Manipulations::FIT_CROP, 80, 80)
                    ->width(80)
                    ->height(80);
            });
    }
}
