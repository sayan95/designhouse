<?php

namespace App\Http\Controllers\Design;

use App\models\Design;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\CommentResource;
use App\Repositories\Contracts \{
    CommentContract,
    DesignContract
};

class CommentController extends Controller
{
    protected $comment, $design ;
    
    /**
     *  Injection
     */
    public function __construct(CommentContract $comment, DesignContract $design)
    {
        $this->comment = $comment;
        $this->design = $design;
    }

    /**
     *  validate the comment request
     *  @param Request
     *  @return JSON
     */
    protected function validator(Request $request){
        $request->validate([
            'body' => ['required']
        ]);
    }

    /**
     *  Store a comment
     *  @param Request
     *  @param Integer 
     *  @return Object
     */

    public function store(Request $request, $design_id){
        $this->validator($request);

        $comment =  $this->design->addComment($design_id, [
            'body' => $request->body,
            'user_id' => auth('api')->user()->id
        ]);

        return new CommentResource($comment);
    }
}
