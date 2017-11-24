<?php

namespace App\Models\Surveys;

use Illuminate\Database\Eloquent\Model;

class PINData extends Model {
    protected $table = "pin_data";

    public $incrementing = false;

    protected $fillable = [
        'id', 'question_id', 'data'
    ];

    public function question(){
        return $this->belongsTo(Question::class, 'question_id');
    }


    public function getDataAttribute($data){
        return json_decode($data);
    }

    public function setDataAttribute($data){
        if(is_array($data)){
            $data = (object) $data;
        }
        $this->attributes['data'] = is_object($data) ? \GuzzleHttp\json_encode($data) : $data;
    }
}
