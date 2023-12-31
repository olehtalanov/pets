<?php

namespace App\Repositories;

use App\Data\User\ReviewData;
use App\Models\Pin;
use App\Models\Review;
use App\Traits\MediaTrait;
use Illuminate\Support\Collection;

class ReviewRepository extends BaseRepository
{
    use MediaTrait;

    /*public function my(): LengthAwarePaginator
    {
        return Auth::user()
            ->reviews()
            ->with([
                'pin' => [
                    'type',
                    'user',
                ]
            ])
            ->latest()
            ->paginate(config('app.pagination.default'));
    }*/

    public function list(Pin $pin): Collection
    {
        return $pin
            ->reviews()
            ->with('reviewer')
            ->latest()
            ->get();
    }

    public function one(Review $review): Review
    {
        return $review->load('reviewer');
    }

    public function store(Pin $pin, ReviewData $data): Review
    {
        /** @var Review $review */
        $review = $pin->reviews()->create($data->toArray());

        return $this->one($review);
    }

    public function update(Review $review, ReviewData $data): Review
    {
        tap($review)->update($data->toArray());

        return $this->one($review);
    }

    public function destroy(Review $review): void
    {
        $review->delete();
    }
}
