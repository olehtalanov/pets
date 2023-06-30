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
        return $this->checkAccess($user, $animal);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Animal $animal): Response
    {
        return $this->checkAccess($user, $animal);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Animal $animal): Response
    {
        return $this->checkAccess($user, $animal);
    }

    private function checkAccess(User $user, Animal $animal): Response
    {
        if ($user->canAccessFilament()) {
            return Response::allow();
        }

        return $animal->user_id === $user->id
            ? Response::allow()
            : Response::deny();
    }
}
