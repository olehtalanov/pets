<?php

namespace Tests\Feature\Event;

use App\Enums\EventRepeatSchemeEnum;

class RepeatSchemeTest extends BaseTestCase
{
    public function test_never_repeat_scheme(): void
    {
        $this->actingAs($this->user, 'sanctum');

        $this->createEvent(EventRepeatSchemeEnum::Never);

        $response = $this->getJson('/api/v1/events');

        $response
            ->assertStatus(200)
            ->assertJsonCount(1, 'items');
    }

    public function test_every_day_repeat_scheme(): void
    {
        $this->actingAs($this->user, 'sanctum');

        $this->createEvent(EventRepeatSchemeEnum::EveryDay);

        $response = $this->getJson('/api/v1/events');

        $response
            ->assertStatus(200)
            ->assertJsonCount(config('app.events.repeat.days'), 'items');
    }

    public function test_every_working_day_repeat_scheme(): void
    {
        $this->actingAs($this->user, 'sanctum');

        $this->createEvent(EventRepeatSchemeEnum::EveryWorkingDay);

        $response = $this->getJson('/api/v1/events');

        $response
            ->assertStatus(200)
            ->assertJsonCount(config('app.events.repeat.weeks') * 5, 'items');
    }

    public function test_every_weekend_repeat_scheme(): void
    {
        $this->actingAs($this->user, 'sanctum');

        $this->createEvent(EventRepeatSchemeEnum::EveryWeekend);

        $response = $this->getJson('/api/v1/events');

        $response
            ->assertStatus(200)
            ->assertJsonCount(config('app.events.repeat.weeks') * 2, 'items');
    }

    public function test_every_week_repeat_scheme(): void
    {
        $this->actingAs($this->user, 'sanctum');

        $this->createEvent(EventRepeatSchemeEnum::EveryWeek);

        $response = $this->getJson('/api/v1/events');

        $response
            ->assertStatus(200)
            ->assertJsonCount(config('app.events.repeat.weeks') + 1, 'items');
    }

    public function test_every_month_repeat_scheme(): void
    {
        $this->actingAs($this->user, 'sanctum');

        $this->createEvent(EventRepeatSchemeEnum::EveryMonth);

        $response = $this->getJson('/api/v1/events');

        $response
            ->assertStatus(200)
            ->assertJsonCount(config('app.events.repeat.months') + 1, 'items');
    }

    public function test_every_year_repeat_scheme(): void
    {
        $this->actingAs($this->user, 'sanctum');

        $this->createEvent(EventRepeatSchemeEnum::EveryYear);

        $response = $this->getJson('/api/v1/events');

        $response
            ->assertStatus(200)
            ->assertJsonCount(config('app.events.repeat.years') + 1, 'items');
    }
}
