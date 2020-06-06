<?php

namespace App\Http\Controllers\Chats;

use App\Http\Controllers\Controller;
use App\Http\Resources\MessageResource;
use App\Repositories\Contracts\ChatContract;
use App\Repositories\Contracts\MessageContract;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    protected $chat, $message;

    /**
     *  Dependency injection
     */
    public function __construct(ChatContract $chat, MessageContract $message)
    {
        $this->chat = $chat;
        $this->message = $message;
    }

    /**
     *  Send a message 
     */
    public function sendMessage(Request $request){
        // validate the request
        $request->validate([
            'recipient' => ['required'],
            'body' => ['required']
        ]);   

        $recipient = $request->recipient;
        $user = auth('api')->user();
        $body = $request->body;

        // check the current user has an exisiting chat with the recipient
        $chat = $user->getChatWithUser($recipient);

        if(!$chat){
            $chat = $this->chat->create([]);
            $this->chat->createParticipants($chat->id, [$user->id,$recipient]);
        }
        

        // add the message to the chat
        $message = $this->message->create([
            'user_id' => $user->id,
            'chat_id' => $chat->id,
            'body' => $body,
            'last_read' => null 
        ]); 

        return new MessageResource($message);

    }

    /** 
     * get all the chats of the current user
    */
    public function getUserChats(){

    }

    /**
     * Get all the messages of a specific chat
     */
    public function getChatMessages($chat_id){

    }

    /**
     * Make a chat as read
     */
    public function markAsRead($chat_id){}

    /**
     *  Delete a messagefrom the chat
     */
    public function destroyMessage($mid){}
}
