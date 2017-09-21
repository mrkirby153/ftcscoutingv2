<?php

namespace App\Http\Controllers;

use App\Models\Team;
use App\Models\TeamMember;
use Illuminate\Http\Request;
use Keygen\Keygen;

class TeamMemberController extends Controller {


    public function inviteMember(Request $request, Team $team) {
        $request->validate([
            'email' => 'required'
        ]);
        $m = $team->members()->create([
            'id' => Keygen::alphanum(15)->generate(),
            'user_email' => $request->get('email')
        ]);
        // TODO 9/21/17: Send an email to the invited user
        return $m;
    }

    public function removeMember(TeamMember $member) {
        $member->delete();
    }
}
