<?php

namespace App\Policies;

use App\Models\Event;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class EventPolicy
{
    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Event $event): Response
    {
        return $event->user_id === $user->id
            ? Response::allow()
            : Response::deny();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Event $event): Response
    {
        return $event->user_id === $user->id
            ? Response::allow()
            : Response::deny();
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Event $event): Response
    {
        return $event->user_id === $user->id
            ? Response::allow()
            : Response::deny();
    }
}
