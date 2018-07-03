<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CandidateUploadedResume extends Model
{
    //
    public $table = "candidate_uploaded_resume";

     public static function getCandidateAttachment($candidate_id){

        $candidate_attach = CandidateUploadedResume::query();
        $candidate_attach = $candidate_attach->join('candidate_basicinfo','candidate_basicinfo.id','=','candidate_uploaded_resume.candidate_id');
        $candidate_attach = $candidate_attach->select('candidate_uploaded_resume.*');
        $candidate_attach = $candidate_attach->where('candidate_uploaded_resume.candidate_id','=',$candidate_id);
        $candidate_attach = $candidate_attach->where('candidate_uploaded_resume.file_type','=','Candidate Formatted Resume');
        $candidate_attach_res = $candidate_attach->first();

        return $candidate_attach_res;
        
    }
}
