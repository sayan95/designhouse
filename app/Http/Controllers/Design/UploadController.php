<?php

namespace App\Http\Controllers\Design;


use App\Jobs\UploadImage;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UploadController extends Controller
{
    // validate incoming request for image
    protected function validateImage(Request $request){
        $request->validate([
            'image' => ['required','mimes:png,jpg,jpeg,gif,bmp','max:2048']
        ]);
    }

    // get the image
    protected function getImage(Request $request){
        return $request->file('image');
    }

    // get image path
    protected function getImagePath($image){
        return $image->getPathName();
    }

    /**
     * get original file name and replace any spaces with _
     * Business card.png ---> timestamp()_business_card.png
     */
    protected function getImageOriginalName($image){
        return time().'_'.preg_replace('/\s+/','_',strtolower($image->getClientOriginalName()));
    }

    /**
     * move the image to the temporary location
     */
    protected function moveToTempLocation($image,$filename){
        $image->storeAs('uploads/original', $filename, 'tmp');
    }
    
    /**
     *  Add design to the database
     */
    protected function createDesign($filename){
        return auth()->user()->designs()->create([
            'image' => $filename,
            'disk' => config('site.upload_disk'),
        ]);
    }

    /**
     *  Dispatch a job to manipulate image
     */
    protected function imageManipulate($design){
        /**
         * job UploadImage
         */
        UploadImage::dispatch($design);
    }

    /**
     * Upload a design
     */
    public function upload(Request $request){
        
        $this->validateImage($request);
        
        $image = $this->getImage($request);

        $image_path = $this->getImagePath($image);

        $filename = $this->getImageOriginalName($image);
            
        
        $tmp = $this->moveToTempLocation($image,$filename);

        $design = $this->createDesign($filename);

        $this->imageManipulate($design);

        return response()->json(['success'=>[
            'message' => "Design is being uploaded",
            'design' => $design
        ]], 200);
    }
}
