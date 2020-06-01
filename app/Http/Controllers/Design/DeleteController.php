<?php

namespace App\Http\Controllers\Design;

use App\Http\Controllers\Controller;
use App\models\Design;
use Illuminate\Contracts\Cache\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DeleteController extends Controller
{
    /**
     *  delete the files associated with the database record from the filesystem
     */
    protected function deleteAssoc($design){
        foreach(['thumbnail','original','large'] as $size){
            // check if the file is in the storage
            if(Storage::disk($design->disk)
                ->exists("uploads/designs/{$size}/".$design->image)){

                Storage::disk($design->disk)->delete("uploads/designs/{$size}/".$design->image);
            
            }
        }
    }

    /**
     * Destroy a design
     */
    public function destroy($id){
        $design = Design::findOrFail($id);

        $this->authorize('delete', $design);

        $design->delete();

        $this->deleteAssoc($design); 
        
        return response()->json(['success'=>[
            'message' => 'The design is deleted successfuly'
        ]], 200);
    }
}
