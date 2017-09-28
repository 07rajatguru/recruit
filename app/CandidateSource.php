<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CandidateSource extends Model
{
    //
    public $table = "candidate_source";

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
