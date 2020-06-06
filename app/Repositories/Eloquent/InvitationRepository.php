<?php

namespace App\Repositories\Eloquent;

use App\models\Invitation;
use App\Repositories\Eloquent\BaseRepository;
use App\Repositories\Contracts\InvitationContract;

class InvitationRepository extends BaseRepository implements InvitationContract
{
    public function model(){
        return Invitation::class;
    }

    public function addUserToTeam($team, $user_id){
        $team->members()->attach($user_id);
    }
    public function removeUserToTeam($user_id,  $team){
        $team->members()->detach($user_id);
    }
}