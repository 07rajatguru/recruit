<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CandidateOtherInfo extends Model
{
    //
    public $table = "candidate_otherinfo";

    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'candidate_id','highest_qualification','experience_years','	experience_months','source_id','source_other','current_job_title','current_employer','expected_salary','current_salary','skill','skype_id','status_id','owner_id','deleted_at','created_at','updated_at'
    ];
}
