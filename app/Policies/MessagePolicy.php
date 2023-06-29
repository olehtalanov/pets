<?php

namespace App\Policies;

use App\Models\Message;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class MessagePolicy
{
    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Message $message): Response
    {
        return $message->user_id === $user->id
            ? Response::allow()
            : Response::deny();
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Message $message): Response
    {
        return $message->user_id === $user->id
            ? Response::allow()
            : Response::deny();
    }
}
