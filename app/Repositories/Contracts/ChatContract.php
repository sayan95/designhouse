<?php 

namespace App\Repositories\Contracts;

interface ChatContract
{
    public function createParticipants($chat_id,array $data);
}