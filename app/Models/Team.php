<?php

namespace App\Models;

use App\Models\Surveys\Survey;
use App\User;
use Illuminate\Database\Eloquent\Model;

class Team extends Model {

    protected $table = 'teams';

    public $incrementing = false;

    protected $fillable = ['id', 'name', 'team_number', 'owner_id'];

    public function owner(){
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function members(){
        return $this->hasMany(TeamMember::class, 'team_id');
    }

    public function surveys(){
        return $this->hasMany(Survey::class, 'team_id');
    }
}
