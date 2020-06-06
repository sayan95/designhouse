<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class Team extends Model
{

    /**
     *  Fillable array
     *  allows mass assignments
     */
    protected $fillable = [
        'name',
        'owner_id',
        'slug'
    ];

    /**
     *  Observer
     */
    protected static function boot(){
        Parent::boot();

        // when a team is created add current user as a team member
        static::created(function($team){
            $team->members()->attach(auth('api')->id());
        });

        static::deleted(function($team){
            $team->members()->sync([]);
        });
    }

    /**
     * Eloquent ORM relationships
     */

     // team owner
    public function owner(){
        return $this->belongsTo(User::class, 'owner_id');
    }

    // a team belongs to many users
    public function members(){
        return $this->belongsToMany(User::class)
                ->withTimestamps();
    }

    // a user can add designs to a team.So a team can have many designs
    public function designs(){
        return $this->hasMany(Design::class);
    }

    // check the team has a perticular user
    public function hasUser(User $user){
        return $this->members()
                ->where('user_id', $user->id)
                ->first() ? true : false;
    }

    // a team has many invitations
    public function invitations(){
        return $this->hasMany(Invitation::class);
    }

    // checks for pending invitations
    public function hasPendingInvite($email){
        return (bool)$this->invitations()
                    ->where('recipient_email', $email)
                    ->count();
    }

}
