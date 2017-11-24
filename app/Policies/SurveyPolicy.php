<?php

namespace App\Policies;

use App\Models\Surveys\Survey;
use App\Models\Team;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class SurveyPolicy extends BasePolicy {
    use HandlesAuthorization, GrantsSuperAdmin;

    /**
     * Determine whether the user can view the survey.
     *
     * @param  \App\User $user
     * @param  \App\Models\Surveys\Survey $survey
     * @return mixed
     */
    public function view(User $user, Survey $survey) {
        return $this->isTeamMember($user, $survey->team_id);
    }

    /**
     * Determine whether the user can create surveys.
     *
     * @param  \App\User $user
     * @return mixed
     */
    public function create(User $user, Team $team) {
        return  $this->isTeamAdmin($user, $team);
    }

    /**
     * Determine whether the user can update the survey.
     *
     * @param  \App\User $user
     * @param \App\Models\Surveys\Survey $survey
     * @return mixed
     */
    public function update(User $user, Survey $survey) {
        return $this->isTeamAdmin($user, $survey->team);
    }

    /**
     * Determine whether the user can delete the survey.
     *
     * @param  \App\User $user
     * @param  \App\Models\Surveys\Survey $survey $survey
     * @return mixed
     */
    public function delete(User $user, Survey $survey) {
        return $this->isTeamAdmin($user, $survey->team);
    }
}
