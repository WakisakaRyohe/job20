<?php

namespace App\Policies;

use App\User;
use App\JobCareer;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Log;

class JobCareerPolicy
{
    use HandlesAuthorization;
    
    /**
     * Determine whether the user can view any job careers.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        //
    }

    /**
     * Determine whether the user can view the job career.
     *
     * @param  \App\User  $user
     * @param  \App\JobCareer  $jobCareer
     * @return mixed
     */
    public function view(User $user, JobCareer $jobCareer)
    {
        Log::info('$user->id: ' . $user->id);
        Log::info('$jobCareer->user_id: ' . $jobCareer->user_id);
        return $user->id == $jobCareer->user_id;
    }

    /**
     * Determine whether the user can create job careers.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the job career.
     *
     * @param  \App\User  $user
     * @param  \App\JobCareer  $jobCareer
     * @return mixed
     */
    public function update(User $user, JobCareer $jobCareer)
    {
        //
    }

    /**
     * Determine whether the user can delete the job career.
     *
     * @param  \App\User  $user
     * @param  \App\JobCareer  $jobCareer
     * @return mixed
     */
    public function delete(User $user, JobCareer $jobCareer)
    {
        //
    }

    /**
     * Determine whether the user can restore the job career.
     *
     * @param  \App\User  $user
     * @param  \App\JobCareer  $jobCareer
     * @return mixed
     */
    public function restore(User $user, JobCareer $jobCareer)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the job career.
     *
     * @param  \App\User  $user
     * @param  \App\JobCareer  $jobCareer
     * @return mixed
     */
    public function forceDelete(User $user, JobCareer $jobCareer)
    {
        //
    }
}
