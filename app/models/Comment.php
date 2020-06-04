<?php

namespace App\models;

use App\models\Traits\Likeable;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use Likeable;
    
    protected $fillable = [
        'body',
        'user_id'
    ];

    public function commentable(){
        return $this->morphTo();
    }

    public function user(){
        return $this->belongsTo(User::class);
    }
}
