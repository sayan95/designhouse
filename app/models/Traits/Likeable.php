<?php

namespace App\models\Traits;

use App\models\Like;

trait Likeable 
{
    public static function bootLikeable(){
        static::deleting(function($model){
            $model->removeLikes();
        });
    }

    public function removeLikes(){
        if($this->likes()->count()){
            $this->likes()->delete();
        }
    }

    public function likes(){
        return $this->morphMany(Like::class, 'likeable');
    }

    // like facility
    public function like()
    {
        // check if the user is authenticated
        if(! auth('api')->check()){
            return ;
        }

        // check if the current user is already liked
        if($this->isLikedByUser(auth('api')->id())){
            return;
        }

        $this->likes()->create([
            'user_id' => auth('api')->id()
        ]);
    }

    // unlike facility
    public function unlike(){
        // check if the user is authenticated
        if(! auth('api')->check()){
            return ;
        }

        // check if the current user is already liked
        if(! $this->isLikedByUser(auth('api')->id())){
            return;
        }

        $this->likes()
                ->where('user_id',auth('api')->id())
                ->delete();
    }

    public function isLikedByUser($user_id){
        return (bool)$this->likes()->where('user_id',$user_id)
                            ->count();
    }
}