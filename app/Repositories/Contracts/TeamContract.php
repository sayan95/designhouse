<?php 

namespace App\Repositories\Contracts;

interface TeamContract
{
    public function findBySlug($slug);
    public function fetchUserTeams();
}