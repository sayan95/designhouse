<?php

namespace App\Http\Controllers\Design;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\DesignResource;
use App\Repositories\Contracts\DesignContract;

use App\Repositories\Eloquent\Criteria\{
    LatestFirst,
    AllLive,
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
            new ForUser(1)
        ])->all();
        return DesignResource::collection($designs);
    }
    /**
     *  find a design by id
     *  @return object
     */
    public function findById($id){
        $design = $this->design->find($id);
        return new DesignResource($design);
    }

    /**
    *  Find designs by col name and its value
    *  @return Collection  
    */
    public function findByColName($col, $val){
        $designs = $this->design->findWhere($col, $val);
        return DesignResource::collection($designs);
    }

    /**
     * find a design by col name and its value
     * @return Object
     */
    public function findByColNameFirst($col, $val){
        $design = $this->design->findWhereFirst($col, $val);
        return new DesignResource($design);
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
