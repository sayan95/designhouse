<?php

namespace App\models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    /**
     *  Eloquent ORM relationships
     */

    public function participants(){
        return $this->belongsToMany(User::class, 'participants');
    }

    public function messages(){
        return $this->hasMany(Message::class);
    }

    /**
     *  helper methods
     */
    
     // Get the latest message of the chat
    public function getLatestMessageAttribute(){
        return $this->messages()->latest()->first();
    }

    // check the message is Unread by the user
    public function isUnreadForUser($user_id){
        return (bool)$this->messages()
                    ->whereNull('last_read')
                    ->where('user_id','<>',$user_id)
                    ->count(); 
    } 

    // mark chat as read
    public function markAsReadForUser($user_id){
        $this->messages()
            ->whereNull('last_read')
            ->where('user_id','<>',$user_id)
            ->update([
                'last_read' => Carbon::now()
            ]);
    }
}
