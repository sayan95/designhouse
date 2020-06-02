<?php

namespace App\Repositories\Eloquent;

use Exception;
use App\Exceptions\ModelNotDefinedException;
use App\Repositories\Contracts\BaseContract;

abstract class BaseRepository implements BaseContract
{
    protected $model;

    public function __construct()
    {
        $this->model = $this->getModelClass();
    }

    protected function getModelClass(){
        if ( ! method_exists($this, 'model')){
            throw new ModelNotDefinedException("No model defined");
        }   

        return app()->make($this->model());
    }

    /**
    * Base repository for tha all common database operations for all models
    * like all, create , update, delete
    */
    public function all()
    {
        return $this->model->all();
    }
}