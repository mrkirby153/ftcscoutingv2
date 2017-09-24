<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class TeamMember extends Model {

    protected $table = "team_members";

    public $incrementing = false;

    protected $fillable = ['id', 'user_email', 'team_id', 'pending'];

    protected $casts = [
        'pending' => 'boolean'
    ];

    public function user() {
        return $this->belongsTo(User::class, 'user_email', 'email');
    }

    public function team() {
        return $this->belongsTo(Team::class, 'team_id');
    }
}
