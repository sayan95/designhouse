<?php

namespace App\Repositories\Eloquent;

use App\models\User;
use Illuminate\Http\Request;
use Grimzy\LaravelMysqlSpatial\Types\Point;
use App\Repositories\Contracts\UserContract;

class UserRepository extends BaseRepository implements UserContract
{
    public function model(){
        return User::class;
    }

    public function findByEmail($email)
    {
        $user = $this->model->where('email', $email)->first();
        return $user;
    }

    public function search(Request $request){
        $query = (new $this->model)->newQuery();

        // only designers who have designs
        if($request->has_designs){
            $query->has('designs');
        }

        // check for available to hire 
        if($request->has_available_to_hire){
            $query->where('available_to_hire', true);
        }

        // Geographic serach
        $lat = $request->latitude;
        $lng = $request->longitude;
        $distance = $request->distance;
        $unit = $request->unit;

        if($lat && $lng){
            $point = new Point($lat, $lng);
            $unit == 'km'? $distance *= 1000 : $distance *= 1609.34;
            $query->distanceSphereExcludingSelf('location', $point, $distance);
        }

        // order by closest
        if($request->orderBy == 'closest'){
            $query->orderByDistanceSphere('location', $point, 'asc');
        }else if($request->orderBy == 'latest'){
            $query->latest();
        }else{
            $query->oldest();
        }
        
        return $query->get();
    }
}