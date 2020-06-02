<?php

namespace App\Http\Controllers\Auth;

use App\models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Repositories\Contracts\UserContract;
use Illuminate\Auth\Events\Verified;
use Illuminate\Support\Facades\URL;

class VerificationController extends Controller
{
    
    protected $user;
    public function __construct(UserContract $user)
    {
        $this->user = $user;
        $this->middleware('throttle:6,1')->only('verify', 'resend');
    }

    /**
     *  verification of the email
     */
    public function verify(Request $request, User $user){
        // check if the url is a valid signd url
        if( ! URL::hasValidSignature($request)){
            return response()->json(['error'=>[
                'message' => "Invalid verification link "
            ]],422);
        }

        // check if the user has already verified account
        if($user->hasVerifiedEmail()){
            return response()->json(['error'=>[
                'meassgae'=>'Your account is already verified'
            ]],422);
        }

        $user->markEmailAsVerified();
        event(new Verified($user));

        return response()->json(['success'=>[
            'message'=>'Your account is verified successfully'
        ]],200);
    }

    // resendindin the verification link 
    public function resend(Request $request){
        // validate the email 
        $request->validate([
            'email' => ['email', 'required', 'max:50', 'string']
        ]);
        
        // if user not found
        $user = $this->user->findWhereFirst('email',$request->email);
        
        if(! $user){
            return response()->json(['error'=>[
                'message' => "We can't find a user with that email address."
            ]], 422);
        }

        // if account is already verified
        if($user->hasVerifiedEmail()){
            return response()->json(['error'=>[
                'meassgae'=>'Your account is already verified'
            ]],422);
        }

        $user->sendEmailVerificationNotification();

        return response()->json(['success'=>[
            'status' => 'Verification mail is resent'
        ]], 200);
    }
}
