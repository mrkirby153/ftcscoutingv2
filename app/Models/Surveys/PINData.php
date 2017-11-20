<?php

namespace App\Models\Surveys;

use Illuminate\Database\Eloquent\Model;

class PINData extends Model {
    protected $table = "pin_data";

    public $incrementing = false;

    protected $fillable = [
        'id', 'question_id', 'data'
    ];
}
