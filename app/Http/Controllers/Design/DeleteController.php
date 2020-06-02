<?php

namespace App\Http\Controllers\Design;

use App\models\Design;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\Cache\Store;
use Illuminate\Support\Facades\Storage;
use App\Repositories\Contracts \DesignContract;

class DeleteController extends Controller
{

    protected $design;
    
    /**
     * Injection
     */
    public function __construct(DesignContract $design){
        $this->design = $design;
    }


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
        $design = $this->design->find($id);

        $this->authorize('delete', $design);
        
        $this->deleteAssoc($design); 
        
        $this->design->delete($id);
        
        return response()->json(['success'=>[
            'message' => 'The design is deleted successfuly'
        ]], 200);
    }
}
