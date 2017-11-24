<?php

namespace App\Policies;

use App\Models\Team;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TeamPolicy extends BasePolicy {
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the team.
     *
     * @param  \App\User $user
     * @param  \App\Models\Team $team
     * @return mixed
     */
    public function view(User $user, Team $team) {
        return $this->isTeamMember($user, $team);
    }

    /**
     * Determine whether the user can create teams.
     *
     * @param  \App\User $user
     * @return mixed
     */
    public function create(User $user) {
        return true;
    }

    /**
     * Determine whether the user can update the team.
     *
     * @param  \App\User $user
     * @param  \App\Models\Team $team
     * @return mixed
     */
    public function update(User $user, Team $team) {
        return $this->isTeamAdmin($user, $team);
    }

    /**
     * Determine whether the user can delete the team.
     *
     * @param  \App\User $user
     * @param  \App\Models\Team $team
     * @return mixed
     */
    public function delete(User $user, Team $team) {
        return $team->owner->id == $user->id; // Only the owner can delete the team
    }

    /**
     * Determine whether the user can invite users to the team
     *
     * @param User $user
     * @return mixed
     */
    public function invite(User $user, Team $team){
        return $this->isTeamAdmin($user, $team);
    }
}
