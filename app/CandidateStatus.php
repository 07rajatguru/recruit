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

    public static function getCandidateStatus() {
    
        $interviewStatus['1'] = 'Shortlist';
        $interviewStatus['2'] = 'Shortlisted and Schedule Interview';
        $interviewStatus['3'] = 'Selected';

        return $interviewStatus;
    }
}
