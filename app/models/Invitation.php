<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class Invitation extends Model
{
    /**
     *  Allowed mass assignments
     */
    protected $fillable = [
        'recipient_email',
        'sender_id',
        'team_id',
        'token'
    ];

    /**
     * Eloquent ORM relation ships
     */

     // Invitaion belongs to
    public function team(){
        return $this->belongsTo(Team::class);
    }

    // recipient of the invitation
    public function recipient(){
        return $this->hasOne(User::class, 'email', 'recipient_email');
    }

    // sender of the invitation
    public function sender(){
        return $this->hasOne(User::class, 'id', 'sender_id');
    }
}
