<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CandidateUploadedResume extends Model
{
   public $table = "candidate_uploaded_resume";

   public static function getCandidateAttachment($candidate_id) {

      $type_array = array('Candidate Formatted Resume','Others');

      $query = CandidateUploadedResume::query();
      $query = $query->join('candidate_basicinfo','candidate_basicinfo.id','=','candidate_uploaded_resume.candidate_id');
      $query = $query->select('candidate_uploaded_resume.*');
      $query = $query->where('candidate_uploaded_resume.candidate_id','=',$candidate_id);
      $query = $query->whereIn('candidate_uploaded_resume.file_type',$type_array);
      $candidate_attach_res = $query->get();

      return $candidate_attach_res;
   }

   public static function getCandidateFormattedResume($candidate_id) {

      $query = CandidateUploadedResume::query();
      $query = $query->select('candidate_uploaded_resume.file');
      $query = $query->where('candidate_id','=',$candidate_id);
      $query = $query->where('file_type','=','Candidate Formatted Resume');
      $response = $query->first();

      if(isset($response) && $response != '') {
         $candidate_resume = $response->file;
      }
      else {
         $candidate_resume = '';
      }
      return $candidate_resume;
   }
}