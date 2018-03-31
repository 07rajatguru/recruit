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
        //'target_date' => 'date|after:date_opened',
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
        $job_priorities['4'] = 'On Hold';
        $job_priorities['5'] = 'Identified candidates';
        $job_priorities['6'] = 'Revived Positions';
        $job_priorities['7'] = 'Constant Deliveries needed for very old positions where many deliveries are done but no result yet';
        $job_priorities['8'] = 'No Deliveries Needed';
        $job_priorities['9'] = 'Closed By Us';
        $job_priorities['10'] = 'Closed By Client';

        return $job_priorities;
    }

    public static function getJobPrioritiesColor(){

        $job_priorities = array();
        $job_priorities['0'] = '';
        $job_priorities['1'] = '#FF0000';
        $job_priorities['2'] = '#00B0F0';
        $job_priorities['3'] = '#FABF8F';
        $job_priorities['4'] = '#B1A0C7';
        $job_priorities['5'] = '#92D050';
        $job_priorities['6'] = 'yellow';
        $job_priorities['7'] = '#808080';
        $job_priorities['8'] = '#808080';
        $job_priorities['9'] = '#92D050';
        $job_priorities['10'] = '#FFFFFF';

        return $job_priorities;
    }

    public static function getJobPostingStatus(){

        $job_priorities = array();
        $job_priorities['1'] = 'Naukri';
        $job_priorities['2'] = 'Monster';
        $job_priorities['3'] = 'Indeed';
        $job_priorities['4'] = 'OLX';
        $job_priorities['5'] = 'Quickr';
        $job_priorities['6'] = 'IIMJobs';
        $job_priorities['7'] = 'Others';

        return $job_priorities;
    }

    public static function getJobStatus(){
        $job_priorities = array();
        $job_priorities['0'] ='Select';
        $job_priorities['1'] ='On Hold';
        $job_priorities['2'] ='Closed By Us';
        $job_priorities['3'] = 'Closed By Client';

        return $job_priorities;
    }

    public static function getJobSearchOptions(){

        $job_search = array();
        $job_search['1'] = 'Naukri';
        $job_search['2'] = 'Monster';

        return $job_search;
    }

    public static function getShortlistType(){
        
        $candidate_short = array();
        $candidate_short['1'] = 'Round 1';
        $candidate_short['2'] = 'Round 2';
        $candidate_short['3'] = 'Round 3';

        return $candidate_short;
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

     //   $jobOpenPostingTitle = array('' => 'Select Posting Title');

        if(isset($jobOpenDetails) && sizeof($jobOpenDetails)>0){
            foreach ($jobOpenDetails as $jobOpenDetail) {
                $jobOpenPostingTitle[$jobOpenDetail->posting_title] = $jobOpenDetail->posting_title;
            }
        }
        return $jobOpenPostingTitle;
    }

    public static function getJobOpen(){
        $job_query = JobOpen::query();

        $job_query = $job_query->orderBy('posting_title');

        $jobs = $job_query->get();

        /*$users = User::select('*')
            ->get();*/

        $jobArr = array();
        if(isset($jobs) && sizeof($jobs)){
            foreach ($jobs as $job) {
                $jobArr[$job->id] = $job->posting_title;
            }
        }

        return $jobArr;
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



    public static function getJobsCount($status_id,$user_id=0){

        $jobquery = JobOpen::query();
        //$jobquery = $jobquery->
    }

    public static function getClosedJobs($all=0,$user_id){

        $job_onhold = env('ONHOLD');
        $job_client = env('CLOSEDBYCLIENT');
        $job_us = env('CLOSEDBYUS');

        $job_status = array($job_onhold,$job_us,$job_client);
 
        $job_close_query = JobOpen::query();

        $job_close_query =  $job_close_query->select(\DB::raw("COUNT(job_associate_candidates.candidate_id) as count"),'job_openings.id','job_openings.job_id','client_basicinfo.name as company_name',                                      'job_openings.no_of_positions',
                                                'job_openings.posting_title','job_openings.city','job_openings.qualifications','job_openings.salary_from',
                                                'job_openings.salary_to','industry.name as industry_name','job_openings.desired_candidate','job_openings.date_opened',
                                                'job_openings.target_date','users.name as am_name','client_basicinfo.coordinator_name as coordinator_name',
                                                'job_openings.priority','job_openings.hiring_manager_id','client_basicinfo.display_name'
                                                
                                            );
        $job_close_query = $job_close_query->leftJoin('job_associate_candidates','job_openings.id','=','job_associate_candidates.job_id');
        $job_close_query = $job_close_query->join('client_basicinfo','client_basicinfo.id','=','job_openings.client_id');
        $job_close_query = $job_close_query->join('users','users.id','=','job_openings.hiring_manager_id');

        $job_close_query = $job_close_query->leftJoin('industry','industry.id','=','job_openings.industry_id');
        $job_close_query = $job_close_query->whereIn('priority',$job_status);

        // assign jobs to logged in user
        if($all==0){
            $job_close_query = $job_close_query->join('job_visible_users','job_visible_users.job_id','=','job_openings.id');
            $job_close_query = $job_close_query->where('user_id','=',$user_id);
        }

        //$job_open_query = $job_open_query->where('job_associate_candidates.deleted_at','NULL');
        $job_close_query = $job_close_query->groupBy('job_openings.id');

        $job_close_query = $job_close_query->orderBy('job_openings.id','desc');

        
        $job_response = $job_close_query->get();

                $jobs_list = array();

        $colors = self::getJobPrioritiesColor();

        $i = 0;
        foreach ($job_response as $key=>$value){
            $jobs_list[$i]['id'] = $value->id;
            $jobs_list[$i]['job_id'] = $value->job_id;
            $jobs_list[$i]['display_name'] = $value->display_name;
            $jobs_list[$i]['priority'] = $value->priority;
            $jobs_list[$i]['client'] = $value->company_name." - ".$value->coordinator_name;
            $jobs_list[$i]['no_of_positions'] = $value->no_of_positions;
            $jobs_list[$i]['posting_title'] = $value->posting_title;
            $jobs_list[$i]['location'] = $value->city;
            $jobs_list[$i]['qual'] = $value->qualifications;
            $jobs_list[$i]['min_ctc'] = $value->salary_from;
            $jobs_list[$i]['max_ctc'] = $value->salary_to;
            $jobs_list[$i]['industry'] = $value->industry_name;
            $jobs_list[$i]['desired_candidate'] = $value->desired_candidate;
            $jobs_list[$i]['open_date'] = $value->date_opened;
            $jobs_list[$i]['close_date'] = $value->desired_candidate;
            $jobs_list[$i]['am_name'] = $value->am_name;
            $jobs_list[$i]['hiring_manager_id'] = $value->hiring_manager_id;
            $jobs_list[$i]['associate_candidate_cnt'] = $value->count;
            if(isset($value->priority) && $value->priority!='') {
                $jobs_list[$i]['color'] = $colors[$value->priority];
            }
            else
                $jobs_list[$i]['color'] ='';

            // Admin/super admin have access to all details
            if($all==1){
                $jobs_list[$i]['coordinator_name'] = $value->coordinator_name;
                $jobs_list[$i]['access'] = '1';
            }
            else{
                if(isset($value->hiring_manager_id) && $value->hiring_manager_id==$user_id ){
                    $jobs_list[$i]['coordinator_name'] = $value->coordinator_name;
                    $jobs_list[$i]['access'] = '1';
                }
                else{
                    $jobs_list[$i]['coordinator_name'] = '';
                    $jobs_list[$i]['access'] = '0';
                }
            }

            $i++;
        }

        //print_r($jobs_list);exit;
        return $jobs_list;
    
    }

    public static function getJobsByIds($all=0,$ids){
        $job_open_query = JobOpen::query();

        $job_open_query = $job_open_query->select(\DB::raw("COUNT(job_associate_candidates.candidate_id) as count"),'job_openings.id','job_openings.job_id','client_basicinfo.name as company_name',                                      'job_openings.no_of_positions',
            'job_openings.posting_title','job_openings.city','job_openings.qualifications','job_openings.salary_from',
            'job_openings.salary_to','industry.name as industry_name','job_openings.desired_candidate','job_openings.date_opened',
            'job_openings.target_date','users.name as am_name','client_basicinfo.coordinator_name as coordinator_name',
            'job_openings.priority','job_openings.hiring_manager_id'
        );
        $job_open_query = $job_open_query->leftJoin('job_associate_candidates','job_openings.id','=','job_associate_candidates.job_id');
        $job_open_query = $job_open_query->join('client_basicinfo','client_basicinfo.id','=','job_openings.client_id');
        $job_open_query = $job_open_query->join('users','users.id','=','job_openings.hiring_manager_id');

        $job_open_query = $job_open_query->leftJoin('industry','industry.id','=','job_openings.industry_id');

        // assign jobs to logged in user
        if($all==0){
            $job_open_query = $job_open_query->join('job_visible_users','job_visible_users.job_id','=','job_openings.id');
        }
        //whereIn('job_status',[1,2,3]);
        $job_open_query = $job_open_query->where('job_status',NULL);
        $job_open_query = $job_open_query->whereIn('job_openings.id',$ids);

        //$job_open_query = $job_open_query->where('job_associate_candidates.deleted_at','NULL');
        $job_open_query = $job_open_query->groupBy('job_openings.id');

        $job_open_query = $job_open_query->orderBy('job_openings.id','desc');


        $job_response = $job_open_query->get();
//print_r($job_response);exit;
        $jobs_list = array();

        $colors = self::getJobPrioritiesColor();

        $i = 0;
        foreach ($job_response as $key=>$value){
            $jobs_list[$i]['id'] = $value->id;
            $jobs_list[$i]['job_id'] = $value->job_id;
            $jobs_list[$i]['company_name'] = $value->company_name;
            $jobs_list[$i]['client'] = $value->company_name." - ".$value->coordinator_name;
            $jobs_list[$i]['no_of_positions'] = $value->no_of_positions;
            $jobs_list[$i]['posting_title'] = $value->posting_title;
            $jobs_list[$i]['location'] = $value->city;
            $jobs_list[$i]['qual'] = $value->qualifications;
            $jobs_list[$i]['min_ctc'] = $value->salary_from;
            $jobs_list[$i]['max_ctc'] = $value->salary_to;
            $jobs_list[$i]['industry'] = $value->industry_name;
            $jobs_list[$i]['desired_candidate'] = $value->desired_candidate;
            $jobs_list[$i]['open_date'] = $value->date_opened;
            $jobs_list[$i]['close_date'] = $value->desired_candidate;
            $jobs_list[$i]['am_name'] = $value->am_name;
            $jobs_list[$i]['hiring_manager_id'] = $value->hiring_manager_id;
            $jobs_list[$i]['associate_candidate_cnt'] = $value->count;
            if(isset($value->priority) && $value->priority!='') {
                $jobs_list[$i]['color'] = $colors[$value->priority];
            }
            else
                $jobs_list[$i]['color'] ='';

            // Admin/super admin have access to all details
            if($all==1){
                $jobs_list[$i]['coordinator_name'] = $value->coordinator_name;
                $jobs_list[$i]['access'] = '1';
            }
            else{
                if(isset($value->hiring_manager_id) && $value->hiring_manager_id==$user_id ){
                    $jobs_list[$i]['coordinator_name'] = $value->coordinator_name;
                    $jobs_list[$i]['access'] = '1';
                }
                else{
                    $jobs_list[$i]['coordinator_name'] = '';
                    $jobs_list[$i]['access'] = '0';
                }
            }

            $i++;
        }

        //print_r($jobs_list);exit;
        return $jobs_list;
    }

    public static function getAllBillsJobs($all=0,$user_id){
        $job_onhold = env('ONHOLD');
        $job_client = env('CLOSEDBYCLIENT');

        $job_status = array($job_onhold,$job_client);

        $job_open_query = JobOpen::query();

        $job_open_query = $job_open_query->select(\DB::raw("COUNT(job_associate_candidates.candidate_id) as count"),'job_openings.id','job_openings.job_id','client_basicinfo.name as company_name',                                      'job_openings.no_of_positions',
            'job_openings.posting_title','job_openings.city','job_openings.qualifications','job_openings.salary_from',
            'job_openings.salary_to','industry.name as industry_name','job_openings.desired_candidate','job_openings.date_opened',
            'job_openings.target_date','users.name as am_name','client_basicinfo.coordinator_name as coordinator_name',
            'job_openings.priority','job_openings.hiring_manager_id','client_basicinfo.display_name'
        );
        $job_open_query = $job_open_query->leftJoin('job_associate_candidates','job_openings.id','=','job_associate_candidates.job_id');
        $job_open_query = $job_open_query->join('client_basicinfo','client_basicinfo.id','=','job_openings.client_id');
        $job_open_query = $job_open_query->join('users','users.id','=','job_openings.hiring_manager_id');

        $job_open_query = $job_open_query->leftJoin('industry','industry.id','=','job_openings.industry_id');

        // assign jobs to logged in user
        if($all==0){
            $job_open_query = $job_open_query->join('job_visible_users','job_visible_users.job_id','=','job_openings.id');
            $job_open_query = $job_open_query->where('user_id','=',$user_id);
        }
        //whereIn('job_status',[1,2,3]);
        //$job_open_query = $job_open_query->whereNotIn('priority',[4,9,10]);
        $job_open_query = $job_open_query->whereNotIn('priority',$job_status);

        //$job_open_query = $job_open_query->where('job_associate_candidates.deleted_at','NULL');
        $job_open_query = $job_open_query->groupBy('job_openings.id');

        $job_open_query = $job_open_query->orderBy('job_openings.id','desc');


        $job_response = $job_open_query->get();
//print_r($job_response);exit;
        $jobs_list = array();

        $colors = self::getJobPrioritiesColor();

        $i = 0;
        foreach ($job_response as $key=>$value){
            $jobs_list[$i]['id'] = $value->id;
            $jobs_list[$i]['job_id'] = $value->job_id;
            $jobs_list[$i]['company_name'] = $value->company_name;
            $jobs_list[$i]['display_name'] = $value->display_name;
            $jobs_list[$i]['client'] = $value->company_name." - ".$value->coordinator_name;
            $jobs_list[$i]['no_of_positions'] = $value->no_of_positions;
            $jobs_list[$i]['posting_title'] = $value->posting_title;
            $jobs_list[$i]['location'] = $value->city;
            $jobs_list[$i]['qual'] = $value->qualifications;
            $jobs_list[$i]['min_ctc'] = $value->salary_from;
            $jobs_list[$i]['max_ctc'] = $value->salary_to;
            $jobs_list[$i]['industry'] = $value->industry_name;
            $jobs_list[$i]['desired_candidate'] = $value->desired_candidate;
            $jobs_list[$i]['open_date'] = $value->date_opened;
            $jobs_list[$i]['close_date'] = $value->desired_candidate;
            $jobs_list[$i]['am_name'] = $value->am_name;
            $jobs_list[$i]['hiring_manager_id'] = $value->hiring_manager_id;
            $jobs_list[$i]['associate_candidate_cnt'] = $value->count;
            if(isset($value->priority) && $value->priority!='') {
                $jobs_list[$i]['color'] = $colors[$value->priority];
            }
            else
                $jobs_list[$i]['color'] ='';

            // Admin/super admin have access to all details
            if($all==1){
                $jobs_list[$i]['coordinator_name'] = $value->coordinator_name;
                $jobs_list[$i]['access'] = '1';
            }
            else{
                if(isset($value->hiring_manager_id) && $value->hiring_manager_id==$user_id ){
                    $jobs_list[$i]['coordinator_name'] = $value->coordinator_name;
                    $jobs_list[$i]['access'] = '1';
                }
                else{
                    $jobs_list[$i]['coordinator_name'] = '';
                    $jobs_list[$i]['access'] = '0';
                }
            }

            $i++;
        }

        //print_r($jobs_list);exit;
        return $jobs_list;
    }

    public static function getAllJobs($all=0,$user_id){

        $job_onhold = env('ONHOLD');
        $job_client = env('CLOSEDBYCLIENT');
        $job_us = env('CLOSEDBYUS');

        $job_status = array($job_onhold,$job_us,$job_client);

        $job_open_query = JobOpen::query();

        $job_open_query = $job_open_query->select(\DB::raw("COUNT(job_associate_candidates.candidate_id) as count"),'job_openings.id','job_openings.job_id','client_basicinfo.name as company_name',                                      'job_openings.no_of_positions',
                                                'job_openings.posting_title','job_openings.city','job_openings.qualifications','job_openings.salary_from',
                                                'job_openings.salary_to','industry.name as industry_name','job_openings.desired_candidate','job_openings.date_opened',
                                                'job_openings.target_date','users.name as am_name','client_basicinfo.coordinator_name as coordinator_name',
                                                'job_openings.priority','job_openings.hiring_manager_id','client_basicinfo.display_name'
                                            );
        $job_open_query = $job_open_query->leftJoin('job_associate_candidates','job_openings.id','=','job_associate_candidates.job_id');
        $job_open_query = $job_open_query->join('client_basicinfo','client_basicinfo.id','=','job_openings.client_id');
        $job_open_query = $job_open_query->join('users','users.id','=','job_openings.hiring_manager_id');

        $job_open_query = $job_open_query->leftJoin('industry','industry.id','=','job_openings.industry_id');

        // assign jobs to logged in user
        if($all==0){
            $job_open_query = $job_open_query->join('job_visible_users','job_visible_users.job_id','=','job_openings.id');
            $job_open_query = $job_open_query->where('user_id','=',$user_id);
        }
        //whereIn('job_status',[1,2,3]);
        //$job_open_query = $job_open_query->whereNotIn('priority',[4,9,10]);
        $job_open_query = $job_open_query->whereNotIn('priority',$job_status);

        //$job_open_query = $job_open_query->where('job_associate_candidates.deleted_at','NULL');
        $job_open_query = $job_open_query->groupBy('job_openings.id');

        $job_open_query = $job_open_query->orderBy('job_openings.id','desc');

        
        $job_response = $job_open_query->get();
//print_r($job_response);exit;
        $jobs_list = array();

        $colors = self::getJobPrioritiesColor();

        $i = 0;
        foreach ($job_response as $key=>$value){
            $jobs_list[$i]['id'] = $value->id;
            $jobs_list[$i]['job_id'] = $value->job_id;
            $jobs_list[$i]['company_name'] = $value->company_name;
            $jobs_list[$i]['display_name'] = $value->display_name;
            $jobs_list[$i]['client'] = $value->company_name." - ".$value->coordinator_name;
            $jobs_list[$i]['no_of_positions'] = $value->no_of_positions;
            $jobs_list[$i]['posting_title'] = $value->posting_title;
            $jobs_list[$i]['location'] = $value->city;
            $jobs_list[$i]['qual'] = $value->qualifications;
            $jobs_list[$i]['min_ctc'] = $value->salary_from;
            $jobs_list[$i]['max_ctc'] = $value->salary_to;
            $jobs_list[$i]['industry'] = $value->industry_name;
            $jobs_list[$i]['desired_candidate'] = $value->desired_candidate;
            $jobs_list[$i]['open_date'] = $value->date_opened;
            $jobs_list[$i]['close_date'] = $value->desired_candidate;
            $jobs_list[$i]['am_name'] = $value->am_name;
            $jobs_list[$i]['hiring_manager_id'] = $value->hiring_manager_id;
            $jobs_list[$i]['associate_candidate_cnt'] = $value->count;
            if(isset($value->priority) && $value->priority!='') {
                $jobs_list[$i]['color'] = $colors[$value->priority];
            }
            else
                $jobs_list[$i]['color'] ='';

            // Admin/super admin have access to all details
            if($all==1){
                $jobs_list[$i]['coordinator_name'] = $value->coordinator_name;
                $jobs_list[$i]['access'] = '1';
            }
            else{
                if(isset($value->hiring_manager_id) && $value->hiring_manager_id==$user_id ){
                    $jobs_list[$i]['coordinator_name'] = $value->coordinator_name;
                    $jobs_list[$i]['access'] = '1';
                }
                else{
                    $jobs_list[$i]['coordinator_name'] = '';
                    $jobs_list[$i]['access'] = '0';
                }
            }

            $i++;
        }

        //print_r($jobs_list);exit;
        return $jobs_list;
    }
}
