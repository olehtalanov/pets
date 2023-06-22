<?php

namespace App\Policies;

use App\Models\Pin;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class PinPolicy
{
    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Pin $pin): Response
    {
        return $pin->user_id === $user->id
            ? Response::allow()
            : Response::deny();
    }

    /**
     * Determine whether the user can upload to the model gallery.
     */
    public function upload(User $user, Pin $pin)
    {
        return $pin->user_id === $user->id
            ? Response::allow()
            : Response::deny();
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Pin $pin): Response
    {
        return $pin->user_id === $user->id
            ? Response::allow()
            : Response::deny();
    }

    /**
     * Determine whether the user can delete media from the model.
     */
    public function deleteMedia(User $user, Pin $pin): Response
    {
        return $pin->user_id === $user->id
            ? Response::allow()
            : Response::deny();
    }
}
