<?php

namespace App\Mail;

use App\Models\Team;
use App\Models\TeamMember;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class TeamInvite extends Mailable {
    use Queueable, SerializesModels;

    /**
     * @var Team
     */
    public $team;

    /**
     * @var User
     */
    public $inviter;

    /**
     * @var TeamMember
     */
    public $invite;

    /**
     * Create a new message instance.
     * @param Team $team
     * @param User $inviter
     * @param TeamMember $invite
     */
    public function __construct(Team $team, User $inviter, TeamMember $invite) {
        $this->team = $team;
        $this->inviter = $inviter;
        $this->invite = $invite;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build() {
        return $this->subject('Team Invitation')->markdown('emails.team.invite');
    }
}
