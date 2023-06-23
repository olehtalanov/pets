<?php

namespace App\Repositories;

use App\Data\User\CoordinatesData;
use App\Models\User;
use Auth;
use DB;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class UserRepository extends BaseRepository
{
    public function search(Collection $filters): LengthAwarePaginator
    {
        return User::with([
            'animals' => [
                'breed',
                'type',
                'media',
            ]
        ])
            ->when($filters->has(['latitude', 'longitude', 'radius']), function (Builder $builder) use ($filters) {
                $builder->radius(
                    $filters->get('latitude'),
                    $filters->get('longitude'),
                    $filters->get('radius') / 1000
                );
            })
            ->when($filters->get('animal_type_ids'), function (Builder $builder, array $ids) {
                $builder->whereHas('animals', function (Builder $query) use ($ids) {
                    $query->whereIn('animal_type_id', DB::table('animal_types')->whereIn('uuid', $ids)->pluck('id'));
                });
            })
            ->when($filters->get('breed_ids'), function (Builder $builder, array $ids) {
                $builder->whereHas('animals', function (Builder $query) use ($ids) {
                    $query->whereIn('breed_id', DB::table('breeds')->whereIn('uuid', $ids)->pluck('id'));
                });
            })
            ->paginate(config('app.search_limit'));
    }

    public function storeCoordinates(CoordinatesData $data): array
    {
        Auth::user()->updateQuietly($data->toArray());

        return $this->showCoordinates();
    }

    public function showCoordinates(): array
    {
        return Auth::user()->only('latitude', 'longitude');
    }
}
