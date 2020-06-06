<?php

namespace App\Repositories\Eloquent;

use App\models\Chat;
use App\Repositories\Contracts\ChatContract;
use App\Repositories\Eloquent\BaseRepository;

class ChatRepository extends BaseRepository implements ChatContract
{
    public function model(){
        return Chat::class;
    }

    public function createParticipants($chat_id, array $data){
        $chat = $this->model->find($chat_id);
        $chat->participants()->sync($data);
    }
}