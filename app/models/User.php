<?php

namespace App\models;


use App\Notifications\VerifyEmail;
use App\Notifications\ResetPassword;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Grimzy\LaravelMysqlSpatial\Eloquent\SpatialTrait;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements JWTSubject, MustVerifyEmail
{
    use SpatialTrait, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'tagline', 'about', 'username',
        'location', 'formatted_address', 'available_to_hire'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    public function sendEmailVerificationNotification()
    {
        $this->notify(new VerifyEmail);
    }

   public function sendPasswordResetNotification($token)
   {
       $this->notify(new ResetPassword($token));
   }



    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    /**
     *  Spatial fields
     */
    protected $spatialFields = [
        'location'
    ];

    /**
     *  Eloquent ORM relations
     */

     // a user can have many designs
    public function designs(){
        return $this->hasMany(Design::class);
    }

    // a user can comment multiple times 
    public function comments(){
        return $this->hasMany(Comment::class);
    }

    // a user can belongs to multiple teams
    public function teams(){
        return $this->belongsToMany(Team::class)
                ->withTimestamps();
    }

    // auser can own multiple teams
    public function ownedTeams(){
        return $this->teams()
                ->where('owner_id',$this->id);
    }

    // check if the said user is the owner of the given team
    public function isOwner(Team $team){
        return (bool)$this->teams()
                ->where(['id'=> $team->id,
                         'owner_id' => $this->id])
                ->count();
    }

    // user's invitations
    public function invitations(){
        return $this->hasMany(Invitation::class, 'recipient_email', 'email');
    }
    



    
}
