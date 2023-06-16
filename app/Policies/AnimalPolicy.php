<?php

namespace App\Policies;

use App\Models\Animal;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class AnimalPolicy
{
    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Animal $animal): Response
    {
        return $animal->user_id === $user->id
            ? Response::allow()
            : Response::deny();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Animal $animal): Response
    {
        return $animal->user_id === $user->id
            ? Response::allow()
            : Response::deny();
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Animal $animal): Response
    {
        return $animal->user_id === $user->id
            ? Response::allow()
            : Response::deny();
    }
}
