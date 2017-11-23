<?php


namespace App\Policies;


use App\Models\Team;
use App\Models\TeamMember;
use App\User;

class BasePolicy {

    /**
     * @var Team
     */
    protected $team;

    /**
     * @var TeamMember
     */
    protected $member;

    public function __construct(Team $team, TeamMember $member) {
        $this->team = $team;
        $this->member = $member;
    }


    /**
     * Checks if the user is on a team
     * @param User $user The user
     * @param $teamId int|Team The team ID
     * @return bool
     */
    protected function isTeamMember(User $user, $teamId) {
        if($teamId instanceof Team){
            $teamId = $teamId->id;
        }
        return $this->member->whereTeamId($teamId)->whereUserEmail($user->email)->exists();
    }


    protected function isTeamAdmin(User $user, Team $team){
        if(!$this->isTeamMember($user, $team->id))
            return false;
        if($team->owner->id == $user->id){
            return true;
        }
        // TODO 11/22/17: Add support for team admins
        return false;
    }
}