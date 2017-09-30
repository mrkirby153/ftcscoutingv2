<?php

namespace App\Models\Surveys;

use Illuminate\Database\Eloquent\Model;

class ResponseData extends Model {
    protected $table = "response_data";

    public $incrementing = false;

    protected $fillable = [
        'id',
        'response_id',
        'question_id',
        'response_data'
    ];

    public function response() {
        return $this->belongsTo(Response::class);
    }

    public function question() {
        return $this->belongsTo(Question::class);
    }

    /**
     * @param $data
     * @return object
     */
    public function getResponseDataAttribute($data){
        return \GuzzleHttp\json_decode($data);
    }

    /**
     * @param $data
     */
    public function setResponseDataAttribute($data){
        $this->attributes['response_data'] = \GuzzleHttp\json_encode($data);
    }
}
