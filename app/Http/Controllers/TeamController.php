<?php

namespace App\Http\Controllers;

use App\Models\Team;
use Illuminate\Http\Request;
use Keygen\Keygen;

class TeamController extends Controller {

    public function createTeam(Request $request){
        $request->validate([
            'name' => 'required|max:255',
            'team_number' => 'required|numeric|unique:teams,team_number'
        ]);
        $id = Keygen::alphanum(15)->generate();
        $team = Team::create([
            'id' => $id,
            'name' => $request->get('name'),
            'team_number' => $request->get('team_number'),
            'owner_id' => \Auth::user()->id
        ]);
        // Create a membership for the current user
        $team->members()->create([
            'user_email' => \Auth::user()->email,
            'id' => Keygen::alphanum(15)->generate(),
            'pending' => false
        ]);
        return $team;
    }

    public function getTeams(Request $request){
        $teams = array();
        foreach($request->user()->teams as $team){
            $teams[] = $team->team;
        }
        return $teams;
    }
}
