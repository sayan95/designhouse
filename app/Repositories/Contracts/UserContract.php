<?php

namespace App\Repositories\Contracts;

interface UserContract
{
    public function findByEmail($email);
}