<?php

namespace App\Repositories\Contracts ;

use Illuminate\Http\Request;



interface DesignContract 
{
    public function applyTags($id, array $tags);
    public function addComment($design_id, array $data);
    public function like($design_id);
    public function isLikedByUser($design_id);
    public function search(Request $request);
}