<?php

namespace App\Repositories\Eloquent;

use App\models\Comment;
use App\Repositories\Contracts\CommentContract;

class CommentRepository extends BaseRepository implements CommentContract
{
    public function model(){
        return Comment::class;
    }

    public function like($comment_id){
        $comment = $this->model->findOrFail($comment_id);

        if($comment->isLikedByUser(auth('api')->id())){
            $comment->unlike();
        }else{
            $comment->like();
        }
    }

    public function isLikedByUser($comment_id){
        $comment = $this->model->findOrFail($comment_id);
        return $comment->isLikedByUser(auth('api')->id());
    } 
}