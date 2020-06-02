<?php

namespace App\Http\Controllers\Design;

use App\Http\Controllers\Controller;
use App\Http\Resources\DesignResource;
use App\models\Design;
use Illuminate\Http\Request;

class DesignController extends Controller
{
   public function index(){
       $designs = Design::all();
       return DesignResource::collection($designs);
   }
}
