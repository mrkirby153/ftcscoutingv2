<?php

namespace App\Http\Controllers;

use App\Models\Team;
use Illuminate\Http\Request;
use Keygen\Keygen;

class TeamController extends Controller {

    public function createTeam(Request $request){
        $id = Keygen::alphanum(15)->generate();
        $team = Team::create([
            'id' => $id,
            'name' => $request->get('name'),
            'team_number' => $request->get('team_number'),
            'owner_id' => \Auth::user()->id
        ]);
        return $team;
    }
}
