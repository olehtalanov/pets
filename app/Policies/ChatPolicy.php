<?php

namespace App\Policies;

use App\Models\Chat;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ChatPolicy
{
    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Chat $chat): Response
    {
        return $chat->owner_id === $user->id || $chat->recipient_id === $user->id
            ? Response::allow()
            : Response::deny();
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Chat $chat): Response
    {
        return $chat->owner_id === $user->id || $chat->recipient_id === $user->id
            ? Response::allow()
            : Response::deny();
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Chat $chat): Response
    {
        return $chat->owner_id === $user->id || $chat->recipient_id === $user->id
            ? Response::allow()
            : Response::deny();
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Chat $chat): Response
    {
        return $chat->owner_id === $user->id || $chat->recipient_id === $user->id
            ? Response::allow()
            : Response::deny();
    }
}
