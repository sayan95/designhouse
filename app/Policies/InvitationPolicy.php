<?php

namespace App\Policies;

use App\models\User;
use App\models\Invitation;
use Illuminate\Auth\Access\HandlesAuthorization;

class InvitationPolicy
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
     * @param  \App\Invitation  $invitation
     * @return mixed
     */
    public function view(User $user, Invitation $invitation)
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
     * @param  \App\Invitation  $invitation
     * @return mixed
     */
    public function update(User $user, Invitation $invitation)
    {
        //
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\models\User  $user
     * @param  \App\Invitation  $invitation
     * @return mixed
     */
    public function delete(User $user, Invitation $invitation)
    {
        return $user->id == $invitation->sender_id;
    }

    /**
     * Determine whether the user can respond the invitation.
     *
     * @param  \App\models\User  $user
     * @param  \App\Invitation  $invitation
     * @return mixed
     */
    public function respond(User $user, Invitation $invitation)
    {
        return $user->email == $invitation->recipient_email; 
    }

    /**
     * Determine whether the user can resend the invitation.
     *
     * @param  \App\models\User  $user
     * @param  \App\Invitation  $invitation
     * @return mixed
     */
    public function resend(User $user, Invitation $invitation)
    {
        return $user->id == $invitation->sender_id;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\models\User  $user
     * @param  \App\Invitation  $invitation
     * @return mixed
     */
    public function restore(User $user, Invitation $invitation)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\models\User  $user
     * @param  \App\Invitation  $invitation
     * @return mixed
     */
    public function forceDelete(User $user, Invitation $invitation)
    {
        //
    }
}
