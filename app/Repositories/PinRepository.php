<?php

namespace App\Repositories;

use App\Data\User\PinData;
use App\Models\Pin;
use Auth;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Spatie\MediaLibrary\MediaCollections\Models\Collections\MediaCollection;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class PinRepository extends BaseRepository
{
    public function search(Collection $filters): Collection
    {
        return Pin::query()
            ->with('type')
            ->withAvg('reviews', 'rating')
            ->when($filters->has(['latitude', 'longitude', 'radius']), function (Builder $builder) use ($filters) {
                $builder->radius(
                    $filters->get('latitude'),
                    $filters->get('longitude'),
                    $filters->get('radius')
                );
            })
            ->when($filters->get('type_ids'), function (Builder $builder, array $ids) {
                $builder->whereIn('type_id', $ids);
            })
            ->limit($filters->get('limit', config('app.pins.search_limit')))
            ->get();
    }

    public function list(): LengthAwarePaginator
    {
        return Auth::user()
            ->pins()
            ->with('type')
            ->withAvg('reviews', 'rating')
            ->latest()
            ->paginate(config('app.pins.pagination'));
    }

    public function one(Pin $pin): Pin
    {
        return $pin->load('type');
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

    public function media(Pin $pin): MediaCollection
    {
        return $pin->getMedia('gallery');
    }

    public function upload(Pin $pin, array|UploadedFile $files): MediaCollection
    {
        foreach ($files as $file) {
            $pin
                ->addMedia($file)
                ->toMediaCollection('gallery');
        }

        return $this->media($pin);
    }

    public function destroyMedia(Media $media): void
    {
        $media->delete();
    }
}
