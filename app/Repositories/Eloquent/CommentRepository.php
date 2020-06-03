<?php

namespace App\Repositories\Eloquent;

use App\models\Comment;
use App\Repositories\Contracts\CommentContract;

class CommentRepository extends BaseRepository implements CommentContract
{
    public function model(){
        return Comment::class;
    }
}