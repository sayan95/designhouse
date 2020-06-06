<?php

namespace App\Policies;

use App\models\Team;
use App\models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TeamPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\models\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\models\User  $user
     * @param  \App\models\Team  $team
     * @return mixed
     */
    public function view(User $user, Team $team)
    {
        //
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\models\User  $user
     * @param  \App\models\Team  $team
     * @return mixed
     */
    public function update(User $user, Team $team)
    {
        return $user->isOwner($team);
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\models\User  $user
     * @param  \App\models\Team  $team
     * @return mixed
     */
    public function delete(User $user, Team $team)
    {
        return $user->isOwner($team);
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\models\User  $user
     * @param  \App\models\Team  $team
     * @return mixed
     */
    public function restore(User $user, Team $team)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\models\User  $user
     * @param  \App\models\Team  $team
     * @return mixed
     */
    public function forceDelete(User $user, Team $team)
    {
        //
    }
}
