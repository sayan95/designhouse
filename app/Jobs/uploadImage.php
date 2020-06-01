<?php

namespace App\Jobs;

use Illuminate\Support\Facades\File;
use Exception;
use App\models\Design;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Log;
use Intervention\Image\Facades\Image;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class UploadImage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $design;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Design $design)
    {
        $this->design = $design;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        
        $disk = $this->design->disk;
        $file_name = $this->design->image;
        $original_file = storage_path('\uploads\original\\'.$file_name);
        try{
            // create the large image and save to temp location
            Image::make($original_file)
                ->fit(800, 600, function($constraints){
                    $constraints->aspectRatio();
                })
                ->save($large = storage_path('\uploads\large\\'.$file_name));

            // create the thumbnail    
            Image::make($original_file)
                ->fit(250, 200, function($constraints){
                    $constraints->aspectRatio();
                })
                ->save($thumbnail = storage_path('\uploads\thumbnail\\'.$file_name));
            
            // store images to permanent storage 
            // original image
            if(Storage::disk($disk)
            ->put('uploads/designs/original/'.$this->design->image, fopen($original_file,'r+'))){
                File::delete($original_file);
            };

            // large image
            if(Storage::disk($disk)
            ->put('uploads/designs/large/'.$this->design->image, fopen($large,'r+'))){
                File::delete($large);
            };

            // thumbnail image
            if(Storage::disk($disk)
            ->put('uploads/designs/thumbnail/'.$this->design->image, fopen($thumbnail,'r+'))){
                File::delete($thumbnail);
            };

            // update the database record with success fag
            $this->design->update([
                'upload_successful' => true
            ]);

        }catch(Exception $e){
            Log::error($e->getMessage());
        }
    }
}
