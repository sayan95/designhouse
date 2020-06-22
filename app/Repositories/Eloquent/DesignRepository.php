<?php

namespace App\Repositories\Eloquent;

use App\models\Design;
use Illuminate\Http\Request;
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

    public function search(Request $request){
        $query = (new $this->model)->newQuery();
        $query->where('is_live', true);
        
        // return designs with comments only
        if($request->has_comments){
            $query->has('comments');
        }

        // return designs assigned to teams
        if($request->has_team){
            $query->has('team');
        }

        // search title and description for provided string
        if($request->q){
            $query->where(function($q) use ($request){
                $q->where('title','like','%'.$request->q.'%')
                    ->orWhere('description','like','%'.$request->q.'%');
            });
        }

        // order by the query by likes or latest first
        if($request->orderBy=="likes"){
            $query->withCount("likes")
                    ->orderByDesc("likes_count");
        }else{
            $query->latest();
        } 

        return $query->get();
    }
}