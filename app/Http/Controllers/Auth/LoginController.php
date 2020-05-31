<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    
    use AuthenticatesUsers;


    protected function validateLogin(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email|max:50',
            'password' => 'required|string',
        ]);
    }

    public function attemptLogin(Request $request){
        // attempt to issuse a token based on the valid login credentials
        $credentials = $request->only('email','password'); 
        $token = $this->guard()->attempt($credentials);
        
        if( ! $token ){
            return false;
        }
        
        // get the authenticated user 
        $user = $this->guard()->user();
        if($user instanceof MustVerifyEmail && ! $user->hasVerifiedEmail()){
            return false;
        }

        // set the user's token
        $this->guard()->setToken($token);
        return true;
    }

    protected function sendLoginResponse(Request $request)
    {
        $this->clearLoginAttempts($request);
        
        //get the token
        $token = (string)$this->guard()->getToken();

        //extract the token's expiary date
        $expiration = $this->guard()->getPayload()->get('exp');
        
        return response()->json([
            'token'=>$token,
            'token_type' => 'bearer',
            'expires_in' => $expiration
        ], 200);
    }

    protected function sendFailedLoginResponse()
    {   
        $user = $this->guard()->user();

        if($user instanceof MustVerifyEmail && ! $user->hasVerifiedEmail()){
            return response()->json(['error'=>[
                'message'=> 'You need to verify your account'
            ]], 422);
        }
        return response()->json(['error'=>[
            'message'=>'Invalid email or password'
        ]], 422);
    }

    // logout the user 
    public function logout(){
        $this->guard()->logout();
        return response()->json(['success'=>[
            'message'=> 'Successfully logged out'
        ]], 200);
    }


    // returns authentication guard
    protected function guard(){
        return Auth::guard('api');
    }
}
