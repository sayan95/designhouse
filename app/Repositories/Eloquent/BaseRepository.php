<?php

namespace App\Repositories\Eloquent;

use Exception;
use Illuminate\Support\Arr;
use App\Repositories\Criteria\Criteria;
use App\Repositories\Criteria\Criterias;
use App\Exceptions\ModelNotDefinedException;
use App\Repositories\Contracts\BaseContract;

abstract class BaseRepository implements BaseContract, Criterias
{
    protected $model;

    public function __construct()
    {
        $this->model = $this->getModelClass();
    }

    /**
     *  Base criteras
     */
    public function withCriterias(...$criterias)
    {
        $criterias = Arr::flatten($criterias);
        
        foreach($criterias as $criteria){
            $this->model = $criteria->apply($this->model);  
        }

        return $this;
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
        return $this->model->get();
    }

    public function find($id){
        return $this->model->findOrFail($id);
    }

    public function findWhere($col, $val){
        return $this->model->where($col, $val)->get();
    }

    public function findWhereFirst($col, $val){
        return $this->model->where($col, $val)->first();
    }

    public function paginate($noOfItem){
        return $this->model->paginate($noOfItem);
    }

    public function create(array $data){
        return $this->model->create($data);
    }

    public function update($id, array $data){
        $entity = $this->find($id);
        $entity->update($data);
        return $entity;
    }

    public function delete($id){
        $entity = $this->find($id);
        $entity->delete();
    }
}