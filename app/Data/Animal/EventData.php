<?php

namespace App\Data\Animal;

use App\Enums\Animal\EventRepeatSchemeEnum;
use App\Models\Animal;
use Carbon\Carbon;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\DateTimeInterfaceCast;
use Spatie\LaravelData\Casts\EnumCast;
use Spatie\LaravelData\Data;

class EventData extends Data
{
    public function __construct(
        public string $animal_id,
        public string $title,
        #[WithCast(EnumCast::class, EventRepeatSchemeEnum::class)]
        public EventRepeatSchemeEnum $repeat_scheme,
        public ?string $description,
        #[WithCast(DateTimeInterfaceCast::class)]
        public ?string $starts_at,
        #[WithCast(DateTimeInterfaceCast::class)]
        public ?string $ends_at,
        public ?string $animal_uuid,
        public bool $whole_day = false,
    ) {
        $this->animal_uuid = $animal_id;
        $this->animal_id = Animal::findUOrFail($animal_id)?->getKey();

        if ($this->whole_day) {
            $this->starts_at = $this->starts_at ? Carbon::parse($this->starts_at)->startOfDay() : today();
            $this->ends_at = $this->ends_at ? Carbon::parse($this->ends_at)->endOfDay() : today()->endOfDay();
        } else {
            if (! $this->starts_at) {
                $this->starts_at = now();
            }

            if (! $this->ends_at) {
                $this->ends_at = now()->addMinutes(config('app.events.default_lifetime'));
            }
        }
    }
}
