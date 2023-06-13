<?php

namespace App\Repositories;

use App\Data\Animal\EventData;
use App\Jobs\Event\RepeatEvent;
use App\Models\Event;
use Auth;
use Carbon\Carbon;
use Illuminate\Contracts\Database\Eloquent\Builder;

class EventRepository extends BaseRepository
{
    public function list(array $filters)
    {
        return Auth::user()->events()
            ->when($filters['date_from'], function (Builder $builder, Carbon $value) {
                //
            });
    }

    public function store(
        EventData $data,
        ?Carbon $fromDate = null
    ): Event {
        $event = Auth::user()->events()->create($data->toArray());

        $event->setRelation(
            'animal',
            AnimalRepository::make()->one($data->animal_uuid)
        );

        dispatch(new RepeatEvent($event, $fromDate));

        return $event;
    }

    public function update(
        string $event,
        EventData $data,
        ?Carbon $fromDate = null,
    ): Event {
        $event = tap(Event::whereUuid($event))->update(
            $data->additional([
                'original_id' => null,
            ])->toArray()
        );

        $event->setRelation(
            'animal',
            AnimalRepository::make()->one($data->animal_uuid)
        );

        dispatch(new RepeatEvent($event, $fromDate));

        return $event;
    }
}
