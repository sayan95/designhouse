<?php

namespace App\Repositories\Eloquent;

use App\models\Team;
use App\Repositories\Contracts\TeamContract;
use App\Repositories\Contracts\UserContract;
use App\Repositories\Eloquent\BaseRepository;

class TeamRepository extends BaseRepository implements TeamContract
{
    
    public function model(){
        return Team::class;
    }

    public function findBySlug($slug){
        //
    }

    public function fetchUserTeams(){
        return auth('api')->user()->teams;
    }
}