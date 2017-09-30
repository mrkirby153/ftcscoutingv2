<?php

namespace App\Models\Surveys;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Response extends Model {
    protected $table = "responses";

    public $incrementing = false;

    protected $fillable = [
        'id',
        'user_id'
    ];


    public function survey(){
        return $this->belongsTo(Survey::class);
    }

    public function submitter(){
        return $this->belongsTo(User::class);
    }

    public function data(){
        return $this->belongsTo(ResponseData::class);
    }

    public function delete() {
        foreach ($this->data as $data){
            $data->delete();
        }
        return parent::delete();
    }
}
