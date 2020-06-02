<?php

namespace App\Http\Controllers\User;

use App\models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;

class UserController extends Controller
{

    public function index(){
        $users = User::all();
        return UserResource::collection($users);
    }
    
    public function getUser(){
        if(auth('api')->check()){
            $user = auth()->user();
            return new UserResource($user);
        }

        return response()->json(null, 401);
    }
}
