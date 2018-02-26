<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CandidateBasicInfo extends Model
{
    //
    public $table = "candidate_basicinfo";


    public static $rules = array(
        'fname' => 'required',
        'lname' => 'required',
//        'candidateSex' => 'required',
//        'maritalStatus' => 'required',
        'mobile' => 'required',
    );

    public function messages()
    {
        return [
            'fname.required' => 'First Name is required field',
            'lname.required' => 'Last Name is required field',
            'candidateSex.required' => 'Sex is required field',
        //    'maritalStatus.required' => 'Marital Status is required field',
            'mobile.required' => 'Mobile is required field',
        ];
    }

    public $candidate_upload_type = array('Candidate Resume'=>'Candidate Resume',
        'Candidate Formatted Resume'=>'Candidate Formatted Resume',
        'Candidate Cover Latter' => 'Candidate Cover Latter',
        'Others' => 'Others');

    public static function getTypeArray(){
        $type = array();
        $type[''] = 'Select';
        $type['Male'] = 'Male';
        $type['Female'] = 'Female';

        return $type;
    }

    public static function getMaritalStatusArray(){
        $type = array();
        $type[''] = 'Select Marital Status';
        $type['Single'] = 'Single';
        $type['Engaged'] = 'Engaged';
        $type['Married'] = 'Married';

        return $type;
    }

    public static function getCandidateSourceArray(){
        $candidateSourceArray = array();

        $candidateSource = CandidateSource::all();
        if(isset($candidateSource) && sizeof($candidateSource) > 0){
            foreach ($candidateSource as $item) {
                $candidateSourceArray[$item->id] = $item->name;
            }
        }

        return $candidateSourceArray;
    }

    public static function getCandidateStatusArray(){
        $candidateStatusArray = array('' => 'Select Candidate Status');

        $candidateStatus = CandidateStatus::all();
        if(isset($candidateStatus) && sizeof($candidateStatus) > 0){
            foreach ($candidateStatus as $item) {
                $candidateStatusArray[$item->id] = $item->name;
            }
        }

        return $candidateStatusArray;
    }

    public static function getCandidateArray(){
        $candidateArray = array('' => 'Select Candidate');

        $candidateDetails = CandidateBasicInfo::all();
        if(isset($candidateDetails) && sizeof($candidateDetails)){
            foreach ($candidateDetails as $candidateDetail) {
                $candidateArray[$candidateDetail->id] = $candidateDetail->fname.' '.$candidateDetail->lname;
            }
        }

        return $candidateArray;
    }


}
