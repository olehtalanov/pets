<?php

namespace App\Repositories;

use App\Data\User\PinData;
use App\Models\Pin;
use App\Models\Review;
use App\Traits\MediaTrait;
use Auth;
use DB;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class PinRepository extends BaseRepository
{
    use MediaTrait;

    public function search(Collection $filters): LengthAwarePaginator
    {
        return Pin::query()
            ->with('type')
            ->withAvg('reviews', 'rating')
            ->select([
                'pins.*',
                'own_review_id' => Review::query()
                    ->whereColumn('pin_id', 'pins.id')
                    ->whereRaw('user_id = ' . Auth::id())
                    ->select('uuid')
            ])
            ->when($filters->has(['latitude', 'longitude', 'radius']), function (Builder $builder) use ($filters) {
                $builder->radius(
                    $filters->get('latitude'),
                    $filters->get('longitude'),
                    $filters->get('radius') / 1000
                );
            })
            ->when($filters->get('type_ids'), function (Builder $builder, array $ids) {
                $builder->whereIn('type_id', DB::table('pin_types')->whereIn('uuid', $ids)->pluck('id'));
            })
            ->paginate($filters->get('limit', config('app.pagination.search')));
    }

    public function list(): LengthAwarePaginator
    {
        return Auth::user()
            ->pins()
            ->with('type')
            ->withAvg('reviews', 'rating')
            ->withCount('reviews')
            ->select([
                'pins.*',
                'own_review_id' => Review::query()
                    ->whereColumn('pin_id', 'pins.id')
                    ->whereRaw('user_id = ' . Auth::id())
                    ->select('uuid')
            ])
            ->latest()
            ->paginate(config('app.pagination.default'));
    }

    public function one(Pin $pin): Pin
    {
        return $pin->load('type')->loadCount('reviews');
    }

    public function store(PinData $data): Pin
    {
        /** @var Pin $pin */
        $pin = Auth::user()
            ->pins()
            ->create($data->toArray());

        return $this->one($pin);
    }

    public function update(Pin $pin, PinData $data): Pin
    {
        tap($pin)->update($data->toArray());

        return $this->one($pin);
    }

    public function destroy(Pin $pin): void
    {
        $pin->delete();
    }
}
