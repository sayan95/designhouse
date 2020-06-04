<?php

namespace App\Repositories\Eloquent;

use App\models\Design;
use App\Repositories\Contracts\DesignContract;

class DesignRepository extends BaseRepository implements DesignContract
{   
    public function model(){
        return Design::class;
    }

    public function applyTags($id, array $tags){
        $design = $this->find($id);
        $design->retag($tags);
    }

    public function addComment($design_id, array $data){
        $design = $this->find($design_id);

        $comment = $design->comments()->create($data);

        return $comment;
    }

    public function like($design_id){
        $design = $this->model->findOrFail($design_id);

        if($design->isLikedByUser(auth('api')->id())){
            $design->unlike();
        }else{
            $design->like();
        }
    }

    public function isLikedByUser($design_id){
        $design = $this->model->findOrFail($design_id);
        return $design->isLikedByUser(auth('api')->id());
    }   
}