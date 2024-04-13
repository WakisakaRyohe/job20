<?php

namespace App\Policies;

use App\User;
use App\Bord;
use Illuminate\Auth\Access\HandlesAuthorization;

class BordPolicy
{
    use HandlesAuthorization;
    
    /**
     * Determine whether the user can view any bords.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        //
    }

    /**
     * Determine whether the user can view the bord.
     *
     * @param  \App\User  $user
     * @param  \App\Bord  $bord
     * @return mixed
     */
    public function viewBord(User $user, Bord $bord)
    {        
        return $user->id == $bord->user_id;
    }

    /**
     * Determine whether the user can create bords.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the bord.
     *
     * @param  \App\User  $user
     * @param  \App\Bord  $bord
     * @return mixed
     */
    public function update(User $user, Bord $bord)
    {
        //
    }

    /**
     * Determine whether the user can delete the bord.
     *
     * @param  \App\User  $user
     * @param  \App\Bord  $bord
     * @return mixed
     */
    public function delete(User $user, Bord $bord)
    {
        //
    }

    /**
     * Determine whether the user can restore the bord.
     *
     * @param  \App\User  $user
     * @param  \App\Bord  $bord
     * @return mixed
     */
    public function restore(User $user, Bord $bord)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the bord.
     *
     * @param  \App\User  $user
     * @param  \App\Bord  $bord
     * @return mixed
     */
    public function forceDelete(User $user, Bord $bord)
    {
        //
    }
}
