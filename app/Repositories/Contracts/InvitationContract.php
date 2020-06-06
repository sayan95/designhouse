<?php

namespace App\Repositories\Contracts;

interface InvitationContract
{
    public function removeUserToTeam($user_id,  $team);
    public function addUserToTeam($user_id,  $team);
}