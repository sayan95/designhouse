<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Message extends Model
{
    use SoftDeletes;

    protected $touches = ['chat'];
    /**
     *  Allowed mass assignments
     */
    protected $fillable = [
        'user_id',
        'chat_id',
        'body',
        'last_read'
    ];

    /**
     *  Eloquent ORM relations
     */
    public function chat(){
        return $this->belongsTo(Chat::class,'chat_id');
    }

    public function sender(){
        return $this->belongsTo(User::class,'user_id');
    }

}
