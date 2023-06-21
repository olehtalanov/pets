<?php

namespace App\Policies;

use App\Models\Note;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class NotePolicy
{
    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Note $note): Response
    {
        return $note->user_id === $user->id
            ? Response::allow()
            : Response::deny();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Note $note): Response
    {
        return $note->user_id === $user->id
            ? Response::allow()
            : Response::deny();
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Note $note): Response
    {
        return $note->user_id === $user->id
            ? Response::allow()
            : Response::deny();
    }
}
