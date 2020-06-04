<?php

namespace App\Repositories\Eloquent;

use App\models\User;
use App\Repositories\Contracts\UserContract;

class UserRepository extends BaseRepository implements UserContract
{
    public function model(){
        return User::class;
    }
}