<?php

namespace App\Http\Controllers\Design;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\DesignResource;
use App\Http\Resources\UserResource;
use App\Repositories\Contracts\DesignContract;

use App\Repositories\Eloquent\Criteria\{
    LatestFirst,
    AllLive,
    EagerLoad,
    ForUser
};

class DesignController extends Controller
{
    protected $design;

    /**
    *  Dependency injection
    */
    public function __construct(DesignContract $design)
    {
        $this->design = $design;
    }

    /**
     *  get all the designs
     *  @return Collection
     */
    public function index(){

        $designs = $this->design->withCriterias([
            new LatestFirst(), 
            new AllLive(),
            new ForUser(1),
            new EagerLoad(['user','comments'])
        ])->all();
        return DesignResource::collection($designs);
    }
    /**
     *  find a design by id
     *  @return object
     */
    public function findById($id){
        $design = $this->design->withCriterias([
            new EagerLoad(['user','comments'])
        ])->find($id);
        return new DesignResource($design);
    }
 

    /**
     * Like a design
     * @return Response
     */
    public function like($id){
        $this->design->like($id);

        return response()->json(['success' => [
            'message'=>"Successful"
        ]], 200);
    }

    /**
     *  check if the user liked the design
     */
    public function checkIfUserHasLiked($design_id){
        $isLiked =  $this->design->isLikedByUser($design_id);
        return response()->json([
            'liked' => $isLiked
        ], 200);
    }

    /**
     *  search design
     */
    public function search(Request $request){
        $designs = $this->design->search($request);
        return DesignResource::collection($designs);
    }

    /**
     * Get design by its slug
     */
    public function findBySlug($slug){
        $design = $this->design->withCriterias([
            new AllLive
        ])->findWhereFirst('slug', $slug);
        return new DesignResource($design);
    }

    /**
     *  Get design for team
     */
    public function getForTeam($team_id){
        $designs = $this->design->withCriterias([
            new AllLive  
        ])->findWhere('team_id', $team_id);
        return  DesignResource::collection($designs);
    }

    /**
     *  Get designs for a user
     */
    public function getForUser($user_id){
        $designs = $this->design->withCriterias([
            new AllLive
        ])->findWhere('user_id', $user_id);

        return DesignResource::collection($designs);
    }
}
