<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function getUser(){
        if(auth('api')->check()){
            $user = auth()->user();
            return new UserResource($user);
        }

        return response()->json(null, 401);
    }
}
