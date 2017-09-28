<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CandidateStatus extends Model
{
    //
    public $table = "candidate_status";

    public static $rules = array(
        'name' => 'required',
    );

    public function messages()
    {
        return [
            'name.required' => 'Name is required field',
        ];
    }
}
