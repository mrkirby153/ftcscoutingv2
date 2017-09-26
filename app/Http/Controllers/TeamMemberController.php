<?php

namespace App\Http\Controllers;

use App\Mail\TeamInvite;
use App\Models\Team;
use App\Models\TeamMember;
use Illuminate\Http\Request;
use Keygen\Keygen;

class TeamMemberController extends Controller {


    public function inviteMember(Request $request, Team $team) {
        $request->validate([
            'email' => 'required|email'
        ]);
        if($team->members()->whereUserEmail($request->get('email'))->count() > 0){
            return response()->json([
                'errors' => [
                    'email' => ['That user has already been invited!']
                ]
            ], 422);
        }
        $m = $team->members()->create([
            'id' => Keygen::alphanum(15)->generate(),
            'user_email' => $request->get('email')
        ]);
        \Mail::to($request->get('email'))->queue(new TeamInvite($team, $request->user(), $m));
        return $m;
    }

    public function removeMember(TeamMember $member) {
        $member->delete();
    }

    public function acceptInvite(Request $request, TeamMember $member) {
        $member->pending = false;
        $member->save();
        if($request->expectsJson()){
            return $member;
        } else {
            return redirect('/team/'.$member->team_id);
        }
    }
}
