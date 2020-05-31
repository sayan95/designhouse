<?php

namespace App\Http\Controllers\Design;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UploadController extends Controller
{
    public function uplload(Request $request){
        $request->validate([
            'image' => ['required','mimes:png,jpg,jpeg,gif,bmp','max:2048']
        ]);
    }
}
