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
        $job_priorities['5'] = 'Revived Positions';
        $job_priorities['6'] = 'Constant Deliveries needed for very old positions where many deliveries are done but no result yet';
        $job_priorities['7'] = 'No Deliveries Needed';
        $job_priorities['8'] = 'Identified candidates';
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
        $job_priorities['5'] = 'yellow';
        $job_priorities['6'] = '';
        $job_priorities['7'] = '#808080';
        $job_priorities['8'] = '#92D050';
        $job_priorities['9'] = '#92D050';
        $job_priorities['10'] = '#FFFFFF'; 

        /*$job_priorities['0'] = '';
        $job_priorities['1'] = '#FF0000';
        $job_priorities['2'] = '#00B0F0';
        $job_priorities['3'] = '#FABF8F';
        $job_priorities['4'] = '#B1A0C7';
        $job_priorities['5'] = '#92D050';
        $job_priorities['6'] = '#yellow';
        $job_priorities['7'] = '#808080';
        $job_priorities['8'] = '#808080';
        $job_priorities['9'] = '#92D050';
        $job_priorities['10'] = '#FFFFFF';*/



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

        $job_onhold = getenv('ONHOLD');
        $job_client = getenv('CLOSEDBYCLIENT');
        $job_us = getenv('CLOSEDBYUS');

        $job_status = array($job_onhold,$job_us,$job_client);
 
        $job_close_query = JobOpen::query();

        $job_close_query =  $job_close_query->select(\DB::raw("COUNT(job_associate_candidates.candidate_id) as count"),'job_openings.id','job_openings.job_id','client_basicinfo.name as company_name',                                      'job_openings.no_of_positions',
                                                'job_openings.posting_title','job_openings.city','job_openings.state','job_openings.country','job_openings.qualifications','job_openings.salary_from',
                                                'job_openings.salary_to','industry.name as industry_name','job_openings.desired_candidate','job_openings.date_opened',
                                                'job_openings.target_date','users.name as am_name','client_basicinfo.coordinator_name as coordinator_name',
                                                'job_openings.priority','job_openings.hiring_manager_id','client_basicinfo.display_name','job_openings.created_at'
                                                
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

        $job_close_query = $job_close_query->orderBy('job_openings.updated_at','desc');

        
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
            //$jobs_list[$i]['location'] = $value->city;
            $location ='';
            if($value->city!=''){
                $location .= $value->city;
            }
            if($value->state!=''){
                if($location=='')
                    $location .= $value->state;
                else
                    $location .= ", ".$value->state;
            }
            if($value->country!=''){
                if($location=='')
                    $location .= $value->country;
                else
                    $location .= ", ".$value->country;
            }
            $jobs_list[$i]['location'] = $location;
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
            $jobs_list[$i]['created_date'] = date('d-m-Y',strtotime($value->created_at));
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
        $user_id = \Auth::user()->id;
        $job_open_query = JobOpen::query();

        $job_open_query = $job_open_query->select(\DB::raw("COUNT(job_associate_candidates.candidate_id) as count"),'job_openings.id','job_openings.job_id','client_basicinfo.name as company_name',                                      'job_openings.no_of_positions',
            'job_openings.posting_title','job_openings.city','job_openings.state','job_openings.country','job_openings.qualifications','job_openings.salary_from',
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
            $location ='';
            if($value->city!=''){
                $location .= $value->city;
            }
            if($value->state!=''){
                if($location=='')
                    $location .= $value->state;
                else
                    $location .= ", ".$value->state;
            }
            if($value->country!=''){
                if($location=='')
                    $location .= $value->country;
                else
                    $location .= ", ".$value->country;
            }
            $jobs_list[$i]['location'] = $location;
            //$jobs_list[$i]['location'] = $value->city;
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
            //$job_open_query = $job_open_query->where('user_id','=',$user_id);
            $job_open_query = $job_open_query->where('client_basicinfo.account_manager_id','=',$user_id);
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

    public static function getAllJobs($all=0,$user_id,$limit=0,$offset=0,$search=0,$order=0,$type=NULL){

        $job_onhold = getenv('ONHOLD');
        $job_client = getenv('CLOSEDBYCLIENT');
        $job_us = getenv('CLOSEDBYUS');

        $user =  \Auth::user();
        $userRole = $user->roles->pluck('id','id')->toArray();
        $role_id = key($userRole);

        $user_obj = new User();
        $isStrategy = $user_obj::isStrategyCoordination($role_id);

        $job_status = array($job_onhold,$job_us,$job_client);

        $job_open_query = JobOpen::query();
//getenv()
        $job_open_query = $job_open_query->select(\DB::raw("COUNT(job_associate_candidates.candidate_id) as count"),'job_openings.id','job_openings.job_id','client_basicinfo.name as company_name',                                      'job_openings.no_of_positions',
                                                'job_openings.posting_title','job_openings.city','job_openings.state','job_openings.country','job_openings.qualifications','job_openings.salary_from',
                                                'job_openings.salary_to','job_openings.lacs_from','job_openings.thousand_from','job_openings.lacs_to','job_openings.thousand_to','industry.name as industry_name','job_openings.desired_candidate','job_openings.date_opened',
                                                'job_openings.target_date','users.name as am_name','client_basicinfo.coordinator_name as coordinator_name',
                                                'job_openings.priority','job_openings.hiring_manager_id','client_basicinfo.display_name','job_openings.created_at'
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

        $job_open_query = $job_open_query->where('job_associate_candidates.deleted_at',NULL);
        $job_open_query = $job_open_query->groupBy('job_openings.id');

        $job_open_query = $job_open_query->orderBy('job_openings.updated_at','desc');

        /*if (isset($order) && $order >= 0) {
            if (isset($type) && $type != '') {
                if ($order == 0) {
                    $job_open_query = $job_open_query->orderBy('job_openings.updated_at','asc');
                }
                else if ($order == 2) {
                    $job_open_query = $job_open_query->orderBy('users.name',$type);
                }
                else if ($order == 3) {
                    $job_open_query = $job_open_query->orderBy('client_basicinfo.display_name',$type);
                }
            }
        }*/

       /* if (isset($limit) && $limit > 0) {
            $job_open_query = $job_open_query->limit($limit);
        }
        if (isset($offset) && $offset > 0) {
            $job_open_query = $job_open_query->offset($offset);
        }
        if (isset($search) && $search != '') {
            $job_open_query = $job_open_query->where('job_openings.posting_title','like',"%$search%");
            $job_open_query = $job_open_query->orwhere('users.name','like',"%$search%");
            $job_open_query = $job_open_query->orwhere('client_basicinfo.display_name','like',"%$search%");
            $job_open_query = $job_open_query->orwhere('client_basicinfo.coordinator_name','like',"%$search%");
            $job_open_query = $job_open_query->orwhere('job_openings.no_of_positions','like',"%$search%");
        }*/
        $job_response = $job_open_query->get();
//print_r($job_response);exit;
        $jobs_list = array();

        $colors = self::getJobPrioritiesColor();

        $i = 0;
        foreach ($job_response as $key=>$value){
            // value get in 2 decimal point
            if ($value->lacs_from >= '100') {
                $min_ctc = '100+';
            }
            else{
                $lacs_from = $value->lacs_from*100000;
                $thousand_from = $value->thousand_from*1000;
                $mictc = $lacs_from+$thousand_from;
                $minctc = $mictc/100000;
                $min_ctc = number_format($minctc,2);
            }

            if ($value->lacs_to >= '100') {
                $max_ctc = '100+';
            }
            else{
                $lacs_to = $value->lacs_to*100000;
                $thousand_to = $value->thousand_to*1000;
                $mactc = $lacs_to+$thousand_to;
                $maxctc = $mactc/100000;
                $max_ctc = number_format($maxctc,2);
            }
            //echo $mactc;exit;
            //$min_ctc = $value->lacs_from.".".$value->thousand_from;
            $jobs_list[$i]['id'] = $value->id;
            $jobs_list[$i]['job_id'] = $value->job_id;
            $jobs_list[$i]['company_name'] = $value->company_name;
            $jobs_list[$i]['display_name'] = $value->display_name;
            $jobs_list[$i]['client'] = $value->company_name." - ".$value->coordinator_name;
            $jobs_list[$i]['no_of_positions'] = $value->no_of_positions;
            $jobs_list[$i]['posting_title'] = $value->posting_title;
            //$jobs_list[$i]['location'] = $value->city.",".$value->state.",".$value->country;
            $location ='';
            if($value->city!=''){
                $location .= $value->city;
            }
            if($value->state!=''){
                if($location=='')
                    $location .= $value->state;
                else
                    $location .= ", ".$value->state;
            }
            if($value->country!=''){
                if($location=='')
                    $location .= $value->country;
                else
                    $location .= ", ".$value->country;
            }
            $jobs_list[$i]['location'] = $location;
            $jobs_list[$i]['qual'] = $value->qualifications;
            $jobs_list[$i]['min_ctc'] = $min_ctc;
            $jobs_list[$i]['max_ctc'] = $max_ctc;
            $jobs_list[$i]['industry'] = $value->industry_name;
            $jobs_list[$i]['desired_candidate'] = $value->desired_candidate;
            $jobs_list[$i]['open_date'] = $value->date_opened;
            $jobs_list[$i]['close_date'] = $value->target_date;
            $jobs_list[$i]['am_name'] = $value->am_name;
            $jobs_list[$i]['hiring_manager_id'] = $value->hiring_manager_id;
            $jobs_list[$i]['associate_candidate_cnt'] = $value->count;
            $jobs_list[$i]['priority'] = $value->priority;
            $jobs_list[$i]['created_date'] = date('d-m-Y',strtotime($value->created_at));
            if(isset($value->priority) && $value->priority!='') {
                $jobs_list[$i]['color'] = $colors[$value->priority];
            }
            else
                $jobs_list[$i]['color'] ='';

            // Admin/super admin have access to all details
            if($all==1){

                // Strategy and coordinator role dont have all access
                if($isStrategy){
                    $jobs_list[$i]['access'] = '0';
                }
                else{
                    $jobs_list[$i]['access'] = '1';
                }

                $jobs_list[$i]['coordinator_name'] = $value->coordinator_name;
                //$jobs_list[$i]['access'] = '1';
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

    /*public static function getAllJobsCount($all=0,$user_id,$search){

        $job_onhold = getenv('ONHOLD');
        $job_client = getenv('CLOSEDBYCLIENT');
        $job_us = getenv('CLOSEDBYUS');
        $job_status = array($job_onhold,$job_us,$job_client);

        $job_open_query = JobOpen::query();
        $job_open_query = $job_open_query->select(\DB::raw("COUNT(job_associate_candidates.candidate_id) as count"),'job_openings.id','job_openings.job_id','client_basicinfo.name as company_name',                                      'job_openings.no_of_positions',
                                                'job_openings.posting_title','job_openings.city','job_openings.state','job_openings.country','job_openings.qualifications','job_openings.salary_from',
                                                'job_openings.salary_to','job_openings.lacs_from','job_openings.thousand_from','job_openings.lacs_to','job_openings.thousand_to','industry.name as industry_name','job_openings.desired_candidate','job_openings.date_opened',
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

        $job_open_query = $job_open_query->where('job_associate_candidates.deleted_at',NULL);
        $job_open_query = $job_open_query->groupBy('job_openings.id');

        $job_open_query = $job_open_query->orderBy('job_openings.updated_at','desc');
        if (isset($search) && $search != '') {
            $job_open_query = $job_open_query->where('job_openings.posting_title','like',"%$search%");
            $job_open_query = $job_open_query->orwhere('users.name','like',"%$search%");
        }
        $job_response = $job_open_query->get();

        return sizeof($job_response);
    }*/

    public static function getJobById($job_id){

        /*
         * $job_open_detail = \DB::table('job_openings')
            ->join('client_basicinfo', 'client_basicinfo.id', '=', 'job_openings.client_id')
            ->join('users', 'users.id', '=', 'job_openings.hiring_manager_id')
            ->join('industry', 'industry.id', '=', 'job_openings.industry_id')
            ->select('job_openings.*', 'client_basicinfo.name as client_name', 'users.name as hiring_manager_name', 'industry.name as industry_name')
            ->where('job_openings.id', '=', $id)
            ->get();
         */

        $job_query = JobOpen::query();
        $job_query = $job_query->join('client_basicinfo','client_basicinfo.id','=','job_openings.client_id');
        $job_query = $job_query->leftjoin('interview', 'interview.posting_title','=', 'job_openings.id');
        $job_query = $job_query->join('users','users.id','=','job_openings.hiring_manager_id');
        $job_query = $job_query->select('job_openings.*','client_basicinfo.name as client_name','client_basicinfo.description as client_desc', 'client_basicinfo.website as website','interview.interview_date as date', 'interview.location as interview_location','interview.type as interview_type','client_basicinfo.coordinator_name as contact_person','users.name as user_name','interview.skype_id as skype_id');
        $job_query = $job_query->where('job_openings.id', '=', $job_id);
        $job_response = $job_query->get();

        $response = array();
        foreach ($job_response as $k=>$v){
            $response['company_name'] = $v->client_name;
            $response['company_url'] = $v->website;
            $response['client_desc'] = $v->client_desc;
            $response['posting_title'] = $v->posting_title;
            $location ='';
            if($v->city!=''){
                $location .= $v->city;
            }
            if($v->state!=''){
                if($location=='')
                    $location .= $v->state;
                else
                    $location .= ", ".$v->state;
            }
            if($v->country!=''){
                if($location=='')
                    $location .= $v->country;
                else
                    $location .= ", ".$v->country;
            }
            $response['city'] = $v->city;
            $response['job_location'] = $location;
            $response['contact_person'] = $v->contact_person;
            $response['job_description'] = $v->job_description;
            if (isset($v->date) && $v->date != '') {
                $datearray = explode(' ', $v->date);
                $response['interview_date'] = $datearray[0];
                $response['interview_time'] = $datearray[1];
            }
            $response['interview_location'] = $v->interview_location;
            $response['interview_type'] = $v->interview_type;
            $response['client_id'] = $v->client_id;
            $response['job_unique_id'] = $v->job_id;
            $response['user_name'] = $v->user_name;
            $response['skype_id'] = $v->skype_id;
        }

        return $response;
    }

    public static function getJobforOpentoAll(){

        $date = date('Y-m-d h');
        //print_r($date);exit;
        $job_onhold = getenv('ONHOLD');
        $job_client = getenv('CLOSEDBYCLIENT');
        $job_us = getenv('CLOSEDBYUS');
        $job_status = array($job_onhold,$job_us,$job_client);

        $job = JobOpen::query();
        $job = $job->select('job_openings.id','job_openings.created_at');
        $job = $job->where('job_openings.open_to_all_date','like',"%$date%");
        $job = $job->whereNotIn('priority',$job_status);
        $job = $job->where('open_to_all','=','0');
        $job_res = $job->get();

        $job_data = array();
        $i = 0;
        foreach ($job_res as $key => $value) {
            $job_data[$i]['id'] = $value->id;
            $job_data[$i]['created_at'] = $value->created_at;
            $i++;
        }
        
        return $job_res;
    }

    public static function getOpenToAllJobs($all=0,$user_id=0,$limit=0){

        $job_onhold = getenv('ONHOLD');
        $job_client = getenv('CLOSEDBYCLIENT');
        $job_us = getenv('CLOSEDBYUS');

        $user =  \Auth::user();
        $userRole = $user->roles->pluck('id','id')->toArray();
        $role_id = key($userRole);

        $user_obj = new User();
        $isStrategy = $user_obj::isStrategyCoordination($role_id);

        $job_status = array($job_onhold,$job_us,$job_client);

        $job_open_query = JobOpen::query();
        $job_open_query = $job_open_query->select(\DB::raw("COUNT(job_associate_candidates.candidate_id) as count"),'job_openings.id','job_openings.job_id','client_basicinfo.name as company_name',                                      'job_openings.no_of_positions',
                                                'job_openings.posting_title','job_openings.city','job_openings.state','job_openings.country','job_openings.qualifications','job_openings.salary_from',
                                                'job_openings.salary_to','job_openings.lacs_from','job_openings.thousand_from','job_openings.lacs_to','job_openings.thousand_to','industry.name as industry_name','job_openings.desired_candidate','job_openings.date_opened',
                                                'job_openings.target_date','users.name as am_name','client_basicinfo.coordinator_name as coordinator_name',
                                                'job_openings.priority','job_openings.hiring_manager_id','client_basicinfo.display_name','job_openings.created_at','job_openings.open_to_all as open_to_all','job_openings.open_to_all_date as open_to_all_date'
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

        $job_open_query = $job_open_query->whereNotIn('priority',$job_status);

        $job_open_query = $job_open_query->where('job_associate_candidates.deleted_at',NULL);
        $job_open_query = $job_open_query->groupBy('job_openings.id');
        $job_open_query = $job_open_query->where('open_to_all','=','1');
        $job_open_query = $job_open_query->orderBy('job_openings.updated_at','desc');

        if (isset($limit) && $limit > 0) {
            $job_open_query = $job_open_query->limit($limit);
        }
        $job_response = $job_open_query->get();

        $jobs_open_list = array();
        $colors = self::getJobPrioritiesColor();
        $i = 0;
        foreach ($job_response as $key=>$value){
            // value get in 2 decimal point
            if ($value->lacs_from >= '100') {
                $min_ctc = '100+';
            }
            else{
                $lacs_from = $value->lacs_from*100000;
                $thousand_from = $value->thousand_from*1000;
                $mictc = $lacs_from+$thousand_from;
                $minctc = $mictc/100000;
                $min_ctc = number_format($minctc,2);
            }

            if ($value->lacs_to >= '100') {
                $max_ctc = '100+';
            }
            else{
                $lacs_to = $value->lacs_to*100000;
                $thousand_to = $value->thousand_to*1000;
                $mactc = $lacs_to+$thousand_to;
                $maxctc = $mactc/100000;
                $max_ctc = number_format($maxctc,2);
            }
            //echo $mactc;exit;
            //$min_ctc = $value->lacs_from.".".$value->thousand_from;
            $jobs_open_list[$i]['id'] = $value->id;
            $jobs_open_list[$i]['job_id'] = $value->job_id;
            $jobs_open_list[$i]['company_name'] = $value->company_name;
            $jobs_open_list[$i]['display_name'] = $value->display_name;
            $jobs_open_list[$i]['client'] = $value->company_name." - ".$value->coordinator_name;
            $jobs_open_list[$i]['no_of_positions'] = $value->no_of_positions;
            $jobs_open_list[$i]['posting_title'] = $value->posting_title;
            $location ='';
            if($value->city!=''){
                $location .= $value->city;
            }
            if($value->state!=''){
                if($location=='')
                    $location .= $value->state;
                else
                    $location .= ", ".$value->state;
            }
            if($value->country!=''){
                if($location=='')
                    $location .= $value->country;
                else
                    $location .= ", ".$value->country;
            }
            $jobs_open_list[$i]['location'] = $location;
            $jobs_open_list[$i]['qual'] = $value->qualifications;
            $jobs_open_list[$i]['min_ctc'] = $min_ctc;
            $jobs_open_list[$i]['max_ctc'] = $max_ctc;
            $jobs_open_list[$i]['industry'] = $value->industry_name;
            $jobs_open_list[$i]['desired_candidate'] = $value->desired_candidate;
            $jobs_open_list[$i]['open_date'] = $value->date_opened;
            $jobs_open_list[$i]['close_date'] = $value->target_date;
            $jobs_open_list[$i]['am_name'] = $value->am_name;
            $jobs_open_list[$i]['hiring_manager_id'] = $value->hiring_manager_id;
            $jobs_open_list[$i]['associate_candidate_cnt'] = $value->count;
            $jobs_open_list[$i]['priority'] = $value->priority;
            $jobs_open_list[$i]['created_date'] = date('d-m-Y',strtotime($value->created_at));
            if(isset($value->priority) && $value->priority!='') {
                $jobs_open_list[$i]['color'] = $colors[$value->priority];
            }
            else
                $jobs_open_list[$i]['color'] ='';

            // Admin/super admin have access to all details
            if($all==1){

                // Strategy and coordinator role dont have all access
                if($isStrategy){
                    $jobs_open_list[$i]['access'] = '0';
                }
                else{
                    $jobs_open_list[$i]['access'] = '1';
                }

                $jobs_open_list[$i]['coordinator_name'] = $value->coordinator_name;
                //$jobs_open_list[$i]['access'] = '1';
            }
            else{
                if(isset($value->hiring_manager_id) && $value->hiring_manager_id==$user_id ){
                    $jobs_open_list[$i]['coordinator_name'] = $value->coordinator_name;
                    $jobs_open_list[$i]['access'] = '1';
                }
                else{
                    $jobs_open_list[$i]['coordinator_name'] = '';
                    $jobs_open_list[$i]['access'] = '0';
                }
            }
            $jobs_open_list[$i]['open_to_all'] = $value->open_to_all;
            $jobs_open_list[$i]['open_to_all_date'] = $value->open_to_all_date;
            
            $i++;
        }

        //print_r($jobs_open_list);exit;
        return $jobs_open_list;
    }

    public static function getPriorityWiseJobs($all=0,$user_id,$priority){

        $user =  \Auth::user();
        $userRole = $user->roles->pluck('id','id')->toArray();
        $role_id = key($userRole);

        $user_obj = new User();
        $isStrategy = $user_obj::isStrategyCoordination($role_id);

        $job_open_query = JobOpen::query();
        $job_open_query = $job_open_query->select(\DB::raw("COUNT(job_associate_candidates.candidate_id) as count"),'job_openings.id','job_openings.job_id','client_basicinfo.name as company_name',                                      'job_openings.no_of_positions',
                                                'job_openings.posting_title','job_openings.city','job_openings.state','job_openings.country','job_openings.qualifications','job_openings.salary_from',
                                                'job_openings.salary_to','job_openings.lacs_from','job_openings.thousand_from','job_openings.lacs_to','job_openings.thousand_to','industry.name as industry_name','job_openings.desired_candidate','job_openings.date_opened',
                                                'job_openings.target_date','users.name as am_name','client_basicinfo.coordinator_name as coordinator_name',
                                                'job_openings.priority','job_openings.hiring_manager_id','client_basicinfo.display_name','job_openings.created_at'
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

        if ($priority == '-None-') {
            $job_open_query = $job_open_query->where('priority','=','0');
        }
        else if ($priority == 'Urgent Positions') {
            $job_open_query = $job_open_query->where('priority','=','1');
        }
        else if ($priority == 'New Positions') {
            $job_open_query = $job_open_query->where('priority','=','2');
        }
        else if ($priority == 'Constant Deliveries needed') {
            $job_open_query = $job_open_query->where('priority','=','3');
        }
        else if ($priority == 'On Hold') {
            $job_open_query = $job_open_query->where('priority','=','4');
        }
        else if ($priority == 'Revived Positions') {
            $job_open_query = $job_open_query->where('priority','=','5');
        }
        else if ($priority == 'Constant Deliverie needed for very old positions where many deliveries are done but no result yet') {
            $job_open_query = $job_open_query->where('priority','=','6');
        }
        else if ($priority == 'No Deliveries Needed') {
            $job_open_query = $job_open_query->where('priority','=','7');
        }
        else if ($priority == 'Identified candidates') {
            $job_open_query = $job_open_query->where('priority','=','8');
        }
        else if ($priority == 'Closed By Us') {
            $job_open_query = $job_open_query->where('priority','=','9');
        }
        else if ($priority == 'Closed By Client') {
            $job_open_query = $job_open_query->where('priority','=','10');
        }

        $job_open_query = $job_open_query->where('job_associate_candidates.deleted_at',NULL);
        $job_open_query = $job_open_query->groupBy('job_openings.id');

        $job_open_query = $job_open_query->orderBy('job_openings.updated_at','desc');

        $job_response = $job_open_query->get();
//print_r($job_response);exit;
        $jobs_list = array();

        $colors = self::getJobPrioritiesColor();

        $i = 0;
        foreach ($job_response as $key=>$value){
            // value get in 2 decimal point
            if ($value->lacs_from >= '100') {
                $min_ctc = '100+';
            }
            else{
                $lacs_from = $value->lacs_from*100000;
                $thousand_from = $value->thousand_from*1000;
                $mictc = $lacs_from+$thousand_from;
                $minctc = $mictc/100000;
                $min_ctc = number_format($minctc,2);
            }

            if ($value->lacs_to >= '100') {
                $max_ctc = '100+';
            }
            else{
                $lacs_to = $value->lacs_to*100000;
                $thousand_to = $value->thousand_to*1000;
                $mactc = $lacs_to+$thousand_to;
                $maxctc = $mactc/100000;
                $max_ctc = number_format($maxctc,2);
            }
            //echo $mactc;exit;
            //$min_ctc = $value->lacs_from.".".$value->thousand_from;
            $jobs_list[$i]['id'] = $value->id;
            $jobs_list[$i]['job_id'] = $value->job_id;
            $jobs_list[$i]['company_name'] = $value->company_name;
            $jobs_list[$i]['display_name'] = $value->display_name;
            $jobs_list[$i]['client'] = $value->company_name." - ".$value->coordinator_name;
            $jobs_list[$i]['no_of_positions'] = $value->no_of_positions;
            $jobs_list[$i]['posting_title'] = $value->posting_title;
            //$jobs_list[$i]['location'] = $value->city.",".$value->state.",".$value->country;
            $location ='';
            if($value->city!=''){
                $location .= $value->city;
            }
            if($value->state!=''){
                if($location=='')
                    $location .= $value->state;
                else
                    $location .= ", ".$value->state;
            }
            if($value->country!=''){
                if($location=='')
                    $location .= $value->country;
                else
                    $location .= ", ".$value->country;
            }
            $jobs_list[$i]['location'] = $location;
            $jobs_list[$i]['qual'] = $value->qualifications;
            $jobs_list[$i]['min_ctc'] = $min_ctc;
            $jobs_list[$i]['max_ctc'] = $max_ctc;
            $jobs_list[$i]['industry'] = $value->industry_name;
            $jobs_list[$i]['desired_candidate'] = $value->desired_candidate;
            $jobs_list[$i]['open_date'] = $value->date_opened;
            $jobs_list[$i]['close_date'] = $value->target_date;
            $jobs_list[$i]['am_name'] = $value->am_name;
            $jobs_list[$i]['hiring_manager_id'] = $value->hiring_manager_id;
            $jobs_list[$i]['associate_candidate_cnt'] = $value->count;
            $jobs_list[$i]['priority'] = $value->priority;
            $jobs_list[$i]['created_date'] = date('d-m-Y',strtotime($value->created_at));
            if(isset($value->priority) && $value->priority!='') {
                $jobs_list[$i]['color'] = $colors[$value->priority];
            }
            else
                $jobs_list[$i]['color'] ='';

            // Admin/super admin have access to all details
            if($all==1){

                // Strategy and coordinator role dont have all access
                if($isStrategy){
                    $jobs_list[$i]['access'] = '0';
                }
                else{
                    $jobs_list[$i]['access'] = '1';
                }

                $jobs_list[$i]['coordinator_name'] = $value->coordinator_name;
                //$jobs_list[$i]['access'] = '1';
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
