<?php

namespace App\Http\Controllers\Design;

use App\models\Design;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\DesignResource;

class UpdateController extends Controller
{
    // validate the request
    protected function validates(Request $request, $id){
        $request->validate([
            'title' => ['required','unique:designs,title,'.$id],
            'description' => ['required','string','min:20','max:140'],
            'tags' => ['required']
        ]);
    }

    public function update(Request $request, $id){
        $this->validates($request, $id);

        $design = Design::findOrFail($id);

        $this->authorize('update', $design);

        $design->update([
            'title' => $request->title,
            'description' => $request->description,
            'slug' => Str::slug($request->title), 
            'is_live' => ! $design->upload_successful ? false : true,  
        ]);
        
        // apply tag to the design 
        $design->retag($request->tags);
        
        return new DesignResource($design);

    }
}
