<?php

namespace App\Models\Surveys;

use Illuminate\Database\Eloquent\Model;

class Question extends Model {
    protected $table = "survey_questions";

    public $incrementing = false;

    protected $fillable = [
        'id', 'question_name', 'extra_data'
    ];


    public function survey() {
        return $this->belongsTo(Survey::class);
    }


    /**
     * @param $data
     * @return object
     */
    public function getExtraDataAttribute($data) {
        return json_decode($data);
    }

    /**
     * @param $data object
     */
    public function setExtraDataAttribute($data) {
        $data = (object) $data;
        $this->attributes['extra_data'] = is_object($data)? \GuzzleHttp\json_encode($data) : $data;
    }
}
