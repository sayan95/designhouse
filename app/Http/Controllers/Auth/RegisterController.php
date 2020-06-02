<?php

namespace App\Http\Controllers\Auth;

use App\models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Hash;
use App\Providers\RouteServiceProvider;
use App\Repositories\Contracts\UserContract;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

class RegisterController extends Controller
{
    
    use RegistersUsers;

    protected $user;

    public function __construct(UserContract $user)
    {
        $this->user = $user;
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'username' => ['required','alpha_dash','max:20','unique:users,username'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        return $this->user->create([
            'username' => $data['username'],
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
    }

    protected function registered(Request $request, User $user)
    {
        return response()->json([
            'user' => new UserResource($user),
            'message' =>'An account activation link is sent to your email address. 
                        Please activate your account before getting started with us.'
        ], 200);
    }
}
