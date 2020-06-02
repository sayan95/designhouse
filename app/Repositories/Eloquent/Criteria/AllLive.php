<?php

namespace App\Repositories\Eloquent\Criteria;

use App\Repositories\Criteria\Criteria;

class AllLive implements Criteria
{
    public function apply($model){
        return $model->where('is_live',true);
    }
}