<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    public function changePassword(User $authUser, User $user)
    {
        return $authUser->id === $user->id;
    }


}
