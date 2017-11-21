<?php

namespace App\Http\Controllers;

use App\Models\Team;
use App\Models\TeamMember;
use Illuminate\Http\Request;
use Keygen\Keygen;

class TeamController extends Controller {

    /**
     * @var Team
     */
    private $team;

    /**
     * @var TeamMember
     */
    private $members;


    public function __construct(Team $team, TeamMember $member) {
        $this->team = $team;
        $this->members = $member;
    }

    public function createTeam(Request $request) {
        $request->validate([
            'name' => 'required|max:255',
            'team_number' => 'required|numeric|unique:teams,team_number'
        ]);
        $id = Keygen::alphanum(15)->generate();
        $team = $this->team->create([
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

    public function getTeams(Request $request) {
        return \DB::table('team_members')->where('user_email', '=', $request->user()->email)
            ->join('teams', 'team_members.team_id', '=', 'teams.id')
            ->orderBy('team_number', 'ASC')
            ->get(['team_members.id as member_id', 'teams.id as team_id', 'pending as pending_accept', 'team_number', 'owner_id', 'name']);
    }

    public function getTeam(Team $team) {
        return $team->with('members')->with('members.user')->first();
    }
}
