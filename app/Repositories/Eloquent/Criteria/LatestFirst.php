<?php

namespace App\Repositories\Eloquent\Criteria;

use App\Repositories\Criteria\Criteria;

class LatestFirst implements Criteria
{
    public function apply($model){
        return $model->latest();
    }
}