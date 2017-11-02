<?php

namespace App\Models\Surveys;

use Illuminate\Database\Eloquent\Model;

class Survey extends Model {
    protected $table = "surveys";

    public $incrementing = false;

    protected $fillable = [
        'id', 'team_id', 'created_by', 'name'
    ];

    public function questions() {
        return $this->hasMany(Question::class, 'survey_id');
    }

    public function responses() {
        return $this->hasMany(Response::class);
    }

    public function delete() {
        foreach ($this->questions as $question) {
            $question->delete();
        }
        foreach ($this->responses as $response) {
            $response->delete();
        }
        return parent::delete();
    }
}
