<?php

namespace App;

use App\Models\Team;
use App\Models\TeamMember;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Lab404\Impersonate\Models\Impersonate;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable, Impersonate;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    protected $casts = [
        'admin'=>'boolean'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];


    public function ownedTeams(){
        return $this->hasMany(Team::class, 'owner_id');
    }

    public function teams(){
        return $this->hasMany(TeamMember::class, 'user_email', 'email')->with('team');
    }

    public function canImpersonate() {
        return $this->admin;
    }
}
