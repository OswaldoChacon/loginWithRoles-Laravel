<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Crypt;

class UsersPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }
    public function actualizar_info(User $authuser, User $user)
    {        
        return $authuser->id === $user->id;
    }
    public function registrarView(User $authuser, User $user)
    {        
        return $authuser->id === $user->id;
    }
}
