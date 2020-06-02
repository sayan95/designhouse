<?php

namespace App\Http\Controllers\Design;

use App\models\Design;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\DesignResource;
use App\Repositories\Contracts \DesignContract;

class UpdateController extends Controller
{

    protected $design;
    /**
     * Injection
     */
    public function __construct(DesignContract $design){
        $this->design = $design;
    }

    
    // validate the request
    protected function validates(Request $request, $id){
        $request->validate([
            'title' => ['required','unique:designs,title,'.$id],
            'description' => ['required','string','min:20','max:140'],
            'tags' => ['required']
        ]);
    }

    public function update(Request $request, $id){
        

        $design = $this->design->find($id);
        $this->authorize('update', $design);
        $this->validates($request, $id);

        $this->design->update($id,[
            'title' => $request->title,
            'description' => $request->description,
            'slug' => Str::slug($request->title), 
            'is_live' => ! $design->upload_successful ? false : true,  
        ]);
        
        // apply tag to the design 
        $this->design->applyTags($id, $request->tags);
        
        return new DesignResource($design);

    }
}
