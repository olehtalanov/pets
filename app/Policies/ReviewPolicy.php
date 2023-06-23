<?php

namespace App\Policies;

use App\Models\Pin;
use App\Models\Review;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ReviewPolicy
{
    /**
     * Determine whether the user can create models.
     */
    public function create(User $user, Pin $pin): Response
    {
        return $pin->user_id !== $user->id && $pin->reviews()->where('user_id', $user->id)->doesntExist()
            ? Response::allow()
            : Response::deny();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Review $review): Response
    {
        return $review->user_id === $user->id
            ? Response::allow()
            : Response::deny();
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Review $review): Response
    {
        return $review->user_id === $user->id
            ? Response::allow()
            : Response::deny();
    }
}
