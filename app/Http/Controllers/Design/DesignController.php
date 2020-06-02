<?php

namespace App\Http\Controllers\Design;

use App\Http\Controllers\Controller;
use App\Http\Resources\DesignResource;
use App\models\Design;
use App\Repositories\Contracts\DesignContract;
use Illuminate\Http\Request;

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

   public function index(){

       $designs = $this->design->all();
       return DesignResource::collection($designs);
   }
}
