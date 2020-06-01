<?php

namespace App\Http\Controllers\Design;

use App\models\Design;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UpdateController extends Controller
{
    // validate the request
    protected function validates(Request $request, $id){
        $request->validate([
            'title' => ['required','unique:designs,title,'.$id],
            'description' => ['required','string','min:20','max:140'],
        ]);
    }

    public function update(Request $request, $id){
        $this->validates($request, $id);

        $design = Design::find($id);

        $this->authorize('update', $design);

        $design->update([
            'title' => $request->title,
            'description' => $request->description,
            'slug' => Str::slug($request->title), 
            'is_live' => ! $design->upload_successful ? false : true,  
        ]);
        
        return response()->json(['sucess'=>[
            'message' => 'Design is updated successfully',
            'design' => $design
        ]], 200);
    }
}
