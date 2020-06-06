<?php

namespace App\Http\Controllers\Teams;

use App\models\Team;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Mail;
use App\Mail\SendInvitationToJoinTeam;
use App\Repositories\Contracts\TeamContract;
use App\Repositories\Contracts\UserContract;
use App\Repositories\Contracts\InvitationContract;
use Illuminate\Auth\Access\AuthorizationException;

class InvitationsController extends Controller
{
    protected $invitation, $team, $user;

    /**
     *  Dependency Injection
     */
    public function __construct(InvitationContract $invitation, TeamContract $team, UserContract $user)
    {
        $this->invitation = $invitation;
        $this->team = $team;
        $this->user = $user;
    }

    /**
     * Send an invitation to a user to join the team
     */
    public function invite(Request $request, $team_id){
        $team = $this->team->find($team_id);
        $user = auth('api')->user();

        $request->validate([
            'email' => ['required','email']
        ]);
        
        // check the user owns the team
        if(! $user->isOwner($team)){
            throw new AuthorizationException;
        }

        // check if the email has a pending invitation
        if($team->hasPendingInvite($request->email)){
            return response()->json(['error'=>[
                'message' => 'This email already has an pending invitation'
            ]], 422);
        }

        // get the recipient by email
        $recipient = $this->user->findByEmail($request->email);

        if(!$recipient){
            $this->createInvitations(false, $team, $request->email);

            return response()->json(['success'=>[
                'message' => 'Invitation sent to user'
            ]],200);
        }

        // check if the team already has the user
        if($team->hasUser($recipient)){
            return response()->json(['error'=>[
                'message' => 'This user seems to be team member already'
            ]],422);
        }
        
        // send the invitation
        $this->createInvitations(true, $team, $request->email);

        return response()->json(['success'=>[
            'message' => 'Invitation sent to user'
        ]],200);
    }

    /**
     * Resend an invitation to a user to join the team
     */
    public function resend($id){
        $invitation = $this->invitation->find($id);

        // check the user owns the team
        $this->authorize('resend', $invitation);

        $recipient = $this->user   
                    ->findByEmail($invitation->recipient_email);

        Mail::to($invitation->recipient_email)
                ->send( new SendInvitationToJoinTeam($invitation, ! is_null($recipient)));
        
        return response()->json(['success'=>[
            'message' => 'Invitation sent to user'
        ]],200);
    }
    
    /**
     * Respond to a sent invitation
     */
    public function respond(Request $request, $id){
        $request->validate([
            'token' => ['required'],
            'decision' => ['required']
        ]); 

        $token = $request->token;
        $decision = $request->decision; //'accept' or 'deny 

        $invitation = $this->invitation->find($id);

        // check if invitation belongs to this user
        $this->authorize('respond', $invitation);

        // check the token's matched
        if($invitation->token != $token){
            return response()->json(['error' => [
                'message' => "Invalid token"
            ]], 403);
        }

        // check if accepted
        if($decision != 'deny'){
            $this->invitation->addUserToTeam($invitation->team, auth('api')->id());
        }
        
        $invitation->delete();
        
        return response()->json(['success'=>[
            'message' => "You have been joined the team "
        ]], 200);
    }

    /**
     * Delete an invitation
     */
    public function destroy($id){
        $invitation = $this->invitation->find($id);

        $this->authorize('delete', $invitation);

        $invitation->delete();

        return response()->json(['success' => [
            'message' => "Invitation is deleted successfully."
        ]],200);
    }

    /**
     *  Remove a member from the team
     */
    public function removeFromTeam($team_id, $user_id){

        $team = $this->team->find($team_id);
        $user = $this->user->find($user_id);

        // check the user is the owner of the team or not
        if($user->isOwner($team)){
            return response()->json(['error' => [
                "message" => "You are the owner of the team."
            ]], 422);
        }

        if(! auth('api')->user()->isOwner($team) && auth('api')->id != $user_id){
            return response()->json(['error' => [
                "message" => "You cannot do this."
            ]], 422);
        }

        // detach the user from the team memeber list
        $this->invitation->removeUserToTeam($user_id, $team);

        return response()->json([
            'message' => "Removed successfully"
        ],200);
    }

    /**
     * Create invitation and send email
     */
    protected function createInvitations(bool $user_exist, Team $team, $email){
        $invitation = $this->invitation->create([
            'team_id' => $team->id,
            'sender_id' => auth('api')->id(),
            'recipient_email' => $email,
            'token' => md5(uniqid(microtime())),
        ]); 
        Mail::to($email)
                ->send(new SendInvitationToJoinTeam($invitation, $user_exist));

    }
}
