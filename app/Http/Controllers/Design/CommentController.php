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

    /**
     *  Update a comment
     *  @param Request
     *  @param Integer
     *  @return Object
     */
    public function update($id, Request $request){

        $comment = $this->comment->find($id);
        $this->authorize('update',$comment);

        $this->validator($request);
        $comment = $this->comment->update($id, [
            'body' => $request->body,
            'user_id' => auth('api')->user()->id
        ]);

        return new CommentResource($comment);
    }

    /**
     *  Delete a comment
     *  @param Integer
     */
    public function destroy($id){
        $comment = $this->comment->find($id);
        $this->authorize('delete',$comment);

        $this->comment->delete($id);

        return response()->json(['errors'=>[
            'message' => "Comment deleted successfully"
        ]],200);
    }

    /**
     * Like a design
     * @return Response
     */
    public function like($id){
        $this->comment->like($id);

        return response()->json(['success' => [
            'message'=>"Successful"
        ]], 200);
    }

    /**
     *  check if the user liked the design
     */
    public function checkIfUserHasLiked($comment_id){
        $isLiked = $this->comment->isLikedByUser($comment_id);
        return response()->json([
            'liked' => $isLiked
        ], 200);
    }

}
