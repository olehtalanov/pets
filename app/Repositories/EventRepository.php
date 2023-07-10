<?php

namespace App\Repositories;

use App\Actions\Event\ChangeState;
use App\Data\Animal\EventData;
use App\Jobs\Event\RepeatEvent;
use App\Models\Animal;
use App\Models\Event;
use Auth;
use Carbon\Carbon;
use DB;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class EventRepository extends BaseRepository
{
    public function list(Collection $filters): LengthAwarePaginator
    {
        return Auth::user()
            ->events()
            ->select([
                'events.*',
                'animal_name' => Animal::query()
                    ->whereColumn('user_id', 'events.user_id')
                    ->select('name'),
            ])
            ->with('categories:id,uuid,name')
            ->when($filters->get('animal'), function (Builder $builder, string $uuid) {
                $builder->where('animal_id', DB::table('animals')->whereColumn('uuid', $uuid)->first()?->uuid);
            })
            ->when($filters->get('date_from'), function (Builder $builder, Carbon $value) {
                $builder->where('starts_at', '>=', $value);
            })
            ->when($filters->get('date_to'), function (Builder $builder, Carbon $value) {
                $builder->where('starts_at', '<=', $value);
            })
            ->when($filters->get('search'), function (Builder $builder, string $value) {
                $builder
                    ->where('title', 'like', "%$value%")
                    ->orWhereFullText('description', $value);
            })
            ->paginate($filters->get('limit', config('app.pagination.search')));
    }

    public function one(Event $event): Event
    {
        return $event->load('animal');
    }

    public function store(
        EventData $data,
        ?Carbon   $fromDate = null
    ): Event
    {
        /** @var Event $event */
        $event = Auth::user()
            ->events()
            ->create(
                $data
                    ->except('category_ids')
                    ->additional([
                        'processable' => 1,
                    ])
                    ->toArray()
            );

        if ($data->category_ids) {
            $event->categories()->attach($data->category_ids);
        }

        dispatch(new RepeatEvent($event, $fromDate));

        return $this->one($event);
    }

    public function update(
        Event     $event,
        EventData $data,
        bool      $onlyThis,
    ): Event
    {
        $state = ChangeState::make($event, !$onlyThis);

        tap($event)->update(
            $data
                ->except('category_ids')
                ->additional($state->additional)
                ->toArray()
        );

        if ($data->category_ids) {
            $event->categories()->sync($data->category_ids);
        }

        if ($state->generateChildren) {
            dispatch(new RepeatEvent($event));
        }

        return $this->one($event);
    }

    public function destroy(Event $event): void
    {
        $event->delete();
    }
}
