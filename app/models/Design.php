<?php

namespace App\models;

use App\models\User;
use Cviebrock\EloquentTaggable\Taggable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Design extends Model
{
    use Taggable;
    
    protected $fillable = [
        'user_id',
        'image',
        'title',
        'description',
        'slug',
        'close_to_comment',
        'is_live',
        'upload_successful',
        'disk'
    ];

    public function user(){
        return $this->belongsTo(User::class);
    } 

    public function comments(){
        return $this->morphMany(Comment::class, 'commentable')
                    ->orderBy('created_at','asc');
    }

    public function getImagesAttribute(){
        return [
            'thumbnail' => $this->getImagePath('thumbnail'),
            'original' => $this->getImagePath('original'),
            'large' => $this->getImagePath('large')
        ];
    }

    protected function getImagePath($size){
        return Storage::disk($this->disk)
                        ->url("uploads/designs/{$size}/".$this->image);
    }
}
