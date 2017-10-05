<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JobOpen extends Model
{
    public $table = "job_openings";

    public static $rules = array(
        'posting_title' => 'required',
        'client_id' => 'required',
        'date_opened' => 'required|date',
        'target_date' => 'date|after:date_opened',
    );

    public function messages()
    {
        return [
            'posting_title.required' => 'Posting Title is required field',
            'client_id.required' => 'Client is required field',
            'date_opened.required' => 'Opened Date is required field',

        ];
    }

    public $upload_type = array('Job Summary'=>'Job Summary');

    public static function getPostingTitleArray(){
        $postingArray = array('' => 'Select Posting Title');

        $jobOpenDetails = JobOpen::all();
        if(isset($jobOpenDetails) && sizeof($jobOpenDetails) > 0){
            foreach ($jobOpenDetails as $jobOpenDetail) {
                $postingArray[$jobOpenDetail->id] = $jobOpenDetail->posting_title;
            }
        }

        return $postingArray;
    }

    public static function getJobOpenStatus(){
        // job opening status
        $job_open_status = array();
        $job_open_status['Open'] = 'Open';
        $job_open_status['Closed'] = 'Closed';
        $job_open_status['Hold'] = 'Hold';

        return $job_open_status;
    }

    public static function getJobTypes(){
        $job_types = array();
        $job_types['-None-'] = '-None-';
        $job_types['Full time'] = 'Full time';
        $job_types['Part time'] = 'Part time';
        $job_types['Temporary'] = 'Temporary';
        $job_types['Contract'] = 'Contract';
        $job_types['Temporary to permanent'] = 'Temporary to permanent';

        return $job_types;
    }

    public static function getJobPriorities(){

        $job_priorities = array();
        $job_priorities['0'] = '-None-';
        $job_priorities['1'] = 'Urgent Positions';
        $job_priorities['2'] = 'New Positions';
        $job_priorities['3'] = 'Constant Deliveries needed';
        $job_priorities['4'] = 'Update needed from client/on hold positions';
        $job_priorities['5'] = 'Identified candidates';
        $job_priorities['6'] = 'Revived Positions';
        $job_priorities['7'] = 'Constant Deliveries needed for very old positions where many deliveries are done but no result yet';

        return $job_priorities;
    }

    public static function getJobOpeningId(){

        $jobOpenDetails = JobOpen::all();

        $jobOpenId = array('' => 'Select Job');

        if(isset($jobOpenDetails) && sizeof($jobOpenDetails)>0){
            foreach ($jobOpenDetails as $jobOpenDetail) {
                $jobOpenId[$jobOpenDetail->job_id] = $jobOpenDetail->job_id;
            }
        }
        return $jobOpenId;
    }

    public static function getPostingTitle(){

        $jobOpenDetails = JobOpen::all();

        $jobOpenPostingTitle = array('' => 'Select Posting Title');

        if(isset($jobOpenDetails) && sizeof($jobOpenDetails)>0){
            foreach ($jobOpenDetails as $jobOpenDetail) {
                $jobOpenPostingTitle[$jobOpenDetail->posting_title] = $jobOpenDetail->posting_title;
            }
        }
        return $jobOpenPostingTitle;
    }

    public static function getCity(){

        $jobOpenDetails = JobOpen::all();

        $jobOpenCity = array('' => 'Select City');

        if(isset($jobOpenDetails) && sizeof($jobOpenDetails)>0){
            foreach ($jobOpenDetails as $jobOpenDetail) {
                $jobOpenCity[$jobOpenDetail->city] = $jobOpenDetail->city;
            }
        }
        return $jobOpenCity;
    }
}
