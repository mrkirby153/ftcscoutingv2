<?php

namespace App\Policies;

use App\Models\Surveys\Response;
use App\Models\Team;
use App\Models\TeamMember;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ResponsePolicy extends BasePolicy {
    use HandlesAuthorization, GrantsSuperAdmin;

    /**
     * Determine whether the user can view the response.
     *
     * @param  \App\User $user
     * @param  \App\Models\Surveys\Response $response
     * @return mixed
     */
    public function view(User $user, Response $response) {
        return $this->isTeamMember($user, $response->survey->team_id);
    }

    /**
     * Determine whether the user can create responses.
     *
     * @param  \App\User $user
     * @return mixed
     */
    public function create(User $user, Team $team) {
        return $this->isTeamMember($user, $team->id);
    }

    /**
     * Determine whether the user can update the response.
     *
     * @param  \App\User $user
     * @param \App\Models\Surveys\Response $response
     * @return mixed
     */
    public function update(User $user, Response $response) {
        return false; // Responses should be considered final when submitting
    }

    /**
     * Determine whether the user can delete the response.
     *
     * @param  \App\User $user
     * @param  \App\Models\Surveys\Response $response
     * @return mixed
     */
    public function delete(User $user, Response $response) {
        return $response->submitter == $user || $this->isTeamAdmin($user, $response->survey->team_id);
    }
}
