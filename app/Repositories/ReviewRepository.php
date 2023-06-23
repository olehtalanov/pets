<?php

namespace App\Repositories;

use App\Data\User\ReviewData;
use App\Models\Pin;
use App\Models\Review;
use Auth;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class ReviewRepository extends BaseRepository
{
    use MediaTrait;

    public function my(): LengthAwarePaginator
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
            ->paginate(config('app.pagination_default'));
    }

    public function list(Pin $pin): Collection
    {
        $pin
            ->load(['type', 'user'])
            ->loadAvg('reviews', 'rating');

        return Review::wherePinId($pin->getKey())
            ->get()
            ->each(fn($review) => $review->setRelation('pin', $pin));
    }

    public function one(Review $review): Review
    {
        $pin = $review->pin
            ->load(['type', 'user'])
            ->loadAvg('reviews', 'rating');

        return $review->setRelation('pin', $pin);
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
