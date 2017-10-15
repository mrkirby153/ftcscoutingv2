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
            'email' => 'required'
        ]);
        $toInvite = array();
        foreach(explode("\n", $request->get('email')) as $email){
            if($team->members()->whereUserEmail($email)->count() < 1 && !array_has($toInvite, $email)){
                $toInvite[] = $email;
            }
        }

        \Log::debug("Inviting ".\GuzzleHttp\json_encode($toInvite));

        $toReturn = array();
        foreach($toInvite as $email) {
            $m = $team->members()->create([
                'id' => Keygen::alphanum(15)->generate(),
                'user_email' => $email
            ]);
            \Mail::to($email)->queue(new TeamInvite($team, $request->user(), $m));
            $toReturn[] = $m;
        }
        return $toReturn;
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
