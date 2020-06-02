<?php

namespace App\Repositories\Eloquent;

use App\models\Design;
use App\Repositories\Contracts\DesignContract;

class DesignRepository extends BaseRepository implements DesignContract
{   
    public function model(){
        return Design::class;
    }

    public function applyTags($id, array $tags){
        $design = $this->find($id);
        $design->retag($tags);
    }
}