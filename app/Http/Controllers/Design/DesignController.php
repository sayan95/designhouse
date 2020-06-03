<?php

namespace App\Http\Controllers\Design;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\DesignResource;
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
    *  Find designs by col name and its value
    *  @return Collection  
    */
    public function findByColName($col, $val){
        $designs = $this->design->findWhere($col, $val);
        
        if($designs->count() > 0)
            return DesignResource::collection($designs);
        
        return response()->json(["error"=>[
            'message' => "No records waere found"
        ]], 500);
    }

    /**
     * find a design by col name and its value
     * @return Object
     */
    public function findByColNameFirst($col, $val){
        $design = $this->design->findWhereFirst($col, $val);
        if($design){
            return new DesignResource($design);
        }
        return response()->json(["error"=>[
            'message' => "No record was found"
        ]], 500);
    }

    /**
     *  Paginate a design collection
     *  @return Collection 
     */
    public function pagination($noOfItems){
        $designs = $this->design->paginate($noOfItems);
        return DesignResource::collection($designs);
    }
}
