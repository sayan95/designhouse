<?php

namespace App\Repositories\Contracts ; 

interface CommentContract 
{
    public function like($comment_id);
    public function isLikedByUser($comment_id);
}