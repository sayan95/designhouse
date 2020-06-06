<?php

namespace App\Http\Controllers\Teams;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\TeamsResource;
use App\Repositories\Contracts\TeamContract;
use App\Repositories\Eloquent\Criteria\EagerLoad;

class TeamsController extends Controller
{
    protected $team;
    
    /**
     *  Dependency Injection
     */
    public function __construct(TeamContract $team)
    {
        $this->team = $team;
    }

    /**
     *  get all the teams
     *  @return JSON
     */
    public function index(){
        $teams = $this->team->withCriterias([

        ])->all();

        return TeamsResource::collection($teams);
    }

    /** 
     * Find a team by its id
     * @param Integer
     * @return JSON
    */
    public function findById($id){

        $team = $this->team->find($id);

        return new TeamsResource($team);
    }

    /** 
     * Find a team by its slug for public view
     * @param String
     * @return JSON
    */
    public function findBySlug($slug){
        //
    }

    /** 
     * Fetch the teams that the current authenticated user belongs to
     * @return JSON
    */
    public function fetchUserTeams(){
        $teams = $this->team->fetchUserTeams();
        return TeamsResource::collection($teams);
    }

    /**
     *  Validates incoming request
     *  @param Request
     *  @return JSON
     */
    protected function validator(Request $request){
        $request->validate([
            'name' => ['required', 'max:50', 'string', 'unique:teams,name']
        ]);
    }

    /**
     *  Save team to database
     *  @param Request
     *  @return JSON
     */
    public function store(Request $request){
        $this->validator($request);

        $team = $this->team->create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'owner_id' => auth('api')->id()
        ]);
        
        return new TeamsResource($team);
    }

    /**
     *  Update team information
     *  @param Request
     *  @param Integer
     *  @return JSON
     */
    public function update(Request $request , $id){

        $team = $this->team->find($id);
        $this->authorize('update', $team);

        $request->validate([
            'name' => ['required', 'max:50', 'string', 'unique:teams,name,'.$id]
        ]);
        
        $team = $this->team->update($id, [
            'name' => $request->name,
            'slug' => Str::slug($request->name),
        ]);
        return new TeamsResource($team);
    }

    /**
     *  Delete Team 
     *  @param Integer
     */
    public function destroy($id){

        $team = $this->team->find($id);
        $this->authorize('delete', $team);

        $this->team->delete($id);

        return response()->json(['succcess'=>[
            'message' => "Team deleted successfully"
        ]], 200);
    }
}
