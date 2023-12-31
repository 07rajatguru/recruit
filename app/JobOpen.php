<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Date;

class JobOpen extends Model
{
    public $table = "job_openings";

    public static $rules = array(
        'posting_title' => 'required',
        'client_id' => 'required',
        'date_opened' => 'required|date',
    );

    public function messages() {

        return [
            'posting_title.required' => 'Posting Title is required field',
            'client_id.required' => 'Client is required field',
            'date_opened.required' => 'Opened Date is required field',
        ];
    }

    public $upload_type = array('Job Description'=>'Job Description','Candidate Tracker'=>'Candidate Tracker');

    public static function getJobTypes() {

        $job_types = array();
        $job_types['Full time'] = 'Full time';
        $job_types['Part time'] = 'Part time';
        $job_types['Temporary'] = 'Temporary';
        $job_types['Contract'] = 'Contract';
        $job_types['Temporary to permanent'] = 'Temporary to permanent';

        return $job_types;
    }

    public static function getSalaryArray() {

        $job_salary = array();
        $job_salary['0'] = '10';
        $job_salary['1'] = '10-20';
        $job_salary['2'] = '20';

        return $job_salary;
    }
    
    public static function getPriorityMapping()
    {
        return [
            '-None-' => 0,
            'Urgent Positions' => 1,
            'New Positions' => 2,
            'Constant Deliveries needed' => 3,
            'On Hold' => 4,
            'Revived Positions' => 5,
            'Constant Deliveries needed for very old positions where many deliveries are done but no result yet' => 6,
            'No Deliveries Needed' => 7,
            'Identified candidates' => 8,
            'Closed By Us' => 9,
            'Closed By Client' => 10,
        ];
    }

    public static function getJobPriorities() {

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

    public static function getJobPrioritiesColor() {

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

    public static function getNewJobPriorities() {

        $job_priorities = array();
        $job_priorities['0'] = '-None-';
        $job_priorities['1'] = 'Urgent Positions';
        $job_priorities['2'] = 'New Positions';
        $job_priorities['3'] = 'Constant Deliveries needed';
        //$job_priorities['4'] = 'On Hold';
        $job_priorities['5'] = 'Revived Positions';
        //$job_priorities['6'] = 'Constant Deliveries needed for very old positions where many deliveries are done but no result yet';
        $job_priorities['7'] = 'No Deliveries Needed';
        $job_priorities['8'] = 'Identified candidates';
        //$job_priorities['9'] = 'Closed By Us';
        //$job_priorities['10'] = 'Closed By Client';

        return $job_priorities;
    }

    public static function getJobPostingStatus() {

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

    public static function getJobStatus() {

        $job_priorities = array();
        $job_priorities['0'] ='Select';
        $job_priorities['1'] ='On Hold';
        $job_priorities['2'] ='Closed By Us';
        $job_priorities['3'] = 'Closed By Client';

        return $job_priorities;
    }

    public static function getJobSearchOptions() {

        $job_search = array();
        $job_search['1'] = 'Naukri';
        $job_search['2'] = 'Monster';

        return $job_search;
    }

    public static function getShortlistType() {
        
        $candidate_short = array();
        $candidate_short['1'] = 'Round 1';
        $candidate_short['2'] = 'Round 2';
        $candidate_short['3'] = 'Round 3';

        return $candidate_short;
    }

    public static function getAllHierarchyName() {

        $client_hierarchy_name = array('' => 'Select Position');
        $client_hierarchy_name['1'] = 'Below AM';
        $client_hierarchy_name['2'] = 'Above AM';

        return $client_hierarchy_name;
    }

    public static function getJobsFieldsList() {

        $field_list = array();
        
        $field_list[''] = 'Select Field';
        $field_list['Job Position'] = 'Job Position';
        $field_list['Managed By'] = 'Managed By';
        $field_list['Company Name'] = 'Company Name';
        $field_list['Posting Title'] = 'Posting Title';
        $field_list['Location'] = 'Location';
        $field_list['Min CTC'] = 'Min CTC';
        $field_list['Max CTC'] = 'Max CTC';
        $field_list['Added Date'] = 'Added Date';
        $field_list['No. Of Positions'] = 'No. Of Positions';

        return $field_list;
    }

    public static function getJobOpeningId() {

        $jobOpenDetails = JobOpen::all();

        $jobOpenId = array('' => 'Select Job');

        if(isset($jobOpenDetails) && sizeof($jobOpenDetails)>0){
            foreach ($jobOpenDetails as $jobOpenDetail) {
                $jobOpenId[$jobOpenDetail->job_id] = $jobOpenDetail->job_id;
            }
        }
        return $jobOpenId;
    }

    public static function getAllJobsPostingTitleCount($search=0) {

        $job_query = JobOpen::query();
        
        $job_query = $job_query->leftJoin('job_associate_candidates','job_openings.id','=','job_associate_candidates.job_id');

        $job_query = $job_query->select(\DB::raw("COUNT(job_associate_candidates.candidate_id) as count"),'job_openings.id','job_openings.posting_title','job_openings.priority','job_openings.city as city','job_openings.remote_working as remote_working');

        $job_query = $job_query->where('job_associate_candidates.deleted_at',NULL);
        $job_query = $job_query->groupBy('job_openings.id');

        if (isset($search) && $search != '') {
            $job_query = $job_query->where(function($job_query) use ($search) {

                $job_query = $job_query->where('job_openings.posting_title','like',"%$search%");
                $job_query = $job_query->orwhere('job_openings.city','like',"%$search%");

                if(($search == 'Remote') || ($search == 'remote')) {

                    $job_query = $job_query->orwhere('job_openings.remote_working','=',"1");
                }
            });
        }

        $job_response = $job_query->get();

        return sizeof($job_response);
    }

    public static function getAllJobsPostingTitle($limit=0,$offset=0,$search=0,$order=NULL,$type='desc') {

        $job_query = JobOpen::query();
        
        $job_query = $job_query->leftJoin('job_associate_candidates','job_openings.id','=','job_associate_candidates.job_id');

        $job_query = $job_query->select(\DB::raw("COUNT(job_associate_candidates.candidate_id) as count"),'job_openings.id','job_openings.posting_title','job_openings.priority','job_openings.city as city','job_openings.level_id as level_id','job_openings.remote_working as remote_working');

        $job_query = $job_query->where('job_associate_candidates.deleted_at',NULL);
        $job_query = $job_query->groupBy('job_openings.id');

        if (isset($order) && $order != '') {
            $job_query = $job_query->orderBy($order,$type);
        }
        if (isset($limit) && $limit > 0) {
            $job_query = $job_query->limit($limit);
        }
        if (isset($offset) && $offset > 0) {
            $job_query = $job_query->offset($offset);
        }
        if (isset($search) && $search != '') {

            $job_query = $job_query->where(function($job_query) use ($search) {

                $job_query = $job_query->where('job_openings.posting_title','like',"%$search%");
                $job_query = $job_query->orwhere('job_openings.city','like',"%$search%");

                if(($search == 'Remote') || ($search == 'remote')) {
                    
                    $job_query = $job_query->orwhere('job_openings.remote_working','=',"1");
                }
            });
        }

        $job_response = $job_query->get();

        $jobs_list = array();
        $colors = self::getJobPrioritiesColor();
        $i = 0;

        foreach ($job_response as $key => $value) {

            $jobs_list[$i]['id'] = $value->id;
            if (isset($value->level_name) && $value->level_name != '') {
                $jobs_list[$i]['posting_title'] = $value->level_name." - ".$value->posting_title;
            }
            else {
                $jobs_list[$i]['posting_title'] = $value->posting_title;
            }

            if(isset($value->priority) && $value->priority!='') {
                $jobs_list[$i]['color'] = $colors[$value->priority];
            }
            else {
                $jobs_list[$i]['color'] ='';
            }

            $jobs_list[$i]['associate_candidate_cnt'] = $value->count;

            if($value->remote_working == '1') {

                $jobs_list[$i]['city'] = "Remote";
            }
            else {

                $jobs_list[$i]['city'] = $value->city;
            }
            
            $jobs_list[$i]['level_id'] = $value->level_id;
            $i++;
        }
        return $jobs_list;
    }

    public static function getClosedJobs($all=0,$user_id,$limit=0,$offset=0,$search=0,$order=NULL,$type='desc',$current_year=NULL,$next_year=NULL,$client_heirarchy=0,$mb_name='',$company_name='',$posting_title='',$location='',$min_ctc='',$max_ctc='',$added_date='',$no_of_positions='') {

        $job_onhold = getenv('ONHOLD');
        $job_client = getenv('CLOSEDBYCLIENT');
        $job_us = getenv('CLOSEDBYUS');
        $job_status = array($job_onhold,$job_us,$job_client);

        $job_close_query = JobOpen::query();

        $job_close_query = $job_close_query->select(\DB::raw("COUNT(job_associate_candidates.candidate_id) as count"),'job_openings.id','job_openings.job_id','client_basicinfo.name as company_name','job_openings.no_of_positions','job_openings.posting_title','job_openings.city','job_openings.state','job_openings.country','job_openings.qualifications','job_openings.salary_from','job_openings.salary_to','job_openings.lacs_from','job_openings.thousand_from','job_openings.lacs_to','job_openings.thousand_to','industry.name as industry_name','job_openings.desired_candidate','job_openings.date_opened','job_openings.target_date','users.name as am_name','client_basicinfo.coordinator_name as coordinator_name','job_openings.priority','job_openings.hiring_manager_id','client_basicinfo.display_name','job_openings.created_at','job_openings.updated_at as updated_at','client_basicinfo.second_line_am as second_line_am','job_openings.remote_working as remote_working','job_openings.priority_by');

        $job_close_query = $job_close_query->leftJoin('job_associate_candidates','job_openings.id','=','job_associate_candidates.job_id');
        $job_close_query = $job_close_query->join('client_basicinfo','client_basicinfo.id','=','job_openings.client_id');
        $job_close_query = $job_close_query->leftjoin('users','users.id','=','job_openings.hiring_manager_id');
        $job_close_query = $job_close_query->leftJoin('industry','industry.id','=','job_openings.industry_id');

        // assign jobs to logged in user
        if($all==0) {
            $job_close_query = $job_close_query->join('job_visible_users','job_visible_users.job_id','=','job_openings.id');
            $job_close_query = $job_close_query->where('user_id','=',$user_id);
        }

        $job_close_query = $job_close_query->whereIn('priority',$job_status);
        $job_close_query = $job_close_query->where('job_associate_candidates.deleted_at',NULL);
        $job_close_query = $job_close_query->groupBy('job_openings.id');

        // Get data by financial year
        $job_close_query = $job_close_query->where('job_openings.created_at','>=',$current_year);
        $job_close_query = $job_close_query->where('job_openings.created_at','<=',$next_year);

        if (isset($order) && $order != '') {
            if ($order == 'job_openings.lacs_from') {
                $job_close_query = $job_close_query->orderBy($order,$type);
                $job_close_query = $job_close_query->orderBy('job_openings.thousand_from',$type);
            }
            else if ($order == 'job_openings.lacs_to') {
                $job_close_query = $job_close_query->orderBy($order,$type);
                $job_close_query = $job_close_query->orderBy('job_openings.thousand_to',$type);
            }
            else{
                $job_close_query = $job_close_query->orderBy($order,$type);
            }
        }
        if (isset($limit) && $limit > 0) {
            $job_close_query = $job_close_query->limit($limit);
        }
        if (isset($offset) && $offset > 0) {
            $job_close_query = $job_close_query->offset($offset);
        }
        if (isset($search) && $search != '') {

            $job_close_query = $job_close_query->where(function($job_close_query) use ($search) {

                $date_search = false;
                $date_array = explode("-",$search);
                if(isset($date_array) && sizeof($date_array)>0){
                    $stamp = strtotime($search);
                    if (is_numeric($stamp)){
                        $month = date( 'm', $stamp );
                        $day   = date( 'd', $stamp );
                        $year  = date( 'Y', $stamp );

                        if(checkdate($month, $day, $year)){
                            $date_search = true;
                        }
                    }
                }

                $job_close_query = $job_close_query->where('job_openings.posting_title','like',"%$search%");
                $job_close_query = $job_close_query->orwhere('users.name','like',"%$search%");
                $job_close_query = $job_close_query->orwhere('client_basicinfo.display_name','like',"%$search%");
                $job_close_query = $job_close_query->orwhere('client_basicinfo.coordinator_name','like',"%$search%");
                $job_close_query = $job_close_query->orwhere('job_openings.no_of_positions','like',"%$search%");
                $job_close_query = $job_close_query->orwhere('job_openings.city','like',"%$search%");

                if(($search == 'Remote') || ($search == 'remote')) {
                    
                    $job_close_query = $job_close_query->orwhere('job_openings.remote_working','=',"1");
                }
                
                if($date_search) {
                   
                    $dateClass = new Date();
                    $search_string = $dateClass->changeDMYtoYMD($search);
                    $from_date = date("Y-m-d 00:00:00",strtotime($search_string));
                    $to_date = date("Y-m-d 23:59:59",strtotime($search_string));
                    $job_close_query = $job_close_query->orwhere('job_openings.created_at','>=',"$from_date");
                    $job_close_query = $job_close_query->Where('job_openings.created_at','<=',"$to_date");
                }

                if(($search == 'on') || ($search == 'On') || ($search == 'On Hold') || ($search == 'on hold') || ($search == 'hold') || ($search == 'Hold') || ($search == 'On hold') || ($search == 'on Hold')) {

                    $search = 4;
                    $job_close_query = $job_close_query->orwhere('job_openings.priority','like',"%$search%");
                }

                if(($search == 'Closed') || ($search == 'closed') || ($search == 'Closed By') || ($search == 'closed by') || ($search == 'Closed By Us') || ($search == 'closed by us')) {

                    $search = 9;
                    $job_close_query = $job_close_query->orwhere('job_openings.priority','like',"%$search%");
                }

                if(($search == 'Closed') || ($search == 'closed') || ($search == 'Closed By') || ($search == 'closed by') || ($search == 'Closed By Client') || ($search == 'closed by client')) {

                    $search = 10;
                    $job_close_query = $job_close_query->orwhere('job_openings.priority','like',"%$search%");
                }
            });
        }

        // Master Search Condidtions
        if(isset($mb_name) && $mb_name != '') {

            $job_close_query = $job_close_query->where('job_openings.hiring_manager_id','=',$mb_name);
        }
        else if(isset($company_name) && $company_name != '') {

            $job_close_query = $job_close_query->where('client_basicinfo.name','like',"%$company_name%");
        }
        else if(isset($posting_title) && $posting_title != '') {

            $job_close_query = $job_close_query->where('job_openings.posting_title','like',"%$posting_title%");
        }
        else if(isset($location) && $location != '') {

            if($location == 'Remote') {

                $job_close_query = $job_close_query->where('job_openings.remote_working','=',"1");
            }
            else {

                $job_close_query = $job_close_query->where('job_openings.city','=',"$location");
                $job_close_query = $job_close_query->where('job_openings.remote_working','!=',"1");
            }
        }
        else if (isset($min_ctc) && $min_ctc != '') {

            if($min_ctc == '30') {

                $job_close_query = $job_close_query->where('job_openings.lacs_from','>=',30);
            }
            else if($min_ctc == '0.5') {

                $job_close_query = $job_close_query->where('job_openings.lacs_from','=',0);
            }
            else {
                $job_close_query = $job_close_query->where('job_openings.lacs_from','=',$min_ctc);
            }
        }
        else if (isset($max_ctc) && $max_ctc != '') {

            if($max_ctc == '30') {
                $job_close_query = $job_close_query->where('job_openings.lacs_to','>=',30);
            }
            else if($max_ctc == '0.5') {

                $job_close_query = $job_close_query->where('job_openings.lacs_to','=',0);
            }
            else {
                $job_close_query = $job_close_query->where('job_openings.lacs_to','=',$max_ctc);
            }
        }

        else if(isset($added_date) && $added_date != '') {

            $f_dt = date("Y-m-d 00:00:00",strtotime($added_date));
            $t_dt = date("Y-m-d 23:59:59",strtotime($added_date));

            $job_close_query = $job_close_query->where('job_openings.created_at','>=',"$f_dt");
            $job_close_query = $job_close_query->where('job_openings.created_at','<=',"$t_dt");
        }
        else if(isset($no_of_positions) && $no_of_positions != '') {

            $job_close_query = $job_close_query->where('job_openings.no_of_positions','=',$no_of_positions);
        }
        else if (isset($client_heirarchy) && $client_heirarchy > 0) {
            $job_close_query = $job_close_query->where('job_openings.level_id','=',$client_heirarchy);
        }
        
        $job_response = $job_close_query->get();

        $jobs_list = array();
        $colors = self::getJobPrioritiesColor();
        $i = 0;

        foreach ($job_response as $key => $value) {

            // value get in 2 decimal point
            if ($value->lacs_from >= '100') {
                $min_ctc = '100+';
            }
            else {
                $lacs_from = $value->lacs_from*100000;
                $thousand_from = $value->thousand_from*1000;
                $mictc = $lacs_from+$thousand_from;
                $minctc = $mictc/100000;
                $min_ctc = number_format($minctc,2);
            }

            if ($value->lacs_to >= '100') {
                $max_ctc = '100+';
            }
            else {
                $lacs_to = $value->lacs_to*100000;
                $thousand_to = $value->thousand_to*1000;
                $mactc = $lacs_to+$thousand_to;
                $maxctc = $mactc/100000;
                $max_ctc = number_format($maxctc,2);
            }
            
            $jobs_list[$i]['id'] = $value->id;
            $jobs_list[$i]['job_id'] = $value->job_id;
            $jobs_list[$i]['company_name'] = $value->company_name;
            $jobs_list[$i]['display_name'] = $value->display_name;
            $jobs_list[$i]['client'] = $value->company_name." - ".$value->coordinator_name;
            $jobs_list[$i]['no_of_positions'] = $value->no_of_positions;

            if (isset($value->level_name) && $value->level_name != '') {
                $jobs_list[$i]['posting_title'] = $value->level_name." - ".$value->posting_title;
            }
            else {
                $jobs_list[$i]['posting_title'] = $value->posting_title;
            }
            
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
            $jobs_list[$i]['hiring_manager_id'] = $value->hiring_manager_id;
            $jobs_list[$i]['associate_candidate_cnt'] = $value->count;
            $jobs_list[$i]['priority'] = $value->priority;
            $jobs_list[$i]['priority_by'] = $value->priority_by;
            $jobs_list[$i]['created_date'] = date('d-m-Y',strtotime($value->created_at));

            if(isset($value->priority) && $value->priority!='') {
                $jobs_list[$i]['color'] = $colors[$value->priority];
            }
            else
                $jobs_list[$i]['color'] ='';

            if($all==1) {
                
                $jobs_list[$i]['access'] = '1';
                $jobs_list[$i]['coordinator_name'] = $value->coordinator_name;
            }
            else {
                if(isset($value->hiring_manager_id) && $value->hiring_manager_id == $user_id) {
                    $jobs_list[$i]['coordinator_name'] = $value->coordinator_name;
                    $jobs_list[$i]['access'] = '1';
                }
                else if(isset($value->second_line_am) && $value->second_line_am == $user_id) {
                    $jobs_list[$i]['coordinator_name'] = $value->coordinator_name;
                    $jobs_list[$i]['access'] = '1';
                }
                else{
                    $jobs_list[$i]['coordinator_name'] = '';
                    $jobs_list[$i]['access'] = '0';
                }
            }

            if($value->hiring_manager_id == 0) {

                $jobs_list[$i]['am_name'] = 'Yet to Assign';
            }
            else {

                $jobs_list[$i]['am_name'] = $value->am_name;
            }
            
            if($value->remote_working == '1') {

                $jobs_list[$i]['city'] = "Remote";
            }
            else {

                $jobs_list[$i]['city'] = $value->city;
            }

            $i++;
        }
        return $jobs_list;   
    }

    public static function getClosedJobsByClient($client_id,$limit=0,$offset=0,$search=0,$order=NULL,$type='desc',$current_year=NULL,$next_year=NULL,$client_heirarchy=0,$mb_name='',$company_name='',$posting_title='',$location='',$min_ctc='',$max_ctc='',$added_date='',$no_of_positions='') {

        $job_onhold = getenv('ONHOLD');
        $job_client = getenv('CLOSEDBYCLIENT');
        $job_us = getenv('CLOSEDBYUS');
        $job_status = array($job_onhold,$job_us,$job_client);

        $job_close_query = JobOpen::query();

        $job_close_query = $job_close_query->select(\DB::raw("COUNT(job_associate_candidates.candidate_id) as count"),'job_openings.id','job_openings.job_id','client_basicinfo.name as company_name','job_openings.no_of_positions','job_openings.posting_title','job_openings.city','job_openings.state','job_openings.country','job_openings.qualifications','job_openings.salary_from','job_openings.salary_to','job_openings.lacs_from','job_openings.thousand_from','job_openings.lacs_to','job_openings.thousand_to','industry.name as industry_name','job_openings.desired_candidate','job_openings.date_opened','job_openings.target_date','users.name as am_name','client_basicinfo.coordinator_name as coordinator_name','job_openings.priority','job_openings.hiring_manager_id','client_basicinfo.display_name','job_openings.created_at','job_openings.remote_working as remote_working','job_openings.priority_by');

        $job_close_query = $job_close_query->leftJoin('job_associate_candidates','job_openings.id','=','job_associate_candidates.job_id');
        $job_close_query = $job_close_query->join('client_basicinfo','client_basicinfo.id','=','job_openings.client_id');
        $job_close_query = $job_close_query->leftjoin('users','users.id','=','job_openings.hiring_manager_id');
        $job_close_query = $job_close_query->leftJoin('industry','industry.id','=','job_openings.industry_id');

        $job_close_query = $job_close_query->whereIn('priority',$job_status);
        $job_close_query = $job_close_query->where('job_associate_candidates.deleted_at',NULL);
        $job_close_query = $job_close_query->where('job_openings.client_id',$client_id);
        $job_close_query = $job_close_query->groupBy('job_openings.id');

        if (isset($order) && $order != '') {

            if ($order == 'job_openings.lacs_from') {
                $job_close_query = $job_close_query->orderBy($order,$type);
                $job_close_query = $job_close_query->orderBy('job_openings.thousand_from',$type);
            }
            else if ($order == 'job_openings.lacs_to') {
                $job_close_query = $job_close_query->orderBy($order,$type);
                $job_close_query = $job_close_query->orderBy('job_openings.thousand_to',$type);
            }
            else{
                $job_close_query = $job_close_query->orderBy($order,$type);
            }
        }

        if (isset($limit) && $limit > 0) {
            $job_close_query = $job_close_query->limit($limit);
        }
        if (isset($offset) && $offset > 0) {
            $job_close_query = $job_close_query->offset($offset);
        }
        if (isset($search) && $search != '') {

            $job_close_query = $job_close_query->where(function($job_close_query) use ($search) {

                $job_close_query = $job_close_query->where('job_openings.posting_title','like',"%$search%");
                $job_close_query = $job_close_query->orwhere('users.name','like',"%$search%");
                $job_close_query = $job_close_query->orwhere('client_basicinfo.display_name','like',"%$search%");
                $job_close_query = $job_close_query->orwhere('client_basicinfo.coordinator_name','like',"%$search%");
                $job_close_query = $job_close_query->orwhere('job_openings.no_of_positions','like',"%$search%");
                $job_close_query = $job_close_query->orwhere('job_openings.city','like',"%$search%");

                if(($search == 'Remote') || ($search == 'remote')) {
                    
                    $job_close_query = $job_close_query->orwhere('job_openings.remote_working','=',"1");
                }
            });
        }

        // Master Search Condidtions
        if(isset($mb_name) && $mb_name != '') {

            $job_close_query = $job_close_query->where('job_openings.hiring_manager_id','=',$mb_name);
        }
        else if(isset($company_name) && $company_name != '') {

            $job_close_query = $job_close_query->where('client_basicinfo.name','like',"%$company_name%");
        }
        else if(isset($posting_title) && $posting_title != '') {

            $job_close_query = $job_close_query->where('job_openings.posting_title','like',"%$posting_title%");
        }
        else if(isset($location) && $location != '') {

            if($location == 'Remote') {

                $job_close_query = $job_close_query->where('job_openings.remote_working','=',"1");
            }
            else {

                $job_close_query = $job_close_query->where('job_openings.city','=',"$location");
                $job_close_query = $job_close_query->where('job_openings.remote_working','!=',"1");
            }
        }
        else if (isset($min_ctc) && $min_ctc != '') {

            if($min_ctc == '30') {

                $job_close_query = $job_close_query->where('job_openings.lacs_from','>=',30);
            }
            else if($min_ctc == '0.5') {

                $job_close_query = $job_close_query->where('job_openings.lacs_from','=',0);
            }
            else {
                $job_close_query = $job_close_query->where('job_openings.lacs_from','=',$min_ctc);
            }
        }
        else if (isset($max_ctc) && $max_ctc != '') {

            if($max_ctc == '30') {
                $job_close_query = $job_close_query->where('job_openings.lacs_to','>=',30);
            }
            else if($max_ctc == '0.5') {

                $job_close_query = $job_close_query->where('job_openings.lacs_to','=',0);
            }
            else {
                $job_close_query = $job_close_query->where('job_openings.lacs_to','=',$max_ctc);
            }
        }
        else if(isset($added_date) && $added_date != '') {

            $f_dt = date("Y-m-d 00:00:00",strtotime($added_date));
            $t_dt = date("Y-m-d 23:59:59",strtotime($added_date));

            $job_close_query = $job_close_query->where('job_openings.created_at','>=',"$f_dt");
            $job_close_query = $job_close_query->where('job_openings.created_at','<=',"$t_dt");
        }
        else if(isset($no_of_positions) && $no_of_positions != '') {

            $job_close_query = $job_close_query->where('job_openings.no_of_positions','=',$no_of_positions);
        }
        else if (isset($client_heirarchy) && $client_heirarchy > 0) {
            $job_close_query = $job_close_query->where('job_openings.level_id','=',$client_heirarchy);
        }

        // Get data by financial year
        if (isset($current_year) && $current_year != NULL) {
            $job_close_query = $job_close_query->where('job_openings.created_at','>=',$current_year);
        }
        if (isset($next_year) && $next_year != NULL) {
            $job_close_query = $job_close_query->where('job_openings.created_at','<=',$next_year);
        }
        
        $job_response = $job_close_query->get();

        $jobs_list = array();
        $colors = self::getJobPrioritiesColor();
        $i = 0;

        foreach ($job_response as $key => $value) {

            // value get in 2 decimal point
            if ($value->lacs_from >= '100') {
                $min_ctc = '100+';
            }
            else {
                $lacs_from = $value->lacs_from*100000;
                $thousand_from = $value->thousand_from*1000;
                $mictc = $lacs_from+$thousand_from;
                $minctc = $mictc/100000;
                $min_ctc = number_format($minctc,2);
            }

            if ($value->lacs_to >= '100') {
                $max_ctc = '100+';
            }
            else {
                $lacs_to = $value->lacs_to*100000;
                $thousand_to = $value->thousand_to*1000;
                $mactc = $lacs_to+$thousand_to;
                $maxctc = $mactc/100000;
                $max_ctc = number_format($maxctc,2);
            }
            
            $jobs_list[$i]['id'] = $value->id;
            $jobs_list[$i]['job_id'] = $value->job_id;
            $jobs_list[$i]['company_name'] = $value->company_name;
            $jobs_list[$i]['display_name'] = $value->display_name;
            $jobs_list[$i]['client'] = $value->company_name." - ".$value->coordinator_name;
            $jobs_list[$i]['no_of_positions'] = $value->no_of_positions;

            if (isset($value->level_name) && $value->level_name != '') {
                $jobs_list[$i]['posting_title'] = $value->level_name." - ".$value->posting_title;
            }
            else {
                $jobs_list[$i]['posting_title'] = $value->posting_title;
            }

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
            if($value->country!='') {
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
            $jobs_list[$i]['hiring_manager_id'] = $value->hiring_manager_id;
            $jobs_list[$i]['associate_candidate_cnt'] = $value->count;
            $jobs_list[$i]['priority'] = $value->priority;
            $jobs_list[$i]['priority_by'] = $value->priority_by;
            $jobs_list[$i]['created_date'] = date('d-m-Y',strtotime($value->created_at));
            $jobs_list[$i]['updated_date'] = date('d-m-Y',strtotime($value->updated_at));

            if(isset($value->priority) && $value->priority!='') {
                $jobs_list[$i]['color'] = $colors[$value->priority];
            }
            else{
                $jobs_list[$i]['color'] ='';
            }
            $jobs_list[$i]['coordinator_name'] = $value->coordinator_name;
            $jobs_list[$i]['access'] = '0';

            if($value->hiring_manager_id == 0) {

                $jobs_list[$i]['am_name'] = 'Yet to Assign';
            }
            else {

                $jobs_list[$i]['am_name'] = $value->am_name;
            }

            if($value->remote_working == '1') {

                $jobs_list[$i]['city'] = "Remote";
            }
            else {

                $jobs_list[$i]['city'] = $value->city;
            }

            $i++;
        }

        return $jobs_list;   
    }

    public static function getAllClosedJobsCount($all=0,$user_id,$search,$current_year,$next_year,$client_heirarchy=0,$mb_name='',$company_name='',$posting_title='',$location='',$min_ctc='',$max_ctc='',$added_date='',$no_of_positions='') {

        $job_onhold = getenv('ONHOLD');
        $job_client = getenv('CLOSEDBYCLIENT');
        $job_us = getenv('CLOSEDBYUS');
        $job_status = array($job_onhold,$job_us,$job_client);

        $job_open_query = JobOpen::query();
        $job_open_query = $job_open_query->select(\DB::raw("COUNT(job_associate_candidates.candidate_id) as count"),'job_openings.id','job_openings.job_id','client_basicinfo.name as company_name','job_openings.no_of_positions','job_openings.posting_title','job_openings.city','job_openings.state','job_openings.country','job_openings.qualifications','job_openings.salary_from','job_openings.salary_to','job_openings.lacs_from','job_openings.thousand_from','job_openings.lacs_to','job_openings.thousand_to','industry.name as industry_name','job_openings.desired_candidate','job_openings.date_opened','job_openings.target_date','users.name as am_name','client_basicinfo.coordinator_name as coordinator_name','job_openings.priority','job_openings.hiring_manager_id','client_basicinfo.display_name','job_openings.remote_working as remote_working');

        $job_open_query = $job_open_query->leftJoin('job_associate_candidates','job_openings.id','=','job_associate_candidates.job_id');
        $job_open_query = $job_open_query->join('client_basicinfo','client_basicinfo.id','=','job_openings.client_id');
        $job_open_query = $job_open_query->leftjoin('users','users.id','=','job_openings.hiring_manager_id');
        $job_open_query = $job_open_query->leftJoin('industry','industry.id','=','job_openings.industry_id');

        // assign jobs to logged in user
        if($all==0) {
            $job_open_query = $job_open_query->join('job_visible_users','job_visible_users.job_id','=','job_openings.id');
            $job_open_query = $job_open_query->where('user_id','=',$user_id);
        }

        $job_open_query = $job_open_query->whereIn('priority',$job_status);
        $job_open_query = $job_open_query->where('job_associate_candidates.deleted_at',NULL);
        $job_open_query = $job_open_query->groupBy('job_openings.id');

        $job_open_query = $job_open_query->orderBy('job_openings.updated_at','desc');

        if (isset($search) && $search != '') {

            $job_open_query = $job_open_query->where(function($job_open_query) use ($search) {

                $job_open_query = $job_open_query->where('job_openings.posting_title','like',"%$search%");
                $job_open_query = $job_open_query->orwhere('users.name','like',"%$search%");
                $job_open_query = $job_open_query->orwhere('client_basicinfo.display_name','like',"%$search%");
                $job_open_query = $job_open_query->orwhere('client_basicinfo.coordinator_name','like',"%$search%");
                $job_open_query = $job_open_query->orwhere('job_openings.no_of_positions','like',"%$search%");
                $job_open_query = $job_open_query->orwhere('job_openings.city','like',"%$search%");

                if(($search == 'Remote') || ($search == 'remote')) {
                    
                    $job_open_query = $job_open_query->orwhere('job_openings.remote_working','=',"1");
                }
            });
        }

        // Master Search Condidtions
        if(isset($mb_name) && $mb_name != '') {

            $job_open_query = $job_open_query->where('job_openings.hiring_manager_id','=',$mb_name);
        }
        else if(isset($company_name) && $company_name != '') {

            $job_open_query = $job_open_query->where('client_basicinfo.name','like',"%$company_name%");
        }
        else if(isset($posting_title) && $posting_title != '') {

            $job_open_query = $job_open_query->where('job_openings.posting_title','like',"%$posting_title%");
        }
        else if(isset($location) && $location != '') {

            if($location == 'Remote') {

                $job_open_query = $job_open_query->where('job_openings.remote_working','=',"1");
            }
            else {

                $job_open_query = $job_open_query->where('job_openings.city','=',"$location");
                $job_open_query = $job_open_query->where('job_openings.remote_working','!=',"1");
            }
        }
        else if (isset($min_ctc) && $min_ctc != '') {

            if($min_ctc == '30') {

                $job_open_query = $job_open_query->where('job_openings.lacs_from','>=',30);
            }
            else if($min_ctc == '0.5') {

                $job_open_query = $job_open_query->where('job_openings.lacs_from','=',0);
            }
            else {
                $job_open_query = $job_open_query->where('job_openings.lacs_from','=',$min_ctc);
            }
        }
        else if (isset($max_ctc) && $max_ctc != '') {

            if($max_ctc == '30') {
                $job_open_query = $job_open_query->where('job_openings.lacs_to','>=',30);
            }
            else if($max_ctc == '0.5') {

                $job_open_query = $job_open_query->where('job_openings.lacs_to','=',0);
            }
            else {
                $job_open_query = $job_open_query->where('job_openings.lacs_to','=',$max_ctc);
            }
        }
        else if(isset($added_date) && $added_date != '') {

            $f_dt = date("Y-m-d 00:00:00",strtotime($added_date));
            $t_dt = date("Y-m-d 23:59:59",strtotime($added_date));

            $job_open_query = $job_open_query->where('job_openings.created_at','>=',"$f_dt");
            $job_open_query = $job_open_query->where('job_openings.created_at','<=',"$t_dt");
        }
        else if(isset($no_of_positions) && $no_of_positions != '') {

            $job_open_query = $job_open_query->where('job_openings.no_of_positions','=',$no_of_positions);
        }
        else if (isset($client_heirarchy) && $client_heirarchy > 0) {
            $job_open_query = $job_open_query->where('job_openings.level_id','=',$client_heirarchy);
        }

        // Get data by financial year
        $job_open_query = $job_open_query->where('job_openings.created_at','>=',$current_year);
        $job_open_query = $job_open_query->where('job_openings.created_at','<=',$next_year);
        $job_response = $job_open_query->get();

        return sizeof($job_response);
    }

    public static function getJobsByIds($all=0,$ids) {

        $user_id = \Auth::user()->id;
        $job_open_query = JobOpen::query();

        $job_open_query = $job_open_query->select(\DB::raw("COUNT(job_associate_candidates.candidate_id) as count"),'job_openings.id','job_openings.job_id','client_basicinfo.name as company_name','job_openings.no_of_positions','job_openings.posting_title','job_openings.city','job_openings.state','job_openings.country','job_openings.qualifications','job_openings.salary_from','job_openings.salary_to','industry.name as industry_name','job_openings.desired_candidate','job_openings.date_opened','job_openings.target_date','users.name as am_name','client_basicinfo.coordinator_name as coordinator_name','job_openings.priority','job_openings.hiring_manager_id','job_openings.remote_working as remote_working');

        $job_open_query = $job_open_query->leftJoin('job_associate_candidates','job_openings.id','=','job_associate_candidates.job_id');
        $job_open_query = $job_open_query->join('client_basicinfo','client_basicinfo.id','=','job_openings.client_id');
        $job_open_query = $job_open_query->join('users','users.id','=','job_openings.hiring_manager_id');
        $job_open_query = $job_open_query->leftJoin('industry','industry.id','=','job_openings.industry_id');

        // assign jobs to logged in user
        if($all==0) {
            $job_open_query = $job_open_query->join('job_visible_users','job_visible_users.job_id','=','job_openings.id');
        }

        $job_open_query = $job_open_query->where('job_status',NULL);
        $job_open_query = $job_open_query->whereIn('job_openings.id',$ids);
        //$job_open_query = $job_open_query->where('job_associate_candidates.deleted_at','NULL');
        $job_open_query = $job_open_query->groupBy('job_openings.id');
        $job_open_query = $job_open_query->orderBy('job_openings.id','desc');

        $job_response = $job_open_query->get();

        $jobs_list = array();
        $colors = self::getJobPrioritiesColor();
        $i = 0;

        foreach ($job_response as $key => $value) {

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
            
            $jobs_list[$i]['qual'] = $value->qualifications;
            $jobs_list[$i]['min_ctc'] = $value->salary_from;
            $jobs_list[$i]['max_ctc'] = $value->salary_to;
            $jobs_list[$i]['industry'] = $value->industry_name;
            $jobs_list[$i]['desired_candidate'] = $value->desired_candidate;
            $jobs_list[$i]['open_date'] = $value->date_opened;
            $jobs_list[$i]['close_date'] = $value->desired_candidate;
            $jobs_list[$i]['hiring_manager_id'] = $value->hiring_manager_id;
            $jobs_list[$i]['associate_candidate_cnt'] = $value->count;

            if(isset($value->priority) && $value->priority!='') {
                $jobs_list[$i]['color'] = $colors[$value->priority];
            }
            else
                $jobs_list[$i]['color'] ='';

            if($all==1) {
                $jobs_list[$i]['coordinator_name'] = $value->coordinator_name;
                $jobs_list[$i]['access'] = '1';
            }
            else {

                if(isset($value->hiring_manager_id) && $value->hiring_manager_id == $user_id ) {
                    $jobs_list[$i]['coordinator_name'] = $value->coordinator_name;
                    $jobs_list[$i]['access'] = '1';
                }
                else {
                    $jobs_list[$i]['coordinator_name'] = '';
                    $jobs_list[$i]['access'] = '0';
                }
            }

            if($value->hiring_manager_id == 0) {

                $jobs_list[$i]['am_name'] = 'Yet to Assign';
            }
            else {

                $jobs_list[$i]['am_name'] = $value->am_name;
            }

            if($value->remote_working == '1') {

                $jobs_list[$i]['city'] = "Remote";
            }
            else {

                $jobs_list[$i]['city'] = $value->city;
            }
            $i++;
        }
        return $jobs_list;
    }

    public static function getAllBillsJobs($all=0,$user_id) {

        $job_onhold = env('ONHOLD');
        $job_client = env('CLOSEDBYCLIENT');
        $job_status = array($job_onhold,$job_client);

        $job_open_query = JobOpen::query();

        $job_open_query = $job_open_query->select(\DB::raw("COUNT(job_associate_candidates.candidate_id) as count"),'job_openings.id','job_openings.job_id','client_basicinfo.name as company_name','job_openings.no_of_positions','job_openings.posting_title','job_openings.city','job_openings.qualifications','job_openings.salary_from','job_openings.salary_to','industry.name as industry_name','job_openings.desired_candidate','job_openings.date_opened','job_openings.target_date','users.name as am_name','client_basicinfo.coordinator_name as coordinator_name','job_openings.priority','job_openings.hiring_manager_id','client_basicinfo.display_name','job_openings.remote_working as remote_working');

        $job_open_query = $job_open_query->leftJoin('job_associate_candidates','job_openings.id','=','job_associate_candidates.job_id');
        $job_open_query = $job_open_query->join('client_basicinfo','client_basicinfo.id','=','job_openings.client_id');
        $job_open_query = $job_open_query->join('users','users.id','=','job_openings.hiring_manager_id');
        $job_open_query = $job_open_query->leftJoin('industry','industry.id','=','job_openings.industry_id');

        // assign jobs to logged in user
        if($all==0) {
            $job_open_query = $job_open_query->join('job_visible_users','job_visible_users.job_id','=','job_openings.id');
            $job_open_query = $job_open_query->where('client_basicinfo.account_manager_id','=',$user_id);
            $job_open_query = $job_open_query->orWhere('client_basicinfo.second_line_am','=',$user_id);
        }

        $job_open_query = $job_open_query->whereNotIn('priority',$job_status);
        $job_open_query = $job_open_query->groupBy('job_openings.id');
        $job_open_query = $job_open_query->orderBy('job_openings.id','desc');

        $job_response = $job_open_query->get();

        $jobs_list = array();
        $colors = self::getJobPrioritiesColor();
        $i = 0;

        foreach ($job_response as $key => $value) {

            $jobs_list[$i]['id'] = $value->id;
            $jobs_list[$i]['job_id'] = $value->job_id;
            $jobs_list[$i]['company_name'] = $value->company_name;
            $jobs_list[$i]['display_name'] = $value->display_name;
            $jobs_list[$i]['client'] = $value->company_name." - ".$value->coordinator_name;
            $jobs_list[$i]['no_of_positions'] = $value->no_of_positions;

            if($value->remote_working == '1') {

                $jobs_list[$i]['location'] = "Remote";
            }
            else {

                $jobs_list[$i]['location'] = $value->city;
            }

            $jobs_list[$i]['qual'] = $value->qualifications;
            $jobs_list[$i]['min_ctc'] = $value->salary_from;
            $jobs_list[$i]['max_ctc'] = $value->salary_to;
            $jobs_list[$i]['industry'] = $value->industry_name;
            $jobs_list[$i]['desired_candidate'] = $value->desired_candidate;
            $jobs_list[$i]['open_date'] = $value->date_opened;
            $jobs_list[$i]['close_date'] = $value->desired_candidate;
            $jobs_list[$i]['hiring_manager_id'] = $value->hiring_manager_id;
            $jobs_list[$i]['associate_candidate_cnt'] = $value->count;

            if(isset($value->priority) && $value->priority!='') 
                $jobs_list[$i]['color'] = $colors[$value->priority];
            else
                $jobs_list[$i]['color'] ='';

            if($all==1) {
                $jobs_list[$i]['coordinator_name'] = $value->coordinator_name;
                $jobs_list[$i]['access'] = '1';
            }
            else {
                if(isset($value->hiring_manager_id) && $value->hiring_manager_id == $user_id ) {
                    $jobs_list[$i]['coordinator_name'] = $value->coordinator_name;
                    $jobs_list[$i]['access'] = '1';
                }
                else {
                    $jobs_list[$i]['coordinator_name'] = '';
                    $jobs_list[$i]['access'] = '0';
                }
            }

            if (isset($value->level_name) && $value->level_name != '') {
                $jobs_list[$i]['posting_title'] = $value->level_name." - ".$value->posting_title;
            }
            else {
                $jobs_list[$i]['posting_title'] = $value->posting_title;
            }

            if($value->hiring_manager_id == 0) {

                $jobs_list[$i]['am_name'] = 'Yet to Assign';
            }
            else {

                $jobs_list[$i]['am_name'] = $value->am_name;
            }
            $i++;
        }
        return $jobs_list;
    }

    public static function getAllJobs($all=0,$user_id,$limit=0,$offset=0,$search=0,$order=NULL,$type='desc',$current_year=NULL,$next_year=NULL,$client_heirarchy=0,$mb_name='',$company_name='',$posting_title='',$location='',$min_ctc='',$max_ctc='',$added_date=NULL,$no_of_positions='',$data_source = null) {

        $job_onhold = getenv('ONHOLD');
        $job_client = getenv('CLOSEDBYCLIENT');
        $job_us = getenv('CLOSEDBYUS');
        $job_status = array($job_onhold,$job_us,$job_client);

        $job_open_query = JobOpen::query();
        $job_open_query = $job_open_query->select(\DB::raw("COUNT(job_associate_candidates.candidate_id) as count"),'job_openings.id','job_openings.job_id','client_basicinfo.name as company_name','job_openings.no_of_positions','job_openings.posting_title','job_openings.city','job_openings.state','job_openings.country','job_openings.qualifications','job_openings.salary_from','job_openings.salary_to','job_openings.lacs_from','job_openings.thousand_from','job_openings.lacs_to','job_openings.thousand_to','industry.name as industry_name','job_openings.desired_candidate','job_openings.date_opened','job_openings.target_date','users.name as am_name','client_basicinfo.coordinator_name as coordinator_name','job_openings.priority','job_openings.hiring_manager_id','client_basicinfo.display_name','job_openings.created_at','job_openings.updated_at as updated_at','client_basicinfo.second_line_am as second_line_am','job_openings.remote_working as remote_working');
        
        $job_open_query = $job_open_query->leftJoin('job_associate_candidates','job_openings.id','=','job_associate_candidates.job_id');
        $job_open_query = $job_open_query->join('client_basicinfo','client_basicinfo.id','=','job_openings.client_id');
        $job_open_query = $job_open_query->join('users','users.id','=','job_openings.hiring_manager_id');

        $job_open_query = $job_open_query->leftJoin('industry','industry.id','=','job_openings.industry_id');

        if ($data_source) {
            $job_open_query = $job_open_query->where('job_openings.data_source', $data_source);
        }
        

        // assign jobs to logged in user
        if($all==0) {
            $job_open_query = $job_open_query->join('job_visible_users','job_visible_users.job_id','=','job_openings.id');
            $job_open_query = $job_open_query->where('job_visible_users.user_id','=',$user_id);
        }

        // Master Search Condidtions
        if(isset($mb_name) && $mb_name != '') {

            $job_open_query = $job_open_query->where('job_openings.hiring_manager_id','=',$mb_name);
        }
        else if(isset($company_name) && $company_name != '') {

            $job_open_query = $job_open_query->where('client_basicinfo.name','like',"%$company_name%");
        }
        else if(isset($posting_title) && $posting_title != '') {

            $job_open_query = $job_open_query->where('job_openings.posting_title','like',"%$posting_title%");
        }
        else if(isset($location) && $location != '') {

            if($location == 'Remote') {
                $job_open_query = $job_open_query->where('job_openings.remote_working','=',"1");
            }
            else {

                $job_open_query = $job_open_query->where('job_openings.city','=',"$location");
                $job_open_query = $job_open_query->where('job_openings.remote_working','!=',"1");
            }
        }
        else if (isset($min_ctc) && $min_ctc != '') {

            if($min_ctc == '30') {

                $job_open_query = $job_open_query->where('job_openings.lacs_from','>=',30);
            }
            else if($min_ctc == '0.5') {

                $job_open_query = $job_open_query->where('job_openings.lacs_from','=',0);
            }
            else {
                $job_open_query = $job_open_query->where('job_openings.lacs_from','=',$min_ctc);
            }
        }
        else if (isset($max_ctc) && $max_ctc != '') {

            if($max_ctc == '30') {
                $job_open_query = $job_open_query->where('job_openings.lacs_to','>=',30);
            }
            else if($max_ctc == '0.5') {

                $job_open_query = $job_open_query->where('job_openings.lacs_to','=',0);
            }
            else {
                $job_open_query = $job_open_query->where('job_openings.lacs_to','=',$max_ctc);
            }
        }
        else if(isset($added_date) && $added_date != NULL) {

            $f_dt = date("Y-m-d 00:00:00",strtotime($added_date));
            $t_dt = date("Y-m-d 23:59:59",strtotime($added_date));

            $job_open_query = $job_open_query->where('job_openings.created_at','>=',"$f_dt");
            $job_open_query = $job_open_query->where('job_openings.created_at','<=',"$t_dt");
        }
        else if(isset($no_of_positions) && $no_of_positions != '') {

            $job_open_query = $job_open_query->where('job_openings.no_of_positions','=',$no_of_positions);
        }
        else if (isset($client_heirarchy) && $client_heirarchy > 0) {
            $job_open_query = $job_open_query->where('job_openings.level_id','=',$client_heirarchy);
        }

        $job_open_query = $job_open_query->where('job_associate_candidates.deleted_at',NULL);
        $job_open_query = $job_open_query->whereNotIn('job_openings.priority',$job_status);
        $job_open_query = $job_open_query->groupBy('job_openings.id');

        if (isset($order) && $order != '') {
            
            if ($order == 'job_openings.lacs_from') {
                $job_open_query = $job_open_query->orderBy($order,$type);
                $job_open_query = $job_open_query->orderBy('job_openings.thousand_from',$type);
            }
            else if ($order == 'job_openings.lacs_to') {
                $job_open_query = $job_open_query->orderBy($order,$type);
                $job_open_query = $job_open_query->orderBy('job_openings.thousand_to',$type);
            }
            else{
                $job_open_query = $job_open_query->orderBy($order,$type);
            }
        }

        // Get data by financial year
        if (isset($current_year) && $current_year != NULL) {
            $job_open_query = $job_open_query->where('job_openings.created_at','>=',$current_year);
        }
        if (isset($next_year) && $next_year != NULL) {
            $job_open_query = $job_open_query->where('job_openings.created_at','<=',$next_year);
        }
        if (isset($limit) && $limit > 0) {
            $job_open_query = $job_open_query->limit($limit);
        }
        if (isset($offset) && $offset > 0) {
            $job_open_query = $job_open_query->offset($offset);
        }

        if (isset($search) && $search != '') {

            $job_open_query = $job_open_query->where(function($job_open_query) use ($search) {

                $date_search = false;
                $date_array = explode("-",$search);
                if(isset($date_array) && sizeof($date_array)>0){
                    $stamp = strtotime($search);
                    if (is_numeric($stamp)){
                        $month = date( 'm', $stamp );
                        $day   = date( 'd', $stamp );
                        $year  = date( 'Y', $stamp );

                        if(checkdate($month, $day, $year)){
                            $date_search = true;
                        }
                    }
                }
                $job_open_query = $job_open_query->where('job_openings.posting_title','like',"%$search%");
                $job_open_query = $job_open_query->orwhere('users.name','like',"%$search%");
                $job_open_query = $job_open_query->orwhere('client_basicinfo.display_name','like',"%$search%");
                $job_open_query = $job_open_query->orwhere('client_basicinfo.coordinator_name','like',"%$search%");
                $job_open_query = $job_open_query->orwhere('job_openings.no_of_positions','like',"%$search%");
                $job_open_query = $job_open_query->orwhere('job_openings.city','like',"%$search%");

                if(($search == 'Remote') || ($search == 'remote')) {
                    
                    $job_open_query = $job_open_query->orwhere('job_openings.remote_working','=',"1");
                }

                if($date_search) {

                    $dateClass = new Date();
                    $search_string = $dateClass->changeDMYtoYMD($search);
                    $from_date = date("Y-m-d 00:00:00",strtotime($search_string));
                    $to_date = date("Y-m-d 23:59:59",strtotime($search_string));
                    $job_open_query = $job_open_query->orwhere('job_openings.created_at','>=',"$from_date");
                    $job_open_query = $job_open_query->Where('job_openings.created_at','<=',"$to_date");
                }
            });
        }

        $job_response = $job_open_query->get();

        $jobs_list = array();
        $colors = self::getJobPrioritiesColor();
        $i = 0;

        foreach ($job_response as $key => $value) {

            // value get in 2 decimal point
            if ($value->lacs_from >= '100') {
                $min_ctc = '100+';
            }
            else {
                $lacs_from = $value->lacs_from*100000;
                $thousand_from = $value->thousand_from*1000;
                $mictc = $lacs_from+$thousand_from;
                $minctc = $mictc/100000;
                $min_ctc = number_format($minctc,2);
            }

            if ($value->lacs_to >= '100') {
                $max_ctc = '100+';
            }
            else {
                $lacs_to = $value->lacs_to*100000;
                $thousand_to = $value->thousand_to*1000;
                $mactc = $lacs_to+$thousand_to;
                $maxctc = $mactc/100000;
                $max_ctc = number_format($maxctc,2);
            }
            
            $jobs_list[$i]['id'] = $value->id;
            $jobs_list[$i]['job_id'] = $value->job_id;
            $jobs_list[$i]['company_name'] = $value->company_name;
            $jobs_list[$i]['display_name'] = $value->display_name;
            $jobs_list[$i]['client'] = $value->company_name." - ".$value->coordinator_name;
            $jobs_list[$i]['no_of_positions'] = $value->no_of_positions;

            if (isset($value->level_name) && $value->level_name != '') {
                $jobs_list[$i]['posting_title'] = $value->level_name." - ".$value->posting_title;
            }
            else {
                $jobs_list[$i]['posting_title'] = $value->posting_title;
            }
            
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
            $jobs_list[$i]['hiring_manager_id'] = $value->hiring_manager_id;
            $jobs_list[$i]['associate_candidate_cnt'] = JobAssociateCandidates::getJobAssociatedCvsCount($value->id);
            $jobs_list[$i]['priority'] = $value->priority;
            $jobs_list[$i]['created_date'] = date('d-m-Y',strtotime($value->created_at));
            $jobs_list[$i]['updated_date'] = date('d-m-Y',strtotime($value->updated_at));

            if(isset($value->priority) && $value->priority!='')
                $jobs_list[$i]['color'] = $colors[$value->priority];
            else
                $jobs_list[$i]['color'] ='';

            if($all==1) {
                $jobs_list[$i]['access'] = '1';
                $jobs_list[$i]['coordinator_name'] = $value->coordinator_name;
            }
            else {
                if(isset($value->hiring_manager_id) && $value->hiring_manager_id == $user_id) {
                    $jobs_list[$i]['coordinator_name'] = $value->coordinator_name;
                    $jobs_list[$i]['access'] = '1';
                }
                else if(isset($value->second_line_am) && $value->second_line_am == $user_id) {
                    $jobs_list[$i]['coordinator_name'] = $value->coordinator_name;
                    $jobs_list[$i]['access'] = '1';
                }
                else{
                    $jobs_list[$i]['coordinator_name'] = '';
                    $jobs_list[$i]['access'] = '0';
                }
            }
            $jobs_list[$i]['level_name'] = $value->level_name;

            if($value->hiring_manager_id == 0) {

                $jobs_list[$i]['am_name'] = 'Yet to Assign';
            }
            else {

                $jobs_list[$i]['am_name'] = $value->am_name;
            }

            if($value->remote_working == '1') {

                $jobs_list[$i]['city'] = "Remote";
            }
            else {

                $jobs_list[$i]['city'] = $value->city;
            } 

            $i++;
        }
        return $jobs_list;
    }
    
    public static function getAllJobsByCLient($client_id,$limit=0,$offset=0,$search=0,$order=NULL,$type='desc',$client_heirarchy=0,$mb_name='',$company_name='',$posting_title='',$location='',$min_ctc='',$max_ctc='',$added_date='',$no_of_positions='') {

        $job_onhold = getenv('ONHOLD');
        $job_client = getenv('CLOSEDBYCLIENT');
        $job_us = getenv('CLOSEDBYUS');
        $job_status = array($job_onhold,$job_us,$job_client);

        $job_open_query = JobOpen::query();
        $job_open_query = $job_open_query->select(\DB::raw("COUNT(job_associate_candidates.candidate_id) as count"),'job_openings.id','job_openings.job_id','client_basicinfo.name as company_name','job_openings.no_of_positions','job_openings.posting_title','job_openings.city','job_openings.state','job_openings.country','job_openings.qualifications','job_openings.salary_from','job_openings.salary_to','job_openings.lacs_from','job_openings.thousand_from','job_openings.lacs_to','job_openings.thousand_to','industry.name as industry_name','job_openings.desired_candidate','job_openings.date_opened','job_openings.target_date','users.name as am_name','client_basicinfo.coordinator_name as coordinator_name','job_openings.priority','job_openings.hiring_manager_id','client_basicinfo.display_name','job_openings.created_at','job_openings.remote_working as remote_working');

        $job_open_query = $job_open_query->leftJoin('job_associate_candidates','job_openings.id','=','job_associate_candidates.job_id');
        $job_open_query = $job_open_query->join('client_basicinfo','client_basicinfo.id','=','job_openings.client_id');
        $job_open_query = $job_open_query->join('users','users.id','=','job_openings.hiring_manager_id');

        $job_open_query = $job_open_query->leftJoin('industry','industry.id','=','job_openings.industry_id');
        $job_open_query = $job_open_query->whereNotIn('priority',$job_status);

        
        // Master Search Condidtions
        if(isset($mb_name) && $mb_name != '') {

            $job_open_query = $job_open_query->where('job_openings.hiring_manager_id','=',$mb_name);
        }
        else if(isset($company_name) && $company_name != '') {

            $job_open_query = $job_open_query->where('client_basicinfo.name','like',"%$company_name%");
        }
        else if(isset($posting_title) && $posting_title != '') {

            $job_open_query = $job_open_query->where('job_openings.posting_title','like',"%$posting_title%");
        }
        else if(isset($location) && $location != '') {

            if($location == 'Remote') {

                $job_open_query = $job_open_query->where('job_openings.remote_working','=',"1");
            }
            else {

                $job_open_query = $job_open_query->where('job_openings.city','=',"$location");
                $job_open_query = $job_open_query->where('job_openings.remote_working','!=',"1");
            }
        }
        else if (isset($min_ctc) && $min_ctc != '') {

            if($min_ctc == '30') {

                $job_open_query = $job_open_query->where('job_openings.lacs_from','>=',30);
            }
            else if($min_ctc == '0.5') {

                $job_open_query = $job_open_query->where('job_openings.lacs_from','=',0);
            }
            else {
                $job_open_query = $job_open_query->where('job_openings.lacs_from','=',$min_ctc);
            }
        }
        else if (isset($max_ctc) && $max_ctc != '') {

            if($max_ctc == '30') {
                $job_open_query = $job_open_query->where('job_openings.lacs_to','>=',30);
            }
            else if($max_ctc == '0.5') {

                $job_open_query = $job_open_query->where('job_openings.lacs_to','=',0);
            }
            else {
                $job_open_query = $job_open_query->where('job_openings.lacs_to','=',$max_ctc);
            }
        }
        else if(isset($added_date) && $added_date != '') {

            $f_dt = date("Y-m-d 00:00:00",strtotime($added_date));
            $t_dt = date("Y-m-d 23:59:59",strtotime($added_date));

            $job_open_query = $job_open_query->where('job_openings.created_at','>=',"$f_dt");
            $job_open_query = $job_open_query->where('job_openings.created_at','<=',"$t_dt");
        }
        else if(isset($no_of_positions) && $no_of_positions != '') {

            $job_open_query = $job_open_query->where('job_openings.no_of_positions','=',$no_of_positions);
        }
        else if (isset($client_heirarchy) && $client_heirarchy > 0) {
            $job_open_query = $job_open_query->where('job_openings.level_id','=',$client_heirarchy);
        }

        $job_open_query = $job_open_query->where('job_associate_candidates.deleted_at',NULL);
        $job_open_query = $job_open_query->where('job_openings.client_id',$client_id);
        $job_open_query = $job_open_query->groupBy('job_openings.id');

        if (isset($order) && $order != '') {
            if ($order == 'job_openings.lacs_from') {
                $job_open_query = $job_open_query->orderBy($order,$type);
                $job_open_query = $job_open_query->orderBy('job_openings.thousand_from',$type);
            }
            else if ($order == 'job_openings.lacs_to') {
                $job_open_query = $job_open_query->orderBy($order,$type);
                $job_open_query = $job_open_query->orderBy('job_openings.thousand_to',$type);
            }
            else{
                $job_open_query = $job_open_query->orderBy($order,$type);
            }
        }

        if (isset($limit) && $limit > 0) {
            $job_open_query = $job_open_query->limit($limit);
        }
        if (isset($offset) && $offset > 0) {
            $job_open_query = $job_open_query->offset($offset);
        }
        if (isset($search) && $search != '') {

            $job_open_query = $job_open_query->where(function($job_open_query) use ($search) {

                $job_open_query = $job_open_query->where('job_openings.posting_title','like',"%$search%");
                $job_open_query = $job_open_query->orwhere('users.name','like',"%$search%");
                $job_open_query = $job_open_query->orwhere('client_basicinfo.display_name','like',"%$search%");
                $job_open_query = $job_open_query->orwhere('client_basicinfo.coordinator_name','like',"%$search%");
                $job_open_query = $job_open_query->orwhere('job_openings.no_of_positions','like',"%$search%");
                $job_open_query = $job_open_query->orwhere('job_openings.city','like',"%$search%");
                    
                if(($search == 'Remote') || ($search == 'remote')) {
                    
                    $job_open_query = $job_open_query->orwhere('job_openings.remote_working','=',"1");
                }
            });
        }
        
        $job_response = $job_open_query->get();

        $jobs_list = array();
        $colors = self::getJobPrioritiesColor();
        $i = 0;
        foreach ($job_response as $key => $value) {

            // value get in 2 decimal point
            if ($value->lacs_from >= '100') {
                $min_ctc = '100+';
            }
            else {
                $lacs_from = $value->lacs_from*100000;
                $thousand_from = $value->thousand_from*1000;
                $mictc = $lacs_from+$thousand_from;
                $minctc = $mictc/100000;
                $min_ctc = number_format($minctc,2);
            }

            if ($value->lacs_to >= '100') {
                $max_ctc = '100+';
            }
            else {
                $lacs_to = $value->lacs_to*100000;
                $thousand_to = $value->thousand_to*1000;
                $mactc = $lacs_to+$thousand_to;
                $maxctc = $mactc/100000;
                $max_ctc = number_format($maxctc,2);
            }

            $jobs_list[$i]['id'] = $value->id;
            $jobs_list[$i]['job_id'] = $value->job_id;
            $jobs_list[$i]['company_name'] = $value->company_name;
            $jobs_list[$i]['display_name'] = $value->display_name;
            $jobs_list[$i]['client'] = $value->company_name." - ".$value->coordinator_name;
            $jobs_list[$i]['no_of_positions'] = $value->no_of_positions;

            if (isset($value->level_name) && $value->level_name != '') {
                $jobs_list[$i]['posting_title'] = $value->level_name." - ".$value->posting_title;
            }
            else {
                $jobs_list[$i]['posting_title'] = $value->posting_title;
            }

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
            $jobs_list[$i]['hiring_manager_id'] = $value->hiring_manager_id;
            $jobs_list[$i]['associate_candidate_cnt'] = $value->count;
            $jobs_list[$i]['priority'] = $value->priority;
            $jobs_list[$i]['created_date'] = date('d-m-Y',strtotime($value->created_at));
            $jobs_list[$i]['updated_date'] = date('d-m-Y',strtotime($value->updated_at));
            
            $jobs_list[$i]['coordinator_name'] = $value->coordinator_name;
            $jobs_list[$i]['access'] = '0';

            if($value->hiring_manager_id == 0) {

                $jobs_list[$i]['am_name'] = 'Yet to Assign';
            }
            else {

                $jobs_list[$i]['am_name'] = $value->am_name;
            }

            if($value->remote_working == '1') {

                $jobs_list[$i]['city'] = "Remote";
            }
            else {

                $jobs_list[$i]['city'] = $value->city;
            } 
            $i++;
        }
        return $jobs_list;
    }

    public static function getAllJobsCount($all=0,$user_id,$search,$current_year=NULL,$next_year=NULL,$client_heirarchy=0,$mb_name='',$company_name='',$posting_title='',$location='',$min_ctc='',$max_ctc='',$added_date=NULL,$no_of_positions='',$data_source = null) {

        $job_onhold = getenv('ONHOLD');
        $job_client = getenv('CLOSEDBYCLIENT');
        $job_us = getenv('CLOSEDBYUS');
        $job_status = array($job_onhold,$job_us,$job_client);

        $job_open_query = JobOpen::query();
        $job_open_query = $job_open_query->select(\DB::raw("COUNT(job_associate_candidates.candidate_id) as count"),'job_openings.id','job_openings.job_id','client_basicinfo.name as company_name','job_openings.no_of_positions','job_openings.posting_title','job_openings.city','job_openings.state','job_openings.country','job_openings.qualifications','job_openings.salary_from','job_openings.salary_to','job_openings.lacs_from','job_openings.thousand_from','job_openings.lacs_to','job_openings.thousand_to','industry.name as industry_name','job_openings.desired_candidate','job_openings.date_opened','job_openings.target_date','users.name as am_name','client_basicinfo.coordinator_name as coordinator_name','job_openings.priority','job_openings.hiring_manager_id','client_basicinfo.display_name','job_openings.created_at','job_openings.updated_at as updated_at','job_openings.remote_working as remote_working');

        $job_open_query = $job_open_query->leftJoin('job_associate_candidates','job_openings.id','=','job_associate_candidates.job_id');
        $job_open_query = $job_open_query->join('client_basicinfo','client_basicinfo.id','=','job_openings.client_id');
        $job_open_query = $job_open_query->join('users','users.id','=','job_openings.hiring_manager_id');
        $job_open_query = $job_open_query->leftJoin('industry','industry.id','=','job_openings.industry_id');



        // Add a condition to filter data_source if provided
        if ($data_source) {
            $job_open_query = $job_open_query->where('job_openings.data_source', $data_source);
        }
    
        // assign jobs to logged in user
        if($all==0) {
            $job_open_query = $job_open_query->join('job_visible_users','job_visible_users.job_id','=','job_openings.id');
            $job_open_query = $job_open_query->where('user_id','=',$user_id);
        }

        $job_open_query = $job_open_query->whereNotIn('job_openings.priority',$job_status);

        // Master Search Condidtions
        if(isset($mb_name) && $mb_name != '') {

            $job_open_query = $job_open_query->where('job_openings.hiring_manager_id','=',$mb_name);
        }
        else if(isset($company_name) && $company_name != '') {

            $job_open_query = $job_open_query->where('client_basicinfo.name','like',"%$company_name%");
        }
        else if(isset($posting_title) && $posting_title != '') {

            $job_open_query = $job_open_query->where('job_openings.posting_title','like',"%$posting_title%");
        }
        else if(isset($location) && $location != '') {

            if($location == 'Remote') {

                $job_open_query = $job_open_query->where('job_openings.remote_working','=',"1");
            }
            else {

                $job_open_query = $job_open_query->where('job_openings.city','=',"$location");
                $job_open_query = $job_open_query->where('job_openings.remote_working','!=',"1");
            }
        }
        else if (isset($min_ctc) && $min_ctc != '') {

            if($min_ctc == '30') {

                $job_open_query = $job_open_query->where('job_openings.lacs_from','>=',30);
            }
            else if($min_ctc == '0.5') {

                $job_open_query = $job_open_query->where('job_openings.lacs_from','=',0);
            }
            else {
                $job_open_query = $job_open_query->where('job_openings.lacs_from','=',$min_ctc);
            }
        }
        else if (isset($max_ctc) && $max_ctc != '') {

            if($max_ctc == '30') {
                $job_open_query = $job_open_query->where('job_openings.lacs_to','>=',30);
            }
            else if($max_ctc == '0.5') {

                $job_open_query = $job_open_query->where('job_openings.lacs_to','=',0);
            }
            else {
                $job_open_query = $job_open_query->where('job_openings.lacs_to','=',$max_ctc);
            }
        }
        else if(isset($added_date) && $added_date != NULL) {

            $f_dt = date("Y-m-d 00:00:00",strtotime($added_date));
            $t_dt = date("Y-m-d 23:59:59",strtotime($added_date));

            $job_open_query = $job_open_query->where('job_openings.created_at','>=',"$f_dt");
            $job_open_query = $job_open_query->where('job_openings.created_at','<=',"$t_dt");
        }
        else if(isset($no_of_positions) && $no_of_positions != '') {

            $job_open_query = $job_open_query->where('job_openings.no_of_positions','=',$no_of_positions);
        }
        else if (isset($client_heirarchy) && $client_heirarchy > 0) {
            $job_open_query = $job_open_query->where('job_openings.level_id','=',$client_heirarchy);
        }

        $job_open_query = $job_open_query->where('job_associate_candidates.deleted_at',NULL);
        $job_open_query = $job_open_query->groupBy('job_openings.id');

        // Get data by financial year
        if (isset($current_year) && $current_year != NULL) {
            $job_open_query = $job_open_query->where('job_openings.created_at','>=',$current_year);
        }
        if (isset($next_year) && $next_year != NULL) {
            $job_open_query = $job_open_query->where('job_openings.created_at','<=',$next_year);
        }

        if (isset($search) && $search != '') {

            $job_open_query = $job_open_query->where(function($job_open_query) use ($search) {

                $date_search = false;
                $date_array = explode("-",$search);
                if(isset($date_array) && sizeof($date_array)>0) {

                    $stamp = strtotime($search);
                    if (is_numeric($stamp)){
                        $month = date( 'm', $stamp );
                        $day   = date( 'd', $stamp );
                        $year  = date( 'Y', $stamp );

                        if(checkdate($month, $day, $year)){
                            $date_search = true;
                        }
                    }
                }

                $job_open_query = $job_open_query->where('job_openings.posting_title','like',"%$search%");
                $job_open_query = $job_open_query->orwhere('users.name','like',"%$search%");
                $job_open_query = $job_open_query->orwhere('client_basicinfo.display_name','like',"%$search%");
                $job_open_query = $job_open_query->orwhere('client_basicinfo.coordinator_name','like',"%$search%");
                $job_open_query = $job_open_query->orwhere('job_openings.no_of_positions','like',"%$search%");
                $job_open_query = $job_open_query->orwhere('job_openings.city','like',"%$search%");

                if(($search == 'Remote') || ($search == 'remote')) {
                    
                    $job_open_query = $job_open_query->orwhere('job_openings.remote_working','=',"1");
                }

                if($date_search) {

                    $dateClass = new Date();
                    $search_string = $dateClass->changeDMYtoYMD($search);
                    $from_date = date("Y-m-d 00:00:00",strtotime($search_string));
                    $to_date = date("Y-m-d 23:59:59",strtotime($search_string));
                    $job_open_query = $job_open_query->orwhere('job_openings.created_at','>=',"$from_date");
                    $job_open_query = $job_open_query->Where('job_openings.created_at','<=',"$to_date");
                }
            });
        }

        $job_response = $job_open_query->get();

        return sizeof($job_response);
    }

    public static function getAllJobsNoofPositions($all=0,$user_id,$search,$current_year=NULL,$next_year=NULL,$client_heirarchy=0,$mb_name='',$company_name='',$posting_title='',$location='',$min_ctc='',$max_ctc='',$added_date=NULL,$no_of_positions='') {

        $job_onhold = getenv('ONHOLD');
        $job_client = getenv('CLOSEDBYCLIENT');
        $job_us = getenv('CLOSEDBYUS');
        $job_status = array($job_onhold,$job_us,$job_client);

        $job_open_query = JobOpen::query();
        $job_open_query = $job_open_query->select(\DB::raw("SUM(job_openings.no_of_positions) as no_of_positions"));

        // assign jobs to logged in user
        if($all==0) {
            $job_open_query = $job_open_query->join('job_visible_users','job_visible_users.job_id','=','job_openings.id');
            $job_open_query = $job_open_query->where('user_id','=',$user_id);
        }

        $job_open_query = $job_open_query->whereNotIn('job_openings.priority',$job_status);

        if (isset($client_heirarchy) && $client_heirarchy > 0) {
            $job_open_query = $job_open_query->where('job_openings.level_id','=',$client_heirarchy);
        }

        $job_response = $job_open_query->first();
        
        return $job_response['no_of_positions'];
    }

    public static function getJobById($job_id) {

        $job_query = JobOpen::query();

        $job_query = $job_query->join('client_basicinfo','client_basicinfo.id','=','job_openings.client_id');
        $job_query = $job_query->leftjoin('interview', 'interview.posting_title','=', 'job_openings.id');
        $job_query = $job_query->leftjoin('users','users.id','=','job_openings.hiring_manager_id');
        $job_query = $job_query->select('job_openings.*','client_basicinfo.name as client_name','client_basicinfo.description as client_desc', 'client_basicinfo.website as website','interview.interview_date as date', 'interview.location as interview_location','interview.type as interview_type','client_basicinfo.coordinator_name as contact_person','users.name as user_name','interview.skype_id as skype_id','interview.candidate_location as candidate_location');

        $job_query = $job_query->where('job_openings.id', '=', $job_id);
        $job_response = $job_query->get();

        $response = array();
        foreach ($job_response as $k => $v) {
            
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

            if($v->remote_working == '1') {

                $response['job_location'] = "Remote";
                $response['city'] = "Remote";
            }
            else {

                $response['job_location'] = $location;
                $response['city'] = $v->city;
            }

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
            $response['candidate_location'] = $v->candidate_location;

            if (isset($v->level_name) && $v->level_name != '') {
                $response['new_posting_title'] = $v->level_name." - ".$v->posting_title;
            }
            else {
                $response['new_posting_title'] = $v->posting_title;
            }
        }
        return $response;
    }

    public static function getJobforOpentoAll() {

        $date = date('Y-m-d', strtotime('-5 days'));
        $date_time = date('Y-m-d H');
        //print_r($date);exit;
        $job_onhold = getenv('ONHOLD');
        $job_client = getenv('CLOSEDBYCLIENT');
        $job_us = getenv('CLOSEDBYUS');
        $job_status = array($job_onhold,$job_us,$job_client);

        $job = JobOpen::query();
        $job = $job->select('job_openings.id','job_openings.created_at','job_openings.hiring_manager_id');
        $job = $job->where('job_openings.open_to_all_date','>=',"$date");
        $job = $job->where('job_openings.open_to_all_date','<=',"$date_time");
        $job = $job->whereNotIn('priority',$job_status);
        $job = $job->where('open_to_all','=','0');
        $job = $job->where('job_open_checkbox','=','0');
        $job_res = $job->get();

        $job_data = array();
        $i = 0;
        foreach ($job_res as $key => $value) {
            
            $job_data[$i]['id'] = $value->id;
            $job_data[$i]['hiring_manager_id'] = $value->hiring_manager_id;
            $job_data[$i]['created_at'] = $value->created_at;
            $i++;
        }
        return $job_res;
    }

    public static function getOpenToAllJobs($limit=0,$user_id,$department_id=0) {

        $job_onhold = getenv('ONHOLD');
        $job_client = getenv('CLOSEDBYCLIENT');
        $job_us = getenv('CLOSEDBYUS');
        //$job_status = array($job_onhold,$job_us,$job_client);
        $job_status = array($job_onhold,$job_us,$job_client,7,8);

        $job_open_query = JobOpen::query();
        $job_open_query = $job_open_query->select(\DB::raw("COUNT(job_associate_candidates.candidate_id) as count"),'job_openings.id','job_openings.job_id','client_basicinfo.name as company_name','job_openings.no_of_positions','job_openings.posting_title','job_openings.city','job_openings.state','job_openings.country','job_openings.qualifications','job_openings.salary_from','job_openings.salary_to','job_openings.lacs_from','job_openings.thousand_from','job_openings.lacs_to','job_openings.thousand_to','industry.name as industry_name','job_openings.desired_candidate','job_openings.date_opened','job_openings.target_date','users.name as am_name','client_basicinfo.coordinator_name as coordinator_name','job_openings.priority','job_openings.hiring_manager_id','client_basicinfo.display_name','job_openings.created_at','job_openings.open_to_all as open_to_all','job_openings.open_to_all_date as open_to_all_date','client_basicinfo.second_line_am as second_line_am','job_openings.remote_working as remote_working');

        $job_open_query = $job_open_query->leftJoin('job_associate_candidates','job_openings.id','=','job_associate_candidates.job_id');
        $job_open_query = $job_open_query->join('client_basicinfo','client_basicinfo.id','=','job_openings.client_id');
        $job_open_query = $job_open_query->join('users','users.id','=','job_openings.hiring_manager_id');
        $job_open_query = $job_open_query->leftJoin('industry','industry.id','=','job_openings.industry_id');

        // assign jobs to logged in user
        if($user_id > 0) {
            $job_open_query = $job_open_query->join('job_visible_users','job_visible_users.job_id','=','job_openings.id');
            $job_open_query = $job_open_query->where('user_id','=',$user_id);
        }

        // For Department
        if (isset($department_id) && $department_id > 0) {
            $job_open_query = $job_open_query->where('client_basicinfo.department_id','=',$department_id);
        }

        $job_open_query = $job_open_query->whereNotIn('priority',$job_status);
        $job_open_query = $job_open_query->where('job_associate_candidates.deleted_at',NULL);
        $job_open_query = $job_open_query->groupBy('job_openings.id');
        $job_open_query = $job_open_query->where('open_to_all','=','1');
        $job_open_query = $job_open_query->having('count','<','5');
        $job_open_query = $job_open_query->orderBy('job_openings.id','desc');

        if (isset($limit) && $limit > 0) {
            $job_open_query = $job_open_query->limit($limit);
        }
        $job_response = $job_open_query->get();

        $jobs_open_list = array();
        $colors = self::getJobPrioritiesColor();
        $i = 0;

        foreach ($job_response as $key => $value) {

            // value get in 2 decimal point
            if ($value->lacs_from >= '100') {
                $min_ctc = '100+';
            }
            else {
                $lacs_from = $value->lacs_from*100000;
                $thousand_from = $value->thousand_from*1000;
                $mictc = $lacs_from+$thousand_from;
                $minctc = $mictc/100000;
                $min_ctc = number_format($minctc,2);
            }

            if ($value->lacs_to >= '100') {
                $max_ctc = '100+';
            }
            else {
                $lacs_to = $value->lacs_to*100000;
                $thousand_to = $value->thousand_to*1000;
                $mactc = $lacs_to+$thousand_to;
                $maxctc = $mactc/100000;
                $max_ctc = number_format($maxctc,2);
            }
            
            $id = \Crypt::encrypt($value->id);
            $jobs_open_list[$i]['id'] = $id;
            $jobs_open_list[$i]['job_id'] = $value->job_id;
            $jobs_open_list[$i]['company_name'] = $value->company_name;
            $jobs_open_list[$i]['display_name'] = $value->display_name;
            $jobs_open_list[$i]['client'] = $value->company_name." - ".$value->coordinator_name;
            $jobs_open_list[$i]['no_of_positions'] = $value->no_of_positions;

            if (isset($value->level_name) && $value->level_name != '') {
                $jobs_open_list[$i]['posting_title'] = $value->level_name." - ".$value->posting_title;
            }
            else {
                $jobs_open_list[$i]['posting_title'] = $value->posting_title;
            }

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
            $jobs_open_list[$i]['hiring_manager_id'] = $value->hiring_manager_id;
            $jobs_open_list[$i]['associate_candidate_cnt'] = $value->count;
            $jobs_open_list[$i]['priority'] = $value->priority;
            $jobs_open_list[$i]['created_date'] = date('d-m-Y',strtotime($value->created_at));

            if(isset($value->priority) && $value->priority!='')
                $jobs_open_list[$i]['color'] = $colors[$value->priority];
            else
                $jobs_open_list[$i]['color'] ='';

            if(isset($value->hiring_manager_id) && $value->hiring_manager_id == $user_id ) {
                $jobs_open_list[$i]['coordinator_name'] = $value->coordinator_name;
                $jobs_open_list[$i]['access'] = '1';
            }
            else if(isset($value->second_line_am) && $value->second_line_am == $user_id) {
                $jobs_open_list[$i]['coordinator_name'] = $value->coordinator_name;
                $jobs_open_list[$i]['access'] = '1';
            }
            else {
                $jobs_open_list[$i]['coordinator_name'] = '';
                $jobs_open_list[$i]['access'] = '0';
            }
            
            $jobs_open_list[$i]['open_to_all'] = $value->open_to_all;
            $jobs_open_list[$i]['open_to_all_date'] = $value->open_to_all_date;

            if($value->hiring_manager_id == 0) {

                $jobs_open_list[$i]['am_name'] = 'Yet to Assign';
            }
            else {

                $jobs_open_list[$i]['am_name'] = $value->am_name;
            }

            if($value->remote_working == '1') {

                $jobs_open_list[$i]['city'] = "Remote";
            }
            else {

                $jobs_open_list[$i]['city'] = $value->city;
            }
            
            $i++;
        }
        return $jobs_open_list;
    }

    public static function getPriorityWiseJobs($all=0,$user_id,$priority,$current_year=NULL,$next_year=NULL,$client_heirarchy=0,$department_id=0,$mb_name='',$company_name='',$posting_title='',$location='',$min_ctc='',$max_ctc='',$added_date=NULL,$no_of_positions='',$limit=0,$offset=0,$search='',$order_column_name='',$type='') {

        $job_open_query = JobOpen::query();
        $job_open_query = $job_open_query->select(\DB::raw("COUNT(job_associate_candidates.candidate_id) as count"),'job_openings.id','job_openings.job_id','client_basicinfo.name as company_name','job_openings.no_of_positions','job_openings.posting_title','job_openings.city','job_openings.state','job_openings.country','job_openings.qualifications','job_openings.salary_from','job_openings.salary_to','job_openings.lacs_from','job_openings.thousand_from','job_openings.lacs_to','job_openings.thousand_to','industry.name as industry_name','job_openings.desired_candidate','job_openings.date_opened','job_openings.target_date','users.name as am_name','client_basicinfo.coordinator_name as coordinator_name','job_openings.priority','job_openings.hiring_manager_id','client_basicinfo.display_name','job_openings.created_at','job_openings.updated_at','client_basicinfo.second_line_am as second_line_am','job_openings.remote_working as remote_working','job_openings.priority_by','job_openings.priority_date');

        $job_open_query = $job_open_query->leftJoin('job_associate_candidates','job_openings.id','=','job_associate_candidates.job_id');
        $job_open_query = $job_open_query->join('client_basicinfo','client_basicinfo.id','=','job_openings.client_id');
        $job_open_query = $job_open_query->leftJoin('users','users.id','=','job_openings.hiring_manager_id');
        $job_open_query = $job_open_query->leftJoin('industry','industry.id','=','job_openings.industry_id');

        // assign jobs to logged in user
        if($all==0) {
            $job_open_query = $job_open_query->join('job_visible_users','job_visible_users.job_id','=','job_openings.id');
            $job_open_query = $job_open_query->where('user_id','=',$user_id);
        }

        // For Department
        if (isset($department_id) && $department_id > 0) {
            $job_open_query = $job_open_query->where('client_basicinfo.department_id','=',$department_id);
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
        else if ($priority == 'Constant Deliveries needed for very old positions where many deliveries are done but no result yet') {
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

        // Master Search Condidtions
        if(isset($mb_name) && $mb_name != '') {

            $job_open_query = $job_open_query->where('job_openings.hiring_manager_id','=',$mb_name);
        }
        else if(isset($company_name) && $company_name != '') {

            $job_open_query = $job_open_query->where('client_basicinfo.name','like',"%$company_name%");
        }
        else if(isset($posting_title) && $posting_title != '') {

            $job_open_query = $job_open_query->where('job_openings.posting_title','like',"%$posting_title%");
        }
        else if(isset($location) && $location != '') {

            if($location == 'Remote') {

                $job_open_query = $job_open_query->where('job_openings.remote_working','=',"1");
            }
            else {

                $job_open_query = $job_open_query->where('job_openings.city','=',"$location");
                $job_open_query = $job_open_query->where('job_openings.remote_working','!=',"1");
            }
        }
        else if (isset($min_ctc) && $min_ctc != '') {

            if($min_ctc == '30') {

                $job_open_query = $job_open_query->where('job_openings.lacs_from','>=',30);
            }
            else if($min_ctc == '0.5') {

                $job_open_query = $job_open_query->where('job_openings.lacs_from','=',0);
            }
            else {
                $job_open_query = $job_open_query->where('job_openings.lacs_from','=',$min_ctc);
            }
        }
        else if (isset($max_ctc) && $max_ctc != '') {

            if($max_ctc == '30') {
                $job_open_query = $job_open_query->where('job_openings.lacs_to','>=',30);
            }
            else if($max_ctc == '0.5') {

                $job_open_query = $job_open_query->where('job_openings.lacs_to','=',0);
            }
            else {
                $job_open_query = $job_open_query->where('job_openings.lacs_to','=',$max_ctc);
            }
        }
        else if(isset($added_date) && $added_date != NULL) {

            $f_dt = date("Y-m-d 00:00:00",strtotime($added_date));
            $t_dt = date("Y-m-d 23:59:59",strtotime($added_date));

            $job_open_query = $job_open_query->where('job_openings.created_at','>=',"$f_dt");
            $job_open_query = $job_open_query->where('job_openings.created_at','<=',"$t_dt");
        }
        else if(isset($no_of_positions) && $no_of_positions != '') {

            $job_open_query = $job_open_query->where('job_openings.no_of_positions','=',$no_of_positions);
        }
        else if (isset($client_heirarchy) && $client_heirarchy > 0) {
            $job_open_query = $job_open_query->where('job_openings.level_id','=',$client_heirarchy);
        }
        
        $job_open_query = $job_open_query->where('job_associate_candidates.deleted_at',NULL);
        $job_open_query = $job_open_query->groupBy('job_openings.id');

        // Get data by financial year
        if (isset($current_year) && $current_year != NULL) {
            $job_open_query = $job_open_query->where('job_openings.created_at','>=',$current_year);
        }
        if (isset($next_year) && $next_year != NULL) {
            $job_open_query = $job_open_query->where('job_openings.created_at','<=',$next_year);
        }

        if (isset($order_column_name) && $order_column_name != '') {
            $job_open_query = $job_open_query->orderBy($order_column_name,$type);
        } else {
            $job_open_query = $job_open_query->orderBy('job_openings.updated_at','desc');
        }
        if (isset($limit) && $limit > 0) {
            $job_open_query = $job_open_query->limit($limit);
        }
        if (isset($offset) && $offset > 0) {
            $job_open_query = $job_open_query->offset($offset);
        }
        if (isset($search) && $search != '') {
            $job_open_query = $job_open_query->where(function($job_open_query) use ($search) {
                $job_open_query = $job_open_query->where('job_openings.posting_title','like',"%$search%");
                $job_open_query = $job_open_query->orwhere('job_openings.city','like',"%$search%");
                if(($search == 'Remote') || ($search == 'remote')) {
                    $job_open_query = $job_open_query->orwhere('job_openings.remote_working','=',"1");
                }
            });
        }

        $job_response = $job_open_query->get();

        $jobs_list = array();
        $colors = self::getJobPrioritiesColor();
        $i = 0;

        foreach ($job_response as $key => $value) {

            // value get in 2 decimal point
            if ($value->lacs_from >= '100') {
                $min_ctc = '100+';
            }
            else {
                $lacs_from = $value->lacs_from*100000;
                $thousand_from = $value->thousand_from*1000;
                $mictc = $lacs_from+$thousand_from;
                $minctc = $mictc/100000;
                $min_ctc = number_format($minctc,2);
            }

            if ($value->lacs_to >= '100') {
                $max_ctc = '100+';
            }
            else {
                $lacs_to = $value->lacs_to*100000;
                $thousand_to = $value->thousand_to*1000;
                $mactc = $lacs_to+$thousand_to;
                $maxctc = $mactc/100000;
                $max_ctc = number_format($maxctc,2);
            }
        
            $jobs_list[$i]['id'] = $value->id;
            $jobs_list[$i]['job_id'] = $value->job_id;
            $jobs_list[$i]['company_name'] = $value->company_name;
            $jobs_list[$i]['display_name'] = $value->display_name;
            $jobs_list[$i]['client'] = $value->company_name." - ".$value->coordinator_name;
            $jobs_list[$i]['no_of_positions'] = $value->no_of_positions;

            if (isset($value->level_name) && $value->level_name != '') {
                $jobs_list[$i]['posting_title'] = $value->level_name." - ".$value->posting_title;
            }
            else {
                $jobs_list[$i]['posting_title'] = $value->posting_title;
            }

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
            $jobs_list[$i]['hiring_manager_id'] = $value->hiring_manager_id;
            $jobs_list[$i]['associate_candidate_cnt'] = $value->count;
            $jobs_list[$i]['priority'] = $value->priority;
            $jobs_list[$i]['priority_by'] = $value->priority_by;
            $jobs_list[$i]['created_date'] = date('d-m-Y',strtotime($value->created_at));
            $jobs_list[$i]['updated_date'] = date('d-m-Y',strtotime($value->updated_at));
            $jobs_list[$i]['priority_date'] = date('Y-m-d',strtotime($value->priority_date));

            if(isset($value->priority) && $value->priority!='') 
                $jobs_list[$i]['color'] = $colors[$value->priority];
            else
                $jobs_list[$i]['color'] ='';

            if($all==1) {

                $jobs_list[$i]['access'] = '1';
                $jobs_list[$i]['coordinator_name'] = $value->coordinator_name;
            }
            else {

                if(isset($value->hiring_manager_id) && $value->hiring_manager_id == $user_id ) {
                    $jobs_list[$i]['coordinator_name'] = $value->coordinator_name;
                    $jobs_list[$i]['access'] = '1';
                }
                else if(isset($value->second_line_am) && $value->second_line_am == $user_id) {
                    $jobs_list[$i]['coordinator_name'] = $value->coordinator_name;
                    $jobs_list[$i]['access'] = '1';
                }
                else {
                    $jobs_list[$i]['coordinator_name'] = '';
                    $jobs_list[$i]['access'] = '0';
                }
            }

            if($value->hiring_manager_id == 0) {

                $jobs_list[$i]['am_name'] = 'Yet to Assign';
            }
            else {

                $jobs_list[$i]['am_name'] = $value->am_name;
            }

            if($value->remote_working == '1') {

                $jobs_list[$i]['city'] = "Remote";
            }
            else {

                $jobs_list[$i]['city'] = $value->city;
            }

            $jobs_list[$i]['lacs_from'] = $value->lacs_from;
            $jobs_list[$i]['lacs_to'] = $value->lacs_to;

            $i++;
        }
        return $jobs_list;
    }

    public static function getPriorityWiseJobsCount($all=0,$user_id,$priority,$current_year=NULL,$next_year=NULL,$client_heirarchy=0,$department_id=0,$mb_name='',$company_name='',$posting_title='',$location='',$min_ctc='',$max_ctc='',$added_date=NULL,$no_of_positions='',$search='') {

        $job_open_query = JobOpen::query();
        $job_open_query = $job_open_query->select(\DB::raw("COUNT(job_associate_candidates.candidate_id) as count"),'job_openings.id','job_openings.job_id','client_basicinfo.name as company_name','job_openings.no_of_positions','job_openings.posting_title','job_openings.city','job_openings.state','job_openings.country','job_openings.qualifications','job_openings.salary_from','job_openings.salary_to','job_openings.lacs_from','job_openings.thousand_from','job_openings.lacs_to','job_openings.thousand_to','industry.name as industry_name','job_openings.desired_candidate','job_openings.date_opened','job_openings.target_date','users.name as am_name','client_basicinfo.coordinator_name as coordinator_name','job_openings.priority','job_openings.hiring_manager_id','client_basicinfo.display_name','job_openings.created_at','job_openings.updated_at','client_basicinfo.second_line_am as second_line_am','job_openings.remote_working as remote_working');

        $job_open_query = $job_open_query->leftJoin('job_associate_candidates','job_openings.id','=','job_associate_candidates.job_id');
        $job_open_query = $job_open_query->join('client_basicinfo','client_basicinfo.id','=','job_openings.client_id');
        $job_open_query = $job_open_query->leftJoin('users','users.id','=','job_openings.hiring_manager_id');
        $job_open_query = $job_open_query->leftJoin('industry','industry.id','=','job_openings.industry_id');

        // assign jobs to logged in user
        if($all==0) {
            $job_open_query = $job_open_query->join('job_visible_users','job_visible_users.job_id','=','job_openings.id');
            $job_open_query = $job_open_query->where('user_id','=',$user_id);
        }

        // For Department
        if (isset($department_id) && $department_id > 0) {
            $job_open_query = $job_open_query->where('client_basicinfo.department_id','=',$department_id);
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
        else if ($priority == 'Constant Deliveries needed for very old positions where many deliveries are done but no result yet') {
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

        // Master Search Condidtions
        if(isset($mb_name) && $mb_name != '') {

            $job_open_query = $job_open_query->where('job_openings.hiring_manager_id','=',$mb_name);
        }
        else if(isset($company_name) && $company_name != '') {

            $job_open_query = $job_open_query->where('client_basicinfo.name','like',"%$company_name%");
        }
        else if(isset($posting_title) && $posting_title != '') {

            $job_open_query = $job_open_query->where('job_openings.posting_title','like',"%$posting_title%");
        }
        else if(isset($location) && $location != '') {

            if($location == 'Remote') {

                $job_open_query = $job_open_query->where('job_openings.remote_working','=',"1");
            }
            else {

                $job_open_query = $job_open_query->where('job_openings.city','=',"$location");
                $job_open_query = $job_open_query->where('job_openings.remote_working','!=',"1");
            }
        }
        else if (isset($min_ctc) && $min_ctc != '') {

            if($min_ctc == '30') {

                $job_open_query = $job_open_query->where('job_openings.lacs_from','>=',30);
            }
            else if($min_ctc == '0.5') {

                $job_open_query = $job_open_query->where('job_openings.lacs_from','=',0);
            }
            else {
                $job_open_query = $job_open_query->where('job_openings.lacs_from','=',$min_ctc);
            }
        }
        else if (isset($max_ctc) && $max_ctc != '') {

            if($max_ctc == '30') {
                $job_open_query = $job_open_query->where('job_openings.lacs_to','>=',30);
            }
            else if($max_ctc == '0.5') {

                $job_open_query = $job_open_query->where('job_openings.lacs_to','=',0);
            }
            else {
                $job_open_query = $job_open_query->where('job_openings.lacs_to','=',$max_ctc);
            }
        }
        else if(isset($added_date) && $added_date != NULL) {

            $f_dt = date("Y-m-d 00:00:00",strtotime($added_date));
            $t_dt = date("Y-m-d 23:59:59",strtotime($added_date));

            $job_open_query = $job_open_query->where('job_openings.created_at','>=',"$f_dt");
            $job_open_query = $job_open_query->where('job_openings.created_at','<=',"$t_dt");
        }
        else if(isset($no_of_positions) && $no_of_positions != '') {

            $job_open_query = $job_open_query->where('job_openings.no_of_positions','=',$no_of_positions);
        }
        else if (isset($client_heirarchy) && $client_heirarchy > 0) {
            $job_open_query = $job_open_query->where('job_openings.level_id','=',$client_heirarchy);
        }
        
        $job_open_query = $job_open_query->where('job_associate_candidates.deleted_at',NULL);
        $job_open_query = $job_open_query->groupBy('job_openings.id');

        // Get data by financial year
        if (isset($current_year) && $current_year != NULL) {
            $job_open_query = $job_open_query->where('job_openings.created_at','>=',$current_year);
        }
        if (isset($next_year) && $next_year != NULL) {
            $job_open_query = $job_open_query->where('job_openings.created_at','<=',$next_year);
        }

        if (isset($search) && $search != '') {
            $job_open_query = $job_open_query->where(function($job_open_query) use ($search) {
                $job_open_query = $job_open_query->where('job_openings.posting_title','like',"%$search%");
                $job_open_query = $job_open_query->orwhere('job_openings.city','like',"%$search%");
                if(($search == 'Remote') || ($search == 'remote')) {
                    $job_open_query = $job_open_query->orwhere('job_openings.remote_working','=',"1");
                }
            });
        }
        $job_open_query = $job_open_query->orderBy('job_openings.updated_at','desc');
        $job_response = $job_open_query->get();
        $job_count = $job_response->count();

        return $job_count;
    }

    public static function getSalaryWiseJobsCount($all=0,$user_id,$salary,$current_year=NULL,$next_year=NULL,$priority=0,$client_heirarchy=0,$department_id=0,$mb_name='',$company_name='',$posting_title='',$location='',$min_ctc='',$max_ctc='',$added_date=NULL,$no_of_positions='',$search='') {

        $job_onhold = getenv('ONHOLD');
        $job_client = getenv('CLOSEDBYCLIENT');
        $job_us = getenv('CLOSEDBYUS');
        $job_status = array($job_onhold,$job_us,$job_client);

        $job_open_query = JobOpen::query();
        $job_open_query = $job_open_query->select(\DB::raw("COUNT(job_associate_candidates.candidate_id) as count"),'job_openings.id','job_openings.job_id','client_basicinfo.name as company_name','job_openings.no_of_positions','job_openings.posting_title','job_openings.city','job_openings.state','job_openings.country','job_openings.qualifications','job_openings.salary_from','job_openings.salary_to','job_openings.lacs_from','job_openings.thousand_from','job_openings.lacs_to','job_openings.thousand_to','industry.name as industry_name','job_openings.desired_candidate','job_openings.date_opened','job_openings.target_date','users.name as am_name','client_basicinfo.coordinator_name as coordinator_name','job_openings.priority','job_openings.hiring_manager_id','client_basicinfo.display_name','job_openings.created_at','job_openings.updated_at','client_basicinfo.second_line_am as second_line_am','job_openings.remote_working as remote_working');

        $job_open_query = $job_open_query->leftJoin('job_associate_candidates','job_openings.id','=','job_associate_candidates.job_id');
        $job_open_query = $job_open_query->join('client_basicinfo','client_basicinfo.id','=','job_openings.client_id');
        $job_open_query = $job_open_query->leftJoin('users','users.id','=','job_openings.hiring_manager_id');
        $job_open_query = $job_open_query->leftJoin('industry','industry.id','=','job_openings.industry_id');

        // assign jobs to logged in user
        if($all==0) {
            $job_open_query = $job_open_query->join('job_visible_users','job_visible_users.job_id','=','job_openings.id');
            $job_open_query = $job_open_query->where('user_id','=',$user_id);
        }

        // For Department
        if (isset($department_id) && $department_id > 0) {
            $job_open_query = $job_open_query->where('client_basicinfo.department_id','=',$department_id);
        }

        // Salary Wise Listing

        if (isset($salary) && $salary == '10') {
            $job_open_query = $job_open_query->where('lacs_from','>=',0);
            $job_open_query = $job_open_query->where('lacs_to','<',10);
        }
        else if (isset($salary) && $salary == '10-20') {
            $job_open_query = $job_open_query->where('lacs_to','>=',10);
            $job_open_query = $job_open_query->where('lacs_to','<=',20);
        }
        else if (isset($salary) && $salary == '20') {
            $job_open_query = $job_open_query->where('lacs_to','>',20);
        }

        // Get data by financial year
        if (isset($current_year) && $current_year != NULL) {
            $job_open_query = $job_open_query->where('job_openings.created_at','>=',$current_year);
        }
        if (isset($next_year) && $next_year != NULL) {
            $job_open_query = $job_open_query->where('job_openings.created_at','<=',$next_year);
        }

        // For check open & closed jobs priority

        if (isset($priority) && $priority == '1') {
            $job_open_query = $job_open_query->whereNotIn('job_openings.priority',$job_status);
        }
        else if (isset($priority) && $priority == '4') {
            $job_open_query = $job_open_query->whereIn('job_openings.priority',$job_status);
        }
        else if (isset($priority) && $priority == '11') {
            $job_open_query = $job_open_query->whereNotIn('job_openings.priority',$job_status);
            $job_open_query = $job_open_query->where('adler_career_checkbox','=',1);
        }

        // Master Search Condidtions
        if(isset($mb_name) && $mb_name != '') {

            $job_open_query = $job_open_query->where('job_openings.hiring_manager_id','=',$mb_name);
        }
        else if(isset($company_name) && $company_name != '') {

            $job_open_query = $job_open_query->where('client_basicinfo.name','like',"%$company_name%");
        }
        else if(isset($posting_title) && $posting_title != '') {

            $job_open_query = $job_open_query->where('job_openings.posting_title','like',"%$posting_title%");
        }
        else if(isset($location) && $location != '') {

            if($location == 'Remote') {

                $job_open_query = $job_open_query->where('job_openings.remote_working','=',"1");
            }
            else {

                $job_open_query = $job_open_query->where('job_openings.city','=',"$location");
                $job_open_query = $job_open_query->where('job_openings.remote_working','!=',"1");
            }
        }
        else if (isset($min_ctc) && $min_ctc != '') {

            if($min_ctc == '30') {

                $job_open_query = $job_open_query->where('job_openings.lacs_from','>=',30);
            }
            else if($min_ctc == '0.5') {

                $job_open_query = $job_open_query->where('job_openings.lacs_from','=',0);
            }
            else {
                $job_open_query = $job_open_query->where('job_openings.lacs_from','=',$min_ctc);
            }
        }
        else if (isset($max_ctc) && $max_ctc != '') {

            if($max_ctc == '30') {
                $job_open_query = $job_open_query->where('job_openings.lacs_to','>=',30);
            }
            else if($max_ctc == '0.5') {

                $job_open_query = $job_open_query->where('job_openings.lacs_to','=',0);
            }
            else {
                $job_open_query = $job_open_query->where('job_openings.lacs_to','=',$max_ctc);
            }
        }
        else if(isset($added_date) && $added_date != '') {

            $f_dt = date("Y-m-d 00:00:00",strtotime($added_date));
            $t_dt = date("Y-m-d 23:59:59",strtotime($added_date));

            $job_open_query = $job_open_query->where('job_openings.created_at','>=',"$f_dt");
            $job_open_query = $job_open_query->where('job_openings.created_at','<=',"$t_dt");
        }
        else if(isset($no_of_positions) && $no_of_positions != '') {

            $job_open_query = $job_open_query->where('job_openings.no_of_positions','=',$no_of_positions);
        }
        else if (isset($client_heirarchy) && $client_heirarchy > 0) {
            $job_open_query = $job_open_query->where('job_openings.level_id','=',$client_heirarchy);
        }

        if (isset($search) && $search != '') {
            $job_open_query = $job_open_query->where(function($job_open_query) use ($search) {
                $job_open_query = $job_open_query->where('job_openings.posting_title','like',"%$search%");
                $job_open_query = $job_open_query->orwhere('job_openings.city','like',"%$search%");
                if(($search == 'Remote') || ($search == 'remote')) {
                    $job_open_query = $job_open_query->orwhere('job_openings.remote_working','=',"1");
                }
            });
        }

        $job_open_query = $job_open_query->where('job_associate_candidates.deleted_at',NULL);
        $job_open_query = $job_open_query->groupBy('job_openings.id');
        $job_open_query = $job_open_query->orderBy('job_openings.updated_at','desc');
        $job_open_query = $job_open_query->get();
        $job_response = $job_open_query->count();

        return $job_response;
    }

    public static function getSalaryWiseJobs($all=0,$user_id,$salary,$current_year=NULL,$next_year=NULL,$priority=0,$limit=0,$offset=0,$search='',$order_column_name='',$type='') {

        $job_onhold = getenv('ONHOLD');
        $job_client = getenv('CLOSEDBYCLIENT');
        $job_us = getenv('CLOSEDBYUS');
        $job_status = array($job_onhold,$job_us,$job_client);

        $job_open_query = JobOpen::query();
        $job_open_query = $job_open_query->select(\DB::raw("COUNT(job_associate_candidates.candidate_id) as count"),'job_openings.id','job_openings.job_id','client_basicinfo.name as company_name','job_openings.no_of_positions','job_openings.posting_title','job_openings.city','job_openings.state','job_openings.country','job_openings.qualifications','job_openings.salary_from','job_openings.salary_to','job_openings.lacs_from','job_openings.thousand_from','job_openings.lacs_to','job_openings.thousand_to','industry.name as industry_name','job_openings.desired_candidate','job_openings.date_opened','job_openings.target_date','users.name as am_name','client_basicinfo.coordinator_name as coordinator_name','job_openings.priority','job_openings.hiring_manager_id','client_basicinfo.display_name','job_openings.created_at','job_openings.updated_at','client_basicinfo.second_line_am as second_line_am','job_openings.remote_working as remote_working','job_openings.priority_by');

        $job_open_query = $job_open_query->leftJoin('job_associate_candidates','job_openings.id','=','job_associate_candidates.job_id');
        $job_open_query = $job_open_query->join('client_basicinfo','client_basicinfo.id','=','job_openings.client_id');
        $job_open_query = $job_open_query->leftJoin('users','users.id','=','job_openings.hiring_manager_id');
        $job_open_query = $job_open_query->leftJoin('industry','industry.id','=','job_openings.industry_id');

        // assign jobs to logged in user
        if($all==0) {
            $job_open_query = $job_open_query->join('job_visible_users','job_visible_users.job_id','=','job_openings.id');
            $job_open_query = $job_open_query->where('user_id','=',$user_id);
        }

        // Salary Wise Listing
        if (isset($salary) && $salary == '10') {
            $job_open_query = $job_open_query->where('lacs_from','>=',0);
            $job_open_query = $job_open_query->where('lacs_to','<',10);
        } else if (isset($salary) && $salary == '10-20') {
            $job_open_query = $job_open_query->where('lacs_to','>=',10);
            $job_open_query = $job_open_query->where('lacs_to','<=',20);
        } else if (isset($salary) && $salary == '20') {
            $job_open_query = $job_open_query->where('lacs_to','>',20);
        }

         // Get data by financial year
        if (isset($current_year) && $current_year != NULL) {
            $job_open_query = $job_open_query->where('job_openings.created_at','>=',$current_year);
        }
        if (isset($next_year) && $next_year != NULL) {
            $job_open_query = $job_open_query->where('job_openings.created_at','<=',$next_year);
        }

        // For check open & closed jobs priority
        if (isset($priority) && $priority == '1') {
            $job_open_query = $job_open_query->whereNotIn('job_openings.priority',$job_status);
        } else if (isset($priority) && $priority == '4') {
            $job_open_query = $job_open_query->whereIn('job_openings.priority',$job_status);
        } else if (isset($priority) && $priority == '11') {
            $job_open_query = $job_open_query->whereNotIn('job_openings.priority',$job_status);
            $job_open_query = $job_open_query->where('adler_career_checkbox','=',1);
        }

        if (isset($order_column_name) && $order_column_name != '') {
            $job_open_query = $job_open_query->orderBy($order_column_name,$type);
        } else {
            $job_open_query = $job_open_query->orderBy('job_openings.updated_at','desc');
        }
        if (isset($limit) && $limit > 0) {
            $job_open_query = $job_open_query->limit($limit);
        }
        if (isset($offset) && $offset > 0) {
            $job_open_query = $job_open_query->offset($offset);
        }
        if (isset($search) && $search != '') {
            $job_open_query = $job_open_query->where(function($job_open_query) use ($search) {
                $job_open_query = $job_open_query->where('job_openings.posting_title','like',"%$search%");
                $job_open_query = $job_open_query->orwhere('job_openings.city','like',"%$search%");
                if(($search == 'Remote') || ($search == 'remote')) {
                    $job_open_query = $job_open_query->orwhere('job_openings.remote_working','=',"1");
                }
            });
        }

        $job_open_query = $job_open_query->where('job_associate_candidates.deleted_at',NULL);
        $job_open_query = $job_open_query->groupBy('job_openings.id');
        $job_response = $job_open_query->get();

        $jobs_list = array();
        $colors = self::getJobPrioritiesColor();
        $i = 0;

        foreach ($job_response as $key => $value) {

            // value get in 2 decimal point
            if ($value->lacs_from >= '100') {
                $min_ctc = '100+';
            }
            else {
                $lacs_from = $value->lacs_from*100000;
                $thousand_from = $value->thousand_from*1000;
                $mictc = $lacs_from+$thousand_from;
                $minctc = $mictc/100000;
                $min_ctc = number_format($minctc,2);
            }

            if ($value->lacs_to >= '100') {
                $max_ctc = '100+';
            }
            else {
                $lacs_to = $value->lacs_to*100000;
                $thousand_to = $value->thousand_to*1000;
                $mactc = $lacs_to+$thousand_to;
                $maxctc = $mactc/100000;
                $max_ctc = number_format($maxctc,2);
            }
        
            $jobs_list[$i]['id'] = $value->id;
            $jobs_list[$i]['job_id'] = $value->job_id;
            $jobs_list[$i]['company_name'] = $value->company_name;
            $jobs_list[$i]['display_name'] = $value->display_name;
            $jobs_list[$i]['client'] = $value->company_name." - ".$value->coordinator_name;
            $jobs_list[$i]['no_of_positions'] = $value->no_of_positions;

            if (isset($value->level_name) && $value->level_name != '') {
                $jobs_list[$i]['posting_title'] = $value->level_name." - ".$value->posting_title;
            }
            else {
                $jobs_list[$i]['posting_title'] = $value->posting_title;
            }

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
            $jobs_list[$i]['hiring_manager_id'] = $value->hiring_manager_id;
            $jobs_list[$i]['associate_candidate_cnt'] = $value->count;
            $jobs_list[$i]['priority'] = $value->priority;
            $jobs_list[$i]['priority_by'] = $value->priority_by;
            $jobs_list[$i]['created_date'] = date('d-m-Y',strtotime($value->created_at));
            $jobs_list[$i]['updated_date'] = date('d-m-Y',strtotime($value->updated_at));

            if(isset($value->priority) && $value->priority!='') 
                $jobs_list[$i]['color'] = $colors[$value->priority];
            else
                $jobs_list[$i]['color'] ='';

            if($all==1) {

                $jobs_list[$i]['access'] = '1';
                $jobs_list[$i]['coordinator_name'] = $value->coordinator_name;
            }
            else {

                if(isset($value->hiring_manager_id) && $value->hiring_manager_id == $user_id ) {
                    $jobs_list[$i]['coordinator_name'] = $value->coordinator_name;
                    $jobs_list[$i]['access'] = '1';
                }
                else if(isset($value->second_line_am) && $value->second_line_am == $user_id) {
                    $jobs_list[$i]['coordinator_name'] = $value->coordinator_name;
                    $jobs_list[$i]['access'] = '1';
                }
                else {
                    $jobs_list[$i]['coordinator_name'] = '';
                    $jobs_list[$i]['access'] = '0';
                }
            }

            if($value->hiring_manager_id == 0) {

                $jobs_list[$i]['am_name'] = 'Yet to Assign';
            }
            else {

                $jobs_list[$i]['am_name'] = $value->am_name;
            }

            if($value->remote_working == '1') {

                $jobs_list[$i]['city'] = "Remote";
            }
            else {

                $jobs_list[$i]['city'] = $value->city;
            }

            $jobs_list[$i]['lacs_from'] = $value->lacs_from;
            $jobs_list[$i]['lacs_to'] = $value->lacs_to;

            $i++;
        }
        return $jobs_list;
    }

    public static function getPriorityWiseJobsByClient($client_id,$priority,$current_year=NULL,$next_year=NULL,$client_heirarchy=0,$mb_name='',$company_name='',$posting_title='',$location='',$min_ctc='',$max_ctc='',$added_date='',$no_of_positions='',$limit=0,$offset=0,$search='',$order_column_name='',$type='') {

        $job_open_query = JobOpen::query();
        $job_open_query = $job_open_query->select(\DB::raw("COUNT(job_associate_candidates.candidate_id) as count"),'job_openings.id','job_openings.job_id','client_basicinfo.name as company_name','job_openings.no_of_positions','job_openings.posting_title','job_openings.city','job_openings.state','job_openings.country','job_openings.qualifications','job_openings.salary_from','job_openings.salary_to','job_openings.lacs_from','job_openings.thousand_from','job_openings.lacs_to','job_openings.thousand_to','industry.name as industry_name','job_openings.desired_candidate','job_openings.date_opened','job_openings.target_date','users.name as am_name','client_basicinfo.coordinator_name as coordinator_name','job_openings.priority','job_openings.hiring_manager_id','client_basicinfo.display_name','job_openings.created_at','job_openings.updated_at','job_openings.remote_working as remote_working','job_openings.priority_by');

        $job_open_query = $job_open_query->leftJoin('job_associate_candidates','job_openings.id','=','job_associate_candidates.job_id');
        $job_open_query = $job_open_query->join('client_basicinfo','client_basicinfo.id','=','job_openings.client_id');
        $job_open_query = $job_open_query->leftJoin('users','users.id','=','job_openings.hiring_manager_id');
        $job_open_query = $job_open_query->leftJoin('industry','industry.id','=','job_openings.industry_id');

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
        else if ($priority == 'Constant Deliveries needed for very old positions where many deliveries are done but no result yet') {
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

        // Master Search Condidtions
        if(isset($mb_name) && $mb_name != '') {

            $job_open_query = $job_open_query->where('job_openings.hiring_manager_id','=',$mb_name);
        }
        else if(isset($company_name) && $company_name != '') {

            $job_open_query = $job_open_query->where('client_basicinfo.name','like',"%$company_name%");
        }
        else if(isset($posting_title) && $posting_title != '') {

            $job_open_query = $job_open_query->where('job_openings.posting_title','like',"%$posting_title%");
        }
        else if(isset($location) && $location != '') {

            if($location == 'Remote') {

                $job_open_query = $job_open_query->where('job_openings.remote_working','=',"1");
            }
            else {

                $job_open_query = $job_open_query->where('job_openings.city','=',"$location");
                $job_open_query = $job_open_query->where('job_openings.remote_working','!=',"1");
            }
        }
        else if (isset($min_ctc) && $min_ctc != '') {

            if($min_ctc == '30') {

                $job_open_query = $job_open_query->where('job_openings.lacs_from','>=',30);
            }
            else if($min_ctc == '0.5') {

                $job_open_query = $job_open_query->where('job_openings.lacs_from','=',0);
            }
            else {
                $job_open_query = $job_open_query->where('job_openings.lacs_from','=',$min_ctc);
            }
        }
        else if (isset($max_ctc) && $max_ctc != '') {

            if($max_ctc == '30') {
                $job_open_query = $job_open_query->where('job_openings.lacs_to','>=',30);
            }
            else if($max_ctc == '0.5') {

                $job_open_query = $job_open_query->where('job_openings.lacs_to','=',0);
            }
            else {
                $job_open_query = $job_open_query->where('job_openings.lacs_to','=',$max_ctc);
            }
        }
        else if(isset($added_date) && $added_date != '') {

            $f_dt = date("Y-m-d 00:00:00",strtotime($added_date));
            $t_dt = date("Y-m-d 23:59:59",strtotime($added_date));

            $job_open_query = $job_open_query->where('job_openings.created_at','>=',"$f_dt");
            $job_open_query = $job_open_query->where('job_openings.created_at','<=',"$t_dt");
        }
        else if(isset($no_of_positions) && $no_of_positions != '') {

            $job_open_query = $job_open_query->where('job_openings.no_of_positions','=',$no_of_positions);
        }
        else if (isset($client_heirarchy) && $client_heirarchy > 0) {
            $job_open_query = $job_open_query->where('job_openings.level_id','=',$client_heirarchy);
        }

        // Get data by financial year
        if (isset($current_year) && $current_year != NULL) {
            $job_open_query = $job_open_query->where('job_openings.created_at','>=',$current_year);
        }
        if (isset($next_year) && $next_year != NULL) {
            $job_open_query = $job_open_query->where('job_openings.created_at','<=',$next_year);
        }

        $job_open_query = $job_open_query->where('job_associate_candidates.deleted_at',NULL);
        $job_open_query = $job_open_query->groupBy('job_openings.id');
        $job_open_query = $job_open_query->where('job_openings.client_id',$client_id);

        if (isset($order_column_name) && $order_column_name != '') {
            $job_open_query = $job_open_query->orderBy($order_column_name,$type);
        } else {
            $job_open_query = $job_open_query->orderBy('job_openings.updated_at','desc');
        }
        if (isset($limit) && $limit > 0) {
            $job_open_query = $job_open_query->limit($limit);
        }
        if (isset($offset) && $offset > 0) {
            $job_open_query = $job_open_query->offset($offset);
        }
        if (isset($search) && $search != '') {
            $job_open_query = $job_open_query->where(function($job_open_query) use ($search) {
                $job_open_query = $job_open_query->where('job_openings.posting_title','like',"%$search%");
                $job_open_query = $job_open_query->orwhere('job_openings.city','like',"%$search%");
                if(($search == 'Remote') || ($search == 'remote')) {
                    $job_open_query = $job_open_query->orwhere('job_openings.remote_working','=',"1");
                }
            });
        }
        $job_response = $job_open_query->get();

        $jobs_list = array();
        $colors = self::getJobPrioritiesColor();
        $i = 0;
        foreach ($job_response as $key => $value) {

            // value get in 2 decimal point
            if ($value->lacs_from >= '100') {
                $min_ctc = '100+';
            }
            else {
                $lacs_from = $value->lacs_from*100000;
                $thousand_from = $value->thousand_from*1000;
                $mictc = $lacs_from+$thousand_from;
                $minctc = $mictc/100000;
                $min_ctc = number_format($minctc,2);
            }

            if ($value->lacs_to >= '100') {
                $max_ctc = '100+';
            }
            else {
                $lacs_to = $value->lacs_to*100000;
                $thousand_to = $value->thousand_to*1000;
                $mactc = $lacs_to+$thousand_to;
                $maxctc = $mactc/100000;
                $max_ctc = number_format($maxctc,2);
            }

            $jobs_list[$i]['id'] = $value->id;
            $jobs_list[$i]['job_id'] = $value->job_id;
            $jobs_list[$i]['company_name'] = $value->company_name;
            $jobs_list[$i]['display_name'] = $value->display_name;
            $jobs_list[$i]['client'] = $value->company_name." - ".$value->coordinator_name;
            $jobs_list[$i]['no_of_positions'] = $value->no_of_positions;

            if (isset($value->level_name) && $value->level_name != '') {
                $jobs_list[$i]['posting_title'] = $value->level_name." - ".$value->posting_title;
            }
            else {
                $jobs_list[$i]['posting_title'] = $value->posting_title;
            }

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
            $jobs_list[$i]['hiring_manager_id'] = $value->hiring_manager_id;
            $jobs_list[$i]['associate_candidate_cnt'] = $value->count;
            $jobs_list[$i]['priority'] = $value->priority;
            $jobs_list[$i]['priority_by'] = $value->priority_by;
            $jobs_list[$i]['created_date'] = date('d-m-Y',strtotime($value->created_at));
            $jobs_list[$i]['updated_date'] = date('d-m-Y',strtotime($value->updated_at));

            if(isset($value->priority) && $value->priority!='')
                $jobs_list[$i]['color'] = $colors[$value->priority];
            else
                $jobs_list[$i]['color'] ='';

            $jobs_list[$i]['coordinator_name'] = $value->coordinator_name;
            $jobs_list[$i]['access'] = '0';

            if($value->hiring_manager_id == 0) {

                $jobs_list[$i]['am_name'] = 'Yet to Assign';
            }
            else {

                $jobs_list[$i]['am_name'] = $value->am_name;
            }

            if($value->remote_working == '1') {

                $jobs_list[$i]['city'] = "Remote";
            }
            else {

                $jobs_list[$i]['city'] = $value->city;
            }

            $jobs_list[$i]['lacs_from'] = $value->lacs_from;
            $jobs_list[$i]['lacs_to'] = $value->lacs_to;

            $i++;
        }
        return $jobs_list;
    }

    public static function getPriorityWiseJobsByClientCount($client_id,$priority,$current_year=NULL,$next_year=NULL,$client_heirarchy=0,$mb_name='',$company_name='',$posting_title='',$location='',$min_ctc='',$max_ctc='',$added_date='',$no_of_positions='',$search='') {

        $job_open_query = JobOpen::query();
        $job_open_query = $job_open_query->select(\DB::raw("COUNT(job_associate_candidates.candidate_id) as count"),'job_openings.id','job_openings.job_id','client_basicinfo.name as company_name','job_openings.no_of_positions','job_openings.posting_title','job_openings.city','job_openings.state','job_openings.country','job_openings.qualifications','job_openings.salary_from','job_openings.salary_to','job_openings.lacs_from','job_openings.thousand_from','job_openings.lacs_to','job_openings.thousand_to','industry.name as industry_name','job_openings.desired_candidate','job_openings.date_opened','job_openings.target_date','users.name as am_name','client_basicinfo.coordinator_name as coordinator_name','job_openings.priority','job_openings.hiring_manager_id','client_basicinfo.display_name','job_openings.created_at','job_openings.updated_at','job_openings.remote_working as remote_working');

        $job_open_query = $job_open_query->leftJoin('job_associate_candidates','job_openings.id','=','job_associate_candidates.job_id');
        $job_open_query = $job_open_query->join('client_basicinfo','client_basicinfo.id','=','job_openings.client_id');
        $job_open_query = $job_open_query->leftJoin('users','users.id','=','job_openings.hiring_manager_id');
        $job_open_query = $job_open_query->leftJoin('industry','industry.id','=','job_openings.industry_id');

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
        else if ($priority == 'Constant Deliveries needed for very old positions where many deliveries are done but no result yet') {
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

        // Master Search Condidtions
        if(isset($mb_name) && $mb_name != '') {

            $job_open_query = $job_open_query->where('job_openings.hiring_manager_id','=',$mb_name);
        }
        else if(isset($company_name) && $company_name != '') {

            $job_open_query = $job_open_query->where('client_basicinfo.name','like',"%$company_name%");
        }
        else if(isset($posting_title) && $posting_title != '') {

            $job_open_query = $job_open_query->where('job_openings.posting_title','like',"%$posting_title%");
        }
        else if(isset($location) && $location != '') {

            if($location == 'Remote') {

                $job_open_query = $job_open_query->where('job_openings.remote_working','=',"1");
            }
            else {

                $job_open_query = $job_open_query->where('job_openings.city','=',"$location");
                $job_open_query = $job_open_query->where('job_openings.remote_working','!=',"1");
            }
        }
        else if (isset($min_ctc) && $min_ctc != '') {

            if($min_ctc == '30') {

                $job_open_query = $job_open_query->where('job_openings.lacs_from','>=',30);
            }
            else if($min_ctc == '0.5') {

                $job_open_query = $job_open_query->where('job_openings.lacs_from','=',0);
            }
            else {
                $job_open_query = $job_open_query->where('job_openings.lacs_from','=',$min_ctc);
            }
        }
        else if (isset($max_ctc) && $max_ctc != '') {

            if($max_ctc == '30') {
                $job_open_query = $job_open_query->where('job_openings.lacs_to','>=',30);
            }
            else if($max_ctc == '0.5') {

                $job_open_query = $job_open_query->where('job_openings.lacs_to','=',0);
            }
            else {
                $job_open_query = $job_open_query->where('job_openings.lacs_to','=',$max_ctc);
            }
        }
        else if(isset($added_date) && $added_date != '') {

            $f_dt = date("Y-m-d 00:00:00",strtotime($added_date));
            $t_dt = date("Y-m-d 23:59:59",strtotime($added_date));

            $job_open_query = $job_open_query->where('job_openings.created_at','>=',"$f_dt");
            $job_open_query = $job_open_query->where('job_openings.created_at','<=',"$t_dt");
        }
        else if(isset($no_of_positions) && $no_of_positions != '') {

            $job_open_query = $job_open_query->where('job_openings.no_of_positions','=',$no_of_positions);
        }
        else if (isset($client_heirarchy) && $client_heirarchy > 0) {
            $job_open_query = $job_open_query->where('job_openings.level_id','=',$client_heirarchy);
        }

        // Get data by financial year
        if (isset($current_year) && $current_year != NULL) {
            $job_open_query = $job_open_query->where('job_openings.created_at','>=',$current_year);
        }
        if (isset($next_year) && $next_year != NULL) {
            $job_open_query = $job_open_query->where('job_openings.created_at','<=',$next_year);
        }

        $job_open_query = $job_open_query->where('job_associate_candidates.deleted_at',NULL);
        $job_open_query = $job_open_query->groupBy('job_openings.id');
        $job_open_query = $job_open_query->where('job_openings.client_id',$client_id);

        if (isset($search) && $search != '') {
            $job_open_query = $job_open_query->where(function($job_open_query) use ($search) {
                $job_open_query = $job_open_query->where('job_openings.posting_title','like',"%$search%");
                $job_open_query = $job_open_query->orwhere('job_openings.city','like',"%$search%");
                if(($search == 'Remote') || ($search == 'remote')) {
                    $job_open_query = $job_open_query->orwhere('job_openings.remote_working','=',"1");
                }
            });
        }
        $job_open_query = $job_open_query->orderBy('job_openings.updated_at','desc');
        $job_response = $job_open_query->get();
        $job_count = $job_response->count();

        return $jobs_count;
    }

    public static function getSalaryWiseJobsCountByClient($client_id,$salary,$current_year=NULL,$next_year=NULL,$priority=0,$client_heirarchy=0,$mb_name='',$company_name='',$posting_title='',$location='',$min_ctc='',$max_ctc='',$added_date='',$no_of_positions='',$search='') {

        $job_onhold = getenv('ONHOLD');
        $job_client = getenv('CLOSEDBYCLIENT');
        $job_us = getenv('CLOSEDBYUS');
        $job_status = array($job_onhold,$job_us,$job_client);

        $job_open_query = JobOpen::query();
        $job_open_query = $job_open_query->select(\DB::raw("COUNT(job_associate_candidates.candidate_id) as count"),'job_openings.id','job_openings.job_id','client_basicinfo.name as company_name','job_openings.no_of_positions','job_openings.posting_title','job_openings.city','job_openings.state','job_openings.country','job_openings.qualifications','job_openings.salary_from','job_openings.salary_to','job_openings.lacs_from','job_openings.thousand_from','job_openings.lacs_to','job_openings.thousand_to','industry.name as industry_name','job_openings.desired_candidate','job_openings.date_opened','job_openings.target_date','users.name as am_name','client_basicinfo.coordinator_name as coordinator_name','job_openings.priority','job_openings.hiring_manager_id','client_basicinfo.display_name','job_openings.created_at','job_openings.updated_at','job_openings.remote_working as remote_working');

        $job_open_query = $job_open_query->leftJoin('job_associate_candidates','job_openings.id','=','job_associate_candidates.job_id');
        $job_open_query = $job_open_query->join('client_basicinfo','client_basicinfo.id','=','job_openings.client_id');
        $job_open_query = $job_open_query->leftJoin('users','users.id','=','job_openings.hiring_manager_id');
        $job_open_query = $job_open_query->leftJoin('industry','industry.id','=','job_openings.industry_id');

        // Salary Wise Listing

        if (isset($salary) && $salary == '10') {
            $job_open_query = $job_open_query->where('lacs_from','>=',0);
            $job_open_query = $job_open_query->where('lacs_to','<',10);
        }
        else if (isset($salary) && $salary == '10-20') {
            $job_open_query = $job_open_query->where('lacs_to','>=',10);
            $job_open_query = $job_open_query->where('lacs_to','<=',20);
        }
        else if (isset($salary) && $salary == '20') {
            $job_open_query = $job_open_query->where('lacs_to','>',20);
        }

        // Get data by financial year
        if (isset($current_year) && $current_year != NULL) {
            $job_open_query = $job_open_query->where('job_openings.created_at','>=',$current_year);
        }
        if (isset($next_year) && $next_year != NULL) {
            $job_open_query = $job_open_query->where('job_openings.created_at','<=',$next_year);
        }

        // For check open & closed jobs priority

        if (isset($priority) && $priority == '1') {
            $job_open_query = $job_open_query->whereNotIn('job_openings.priority',$job_status);
        }
        else if (isset($priority) && $priority == '4') {
            $job_open_query = $job_open_query->whereIn('job_openings.priority',$job_status);
        }
        else if (isset($priority) && $priority == '11') {
            $job_open_query = $job_open_query->whereNotIn('job_openings.priority',$job_status);
            $job_open_query = $job_open_query->where('adler_career_checkbox','=',1);
        }

        // Master Search Condidtions
        if(isset($mb_name) && $mb_name != '') {

            $job_open_query = $job_open_query->where('job_openings.hiring_manager_id','=',$mb_name);
        }
        else if(isset($company_name) && $company_name != '') {

            $job_open_query = $job_open_query->where('client_basicinfo.name','like',"%$company_name%");
        }
        else if(isset($posting_title) && $posting_title != '') {

            $job_open_query = $job_open_query->where('job_openings.posting_title','like',"%$posting_title%");
        }
        else if(isset($location) && $location != '') {

            if($location == 'Remote') {

                $job_open_query = $job_open_query->where('job_openings.remote_working','=',"1");
            }
            else {

                $job_open_query = $job_open_query->where('job_openings.city','=',"$location");
                $job_open_query = $job_open_query->where('job_openings.remote_working','!=',"1");
            }
        }
        else if (isset($min_ctc) && $min_ctc != '') {

            if($min_ctc == '30') {

                $job_open_query = $job_open_query->where('job_openings.lacs_from','>=',30);
            }
            else if($min_ctc == '0.5') {

                $job_open_query = $job_open_query->where('job_openings.lacs_from','=',0);
            }
            else {
                $job_open_query = $job_open_query->where('job_openings.lacs_from','=',$min_ctc);
            }
        }
        else if (isset($max_ctc) && $max_ctc != '') {

            if($max_ctc == '30') {
                $job_open_query = $job_open_query->where('job_openings.lacs_to','>=',30);
            }
            else if($max_ctc == '0.5') {

                $job_open_query = $job_open_query->where('job_openings.lacs_to','=',0);
            }
            else {
                $job_open_query = $job_open_query->where('job_openings.lacs_to','=',$max_ctc);
            }
        }
        else if(isset($added_date) && $added_date != '') {

            $f_dt = date("Y-m-d 00:00:00",strtotime($added_date));
            $t_dt = date("Y-m-d 23:59:59",strtotime($added_date));

            $job_open_query = $job_open_query->where('job_openings.created_at','>=',"$f_dt");
            $job_open_query = $job_open_query->where('job_openings.created_at','<=',"$t_dt");
        }
        else if(isset($no_of_positions) && $no_of_positions != '') {
            
            $job_open_query = $job_open_query->where('job_openings.no_of_positions','=',$no_of_positions);
        }
        else if (isset($client_heirarchy) && $client_heirarchy > 0) {
            $job_open_query = $job_open_query->where('job_openings.level_id','=',$client_heirarchy);
        }

        if (isset($search) && $search != '') {
            $job_open_query = $job_open_query->where(function($job_open_query) use ($search) {
                $job_open_query = $job_open_query->where('job_openings.posting_title','like',"%$search%");
                $job_open_query = $job_open_query->orwhere('job_openings.city','like',"%$search%");
                if(($search == 'Remote') || ($search == 'remote')) {
                    $job_open_query = $job_open_query->orwhere('job_openings.remote_working','=',"1");
                }
            });
        }

        $job_open_query = $job_open_query->where('job_associate_candidates.deleted_at',NULL);
        $job_open_query = $job_open_query->groupBy('job_openings.id');
        $job_open_query = $job_open_query->where('job_openings.client_id',$client_id);
        $job_open_query = $job_open_query->orderBy('job_openings.updated_at','desc');
        $job_response = $job_open_query->count();

        return $job_response;
    }

    public static function getSalaryWiseJobsByClient($client_id,$salary,$current_year=NULL,$next_year=NULL,$priority=0,$limit=0,$offset=0,$search='',$order_column_name='',$type='') {

        $job_onhold = getenv('ONHOLD');
        $job_client = getenv('CLOSEDBYCLIENT');
        $job_us = getenv('CLOSEDBYUS');
        $job_status = array($job_onhold,$job_us,$job_client);

        $job_open_query = JobOpen::query();
        $job_open_query = $job_open_query->select(\DB::raw("COUNT(job_associate_candidates.candidate_id) as count"),'job_openings.id','job_openings.job_id','client_basicinfo.name as company_name','job_openings.no_of_positions','job_openings.posting_title','job_openings.city','job_openings.state','job_openings.country','job_openings.qualifications','job_openings.salary_from','job_openings.salary_to','job_openings.lacs_from','job_openings.thousand_from','job_openings.lacs_to','job_openings.thousand_to','industry.name as industry_name','job_openings.desired_candidate','job_openings.date_opened','job_openings.target_date','users.name as am_name','client_basicinfo.coordinator_name as coordinator_name','job_openings.priority','job_openings.hiring_manager_id','client_basicinfo.display_name','job_openings.created_at','job_openings.updated_at','job_openings.remote_working as remote_working','job_openings.priority_by');

        $job_open_query = $job_open_query->leftJoin('job_associate_candidates','job_openings.id','=','job_associate_candidates.job_id');
        $job_open_query = $job_open_query->join('client_basicinfo','client_basicinfo.id','=','job_openings.client_id');
        $job_open_query = $job_open_query->leftJoin('users','users.id','=','job_openings.hiring_manager_id');
        $job_open_query = $job_open_query->leftJoin('industry','industry.id','=','job_openings.industry_id');

        // Salary Wise Listing

        if (isset($salary) && $salary == '10') {
            $job_open_query = $job_open_query->where('lacs_from','>=',0);
            $job_open_query = $job_open_query->where('lacs_to','<',10);
        }
        else if (isset($salary) && $salary == '10-20') {
            $job_open_query = $job_open_query->where('lacs_to','>=',10);
            $job_open_query = $job_open_query->where('lacs_to','<=',20);
        }
        else if (isset($salary) && $salary == '20') {
            $job_open_query = $job_open_query->where('lacs_to','>',20);
        }

        // Get data by financial year
        if (isset($current_year) && $current_year != NULL) {
            $job_open_query = $job_open_query->where('job_openings.created_at','>=',$current_year);
        }
        if (isset($next_year) && $next_year != NULL) {
            $job_open_query = $job_open_query->where('job_openings.created_at','<=',$next_year);
        }

        // For check open & closed jobs priority

        if (isset($priority) && $priority == '1') {
            $job_open_query = $job_open_query->whereNotIn('job_openings.priority',$job_status);
        }
        else if (isset($priority) && $priority == '4') {
            $job_open_query = $job_open_query->whereIn('job_openings.priority',$job_status);
        }
        else if (isset($priority) && $priority == '11') {
            $job_open_query = $job_open_query->whereNotIn('job_openings.priority',$job_status);
            $job_open_query = $job_open_query->where('adler_career_checkbox','=',1);
        }
        
        if (isset($order_column_name) && $order_column_name != '') {
            $job_open_query = $job_open_query->orderBy($order_column_name,$type);
        } else {
            $job_open_query = $job_open_query->orderBy('job_openings.updated_at','desc');
        }
        if (isset($limit) && $limit > 0) {
            $job_open_query = $job_open_query->limit($limit);
        }
        if (isset($offset) && $offset > 0) {
            $job_open_query = $job_open_query->offset($offset);
        }
        if (isset($search) && $search != '') {
            $job_open_query = $job_open_query->where(function($job_open_query) use ($search) {
                $job_open_query = $job_open_query->where('job_openings.posting_title','like',"%$search%");
                $job_open_query = $job_open_query->orwhere('job_openings.city','like',"%$search%");
                if(($search == 'Remote') || ($search == 'remote')) {
                    $job_open_query = $job_open_query->orwhere('job_openings.remote_working','=',"1");
                }
            });
        }

        $job_open_query = $job_open_query->where('job_associate_candidates.deleted_at',NULL);
        $job_open_query = $job_open_query->groupBy('job_openings.id');
        $job_open_query = $job_open_query->where('job_openings.client_id',$client_id);
        $job_response = $job_open_query->get();

        $jobs_list = array();
        $colors = self::getJobPrioritiesColor();
        $i = 0;
        foreach ($job_response as $key => $value) {

            // value get in 2 decimal point
            if ($value->lacs_from >= '100') {
                $min_ctc = '100+';
            }
            else {
                $lacs_from = $value->lacs_from*100000;
                $thousand_from = $value->thousand_from*1000;
                $mictc = $lacs_from+$thousand_from;
                $minctc = $mictc/100000;
                $min_ctc = number_format($minctc,2);
            }

            if ($value->lacs_to >= '100') {
                $max_ctc = '100+';
            }
            else {
                $lacs_to = $value->lacs_to*100000;
                $thousand_to = $value->thousand_to*1000;
                $mactc = $lacs_to+$thousand_to;
                $maxctc = $mactc/100000;
                $max_ctc = number_format($maxctc,2);
            }

            $jobs_list[$i]['id'] = $value->id;
            $jobs_list[$i]['job_id'] = $value->job_id;
            $jobs_list[$i]['company_name'] = $value->company_name;
            $jobs_list[$i]['display_name'] = $value->display_name;
            $jobs_list[$i]['client'] = $value->company_name." - ".$value->coordinator_name;
            $jobs_list[$i]['no_of_positions'] = $value->no_of_positions;

            if (isset($value->level_name) && $value->level_name != '') {
                $jobs_list[$i]['posting_title'] = $value->level_name." - ".$value->posting_title;
            }
            else {
                $jobs_list[$i]['posting_title'] = $value->posting_title;
            }

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
            $jobs_list[$i]['hiring_manager_id'] = $value->hiring_manager_id;
            $jobs_list[$i]['associate_candidate_cnt'] = $value->count;
            $jobs_list[$i]['priority'] = $value->priority;
            $jobs_list[$i]['priority_by'] = $value->priority_by;
            $jobs_list[$i]['created_date'] = date('d-m-Y',strtotime($value->created_at));
            $jobs_list[$i]['updated_date'] = date('d-m-Y',strtotime($value->updated_at));

            if(isset($value->priority) && $value->priority!='')
                $jobs_list[$i]['color'] = $colors[$value->priority];
            else
                $jobs_list[$i]['color'] ='';

            $jobs_list[$i]['coordinator_name'] = $value->coordinator_name;
            $jobs_list[$i]['access'] = '0';

            if($value->hiring_manager_id == 0) {

                $jobs_list[$i]['am_name'] = 'Yet to Assign';
            }
            else {

                $jobs_list[$i]['am_name'] = $value->am_name;
            }

            if($value->remote_working == '1') {

                $jobs_list[$i]['city'] = "Remote";
            }
            else {

                $jobs_list[$i]['city'] = $value->city;
            }

            $i++;
        }
        return $jobs_list;
    }

    public static function getJobIdByClientId($client_id) {

        $job_onhold = getenv('ONHOLD');
        $job_client = getenv('CLOSEDBYCLIENT');
        $job_us = getenv('CLOSEDBYUS');
        $job_status = array($job_onhold,$job_us,$job_client);

        $query = JobOpen::query();
        $query = $query->select('job_openings.id');
        $query = $query->where('job_openings.client_id','=',$client_id);
        //$query = $query->whereNotIn('priority',$job_status);
        $res = $query->get();

        $job_id = array();
        $i = 0;
        foreach ($res as $key => $value) {
            $job_id[$i] = $value->id;
            $i++;
        }
        return $job_id;
    }

    // Get jobid for open_to_all new user
    public static function getAllJobsId($open_to_all=1) {

        $query = JobOpen::query();
        $query = $query->select('job_openings.id');
        if ($open_to_all > 0) {
            $query = $query->where('job_openings.open_to_all',$open_to_all);
        }
        $res = $query->get();

        $job_id = array();
        $i = 0;
        if (isset($res) && $res != '') {
            foreach ($res as $key => $value) {
                $job_id[$i] = $value->id;
                $i++;
            }
        }
        return $job_id;
    }

    public static function getAllAPIJobsDetails($limit=0,$offset=0) {

        $job_onhold = getenv('ONHOLD');
        $job_client = getenv('CLOSEDBYCLIENT');
        $job_us = getenv('CLOSEDBYUS');
        $job_status = array($job_onhold,$job_us,$job_client);

        $job_open_query = JobOpen::query();

        $job_open_query = $job_open_query->select('job_openings.id','client_basicinfo.name as company_name','job_openings.no_of_positions','job_openings.posting_title','job_openings.city','job_openings.state','job_openings.country','job_openings.qualifications','job_openings.lacs_from','job_openings.thousand_from','job_openings.lacs_to','job_openings.thousand_to','industry.name as industry_name','job_openings.desired_candidate','client_basicinfo.coordinator_name as coordinator_name','job_openings.job_description as job_description','job_openings.work_exp_from','job_openings.work_exp_to','job_openings.adler_job_disclosed_checkbox','job_openings.created_at as added_date','job_openings.remote_working as remote_working','job_openings.updated_at as updated_at');
        
        $job_open_query = $job_open_query->join('client_basicinfo','client_basicinfo.id','=','job_openings.client_id');

        $job_open_query = $job_open_query->leftJoin('industry','industry.id','=','job_openings.industry_id');

        if (isset($limit) && $limit > 0) {
            $job_open_query = $job_open_query->limit($limit);
        }
        if (isset($offset) && $offset > 0) {
            $job_open_query = $job_open_query->offset($offset);
        }

        $job_open_query = $job_open_query->whereNotIn('job_openings.priority',$job_status);
        $job_open_query = $job_open_query->where('job_openings.adler_career_checkbox','=','1');
        $job_open_query = $job_open_query->orderBy('job_openings.updated_at','desc');
        $job_open_query = $job_open_query->groupBy('job_openings.id');
        
        $job_response = $job_open_query->get();

        $jobs_list = array();
        $i=0;

        foreach ($job_response as $key => $value) {

            // value get in 2 decimal point
            if ($value->lacs_from >= '100') {
                $min_ctc = '100+';
            }
            else {
                $lacs_from = $value->lacs_from*100000;
                $thousand_from = $value->thousand_from*1000;
                $mictc = $lacs_from+$thousand_from;
                $minctc = $mictc/100000;
                $min_ctc = number_format($minctc,2);
            }

            if ($value->lacs_to >= '100') {
                $max_ctc = '100+';
            }
            else {
                $lacs_to = $value->lacs_to*100000;
                $thousand_to = $value->thousand_to*1000;
                $mactc = $lacs_to+$thousand_to;
                $maxctc = $mactc/100000;
                $max_ctc = number_format($maxctc,2);
            }

            $jobs_list[$i]['min_ctc'] = $min_ctc;
            $jobs_list[$i]['max_ctc'] = $max_ctc;

            $jobs_list[$i]['id'] = $value->id;
            $jobs_list[$i]['company_name'] = $value->company_name;
            $jobs_list[$i]['no_of_positions'] = $value->no_of_positions;
            $jobs_list[$i]['posting_title'] = $value->posting_title;

            $jobs_list[$i]['work_exp_from'] = $value->work_exp_from;
            $jobs_list[$i]['work_exp_to'] = $value->work_exp_to;

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

            
            $jobs_list[$i]['industry'] = $value->industry_name;
            $jobs_list[$i]['qualifications'] = $value->qualifications;
            $jobs_list[$i]['desired_candidate'] = $value->desired_candidate;

            $desc_len = strlen($value->job_description);

            if ($desc_len >= 70) {

                $desc = substr($value->job_description,0,70);
                $desc .= '[...]';
            }
            else {
                $desc = strip_tags($value->job_description);
            }

            $jobs_list[$i]['job_description'] = $desc;
            $jobs_list[$i]['adler_job_disclosed_checkbox'] = $value->adler_job_disclosed_checkbox;

            $jobs_list[$i]['added_date'] = date('d-m-Y',strtotime($value->added_date));

            $to_date = date('Y-m-d');

            $to = strtotime($to_date);
            $from = strtotime($value->updated_at);
            $diff_in_days = ($to - $from)/60/60/24;

            $jobs_list[$i]['diff_in_days'] = $diff_in_days;

            if($value->remote_working == '1') {

                $jobs_list[$i]['city'] = "Remote";
            }
            else {

                $jobs_list[$i]['city'] = $value->city;
            }

            $i++;
        }
        return $jobs_list;
    }

    public static function getAPIJobDetailsById($job_id) {

        $job_onhold = getenv('ONHOLD');
        $job_client = getenv('CLOSEDBYCLIENT');
        $job_us = getenv('CLOSEDBYUS');
        $job_status = array($job_onhold,$job_us,$job_client);

        $job_open_query = JobOpen::query();

        $job_open_query = $job_open_query->select('job_openings.id','client_basicinfo.name as company_name','job_openings.no_of_positions','job_openings.posting_title','job_openings.city','job_openings.state','job_openings.country','job_openings.qualifications','job_openings.lacs_from','job_openings.thousand_from','job_openings.lacs_to','job_openings.thousand_to','industry.name as industry_name','job_openings.desired_candidate','client_basicinfo.coordinator_name as coordinator_name','job_openings.job_description as job_description','job_openings.work_exp_from','job_openings.work_exp_to','job_openings.adler_job_disclosed_checkbox','job_openings.remote_working as remote_working');
        
        $job_open_query = $job_open_query->join('client_basicinfo','client_basicinfo.id','=','job_openings.client_id');

        $job_open_query = $job_open_query->leftJoin('industry','industry.id','=','job_openings.industry_id');

        $job_open_query = $job_open_query->whereNotIn('job_openings.priority',$job_status);
        $job_open_query = $job_open_query->where('job_openings.adler_career_checkbox','=','1');
        $job_open_query = $job_open_query->where('job_openings.id','=',$job_id);
        $job_open_query = $job_open_query->orderBy('job_openings.updated_at','desc');
        $job_open_query = $job_open_query->groupBy('job_openings.id');
        
        $response = $job_open_query->first();

        $jobs_list = array();

        if(isset($response) && $response != '') {

            // value get in 2 decimal point
            if ($response->lacs_from >= '100') {
                $min_ctc = '100+';
            }
            else {
                $lacs_from = $response->lacs_from*100000;
                $thousand_from = $response->thousand_from*1000;
                $mictc = $lacs_from+$thousand_from;
                $minctc = $mictc/100000;
                $min_ctc = number_format($minctc,2);
            }

            if ($response->lacs_to >= '100') {
                $max_ctc = '100+';
            }
            else {
                $lacs_to = $response->lacs_to*100000;
                $thousand_to = $response->thousand_to*1000;
                $mactc = $lacs_to+$thousand_to;
                $maxctc = $mactc/100000;
                $max_ctc = number_format($maxctc,2);
            }

            $jobs_list['id'] = $response->id;
            $jobs_list['company_name'] = $response->company_name;
            $jobs_list['no_of_positions'] = $response->no_of_positions;
            $jobs_list['posting_title'] = $response->posting_title;

            $location ='';
            if($response->city!=''){
                $location .= $response->city;
            }
            if($response->state!=''){
                if($location=='')
                    $location .= $response->state;
                else
                    $location .= ", ".$response->state;
            }
            if($response->country!=''){
                if($location=='')
                    $location .= $response->country;
                else
                    $location .= ", ".$response->country;
            }
            
            $jobs_list['location'] = $location;
            
            $jobs_list['min_ctc'] = $min_ctc;
            $jobs_list['max_ctc'] = $max_ctc;

            $jobs_list['work_exp_from'] = $response->work_exp_from;
            $jobs_list['work_exp_to'] = $response->work_exp_to;

            $jobs_list['industry'] = $response->industry_name;
            $jobs_list['qualifications'] = $response->qualifications;
            $jobs_list['desired_candidate'] = $response->desired_candidate;
            $jobs_list['job_description'] = $response->job_description;
            $jobs_list['adler_job_disclosed_checkbox'] = $response->adler_job_disclosed_checkbox;

            if($response->remote_working == '1') {

                $jobs_list['city'] = "Remote";
            }
            else {

                $jobs_list['city'] = $response->city;
            }
        }
        return $jobs_list;
    }

    public static function getAllApplicantJobsCount($all=0,$user_id,$search) {

        $job_onhold = getenv('ONHOLD');
        $job_client = getenv('CLOSEDBYCLIENT');
        $job_us = getenv('CLOSEDBYUS');
        $job_status = array($job_onhold,$job_us,$job_client);

        $job_open_query = JobOpen::query();
        $job_open_query = $job_open_query->select(\DB::raw("COUNT(job_associate_candidates.candidate_id) as count"),'job_openings.id','job_openings.job_id','client_basicinfo.name as company_name','job_openings.no_of_positions','job_openings.posting_title','job_openings.city','job_openings.state','job_openings.country','job_openings.qualifications','job_openings.salary_from','job_openings.salary_to','job_openings.lacs_from','job_openings.thousand_from','job_openings.lacs_to','job_openings.thousand_to','industry.name as industry_name','job_openings.desired_candidate','job_openings.date_opened','job_openings.target_date','users.name as am_name','client_basicinfo.coordinator_name as coordinator_name','job_openings.priority','job_openings.hiring_manager_id','client_basicinfo.display_name','job_openings.created_at','job_openings.updated_at as updated_at','job_openings.remote_working as remote_working');

        $job_open_query = $job_open_query->leftJoin('job_associate_candidates','job_openings.id','=','job_associate_candidates.job_id');
        $job_open_query = $job_open_query->join('client_basicinfo','client_basicinfo.id','=','job_openings.client_id');
        $job_open_query = $job_open_query->join('users','users.id','=','job_openings.hiring_manager_id');
        $job_open_query = $job_open_query->leftJoin('industry','industry.id','=','job_openings.industry_id');

        // assign jobs to logged in user
        if($all==0) {
            $job_open_query = $job_open_query->join('job_visible_users','job_visible_users.job_id','=','job_openings.id');
            $job_open_query = $job_open_query->where('user_id','=',$user_id);
        }

        $job_open_query = $job_open_query->whereNotIn('job_openings.priority',$job_status);
        $job_open_query = $job_open_query->where('job_openings.adler_career_checkbox','=','1');
        $job_open_query = $job_open_query->where('job_associate_candidates.deleted_at',NULL);
        $job_open_query = $job_open_query->groupBy('job_openings.id');

        if (isset($search) && $search != '') {

            $job_open_query = $job_open_query->where(function($job_open_query) use ($search) {

                $date_search = false;
                $date_array = explode("-",$search);
                if(isset($date_array) && sizeof($date_array)>0) {

                    $stamp = strtotime($search);
                    if (is_numeric($stamp)){
                        $month = date( 'm', $stamp );
                        $day   = date( 'd', $stamp );
                        $year  = date( 'Y', $stamp );

                        if(checkdate($month, $day, $year)){
                            $date_search = true;
                        }
                    }
                }

                $job_open_query = $job_open_query->where('job_openings.posting_title','like',"%$search%");
                $job_open_query = $job_open_query->orwhere('users.name','like',"%$search%");
                $job_open_query = $job_open_query->orwhere('client_basicinfo.display_name','like',"%$search%");
                $job_open_query = $job_open_query->orwhere('client_basicinfo.coordinator_name','like',"%$search%");
                $job_open_query = $job_open_query->orwhere('job_openings.no_of_positions','like',"%$search%");
                $job_open_query = $job_open_query->orwhere('job_openings.city','like',"%$search%");

                if(($search == 'Remote') || ($search == 'remote')) {
                    
                    $job_open_query = $job_open_query->orwhere('job_openings.remote_working','=',"1");
                }

                if($date_search) {
                    $dateClass = new Date();
                    $search_string = $dateClass->changeDMYtoYMD($search);
                    $from_date = date("Y-m-d 00:00:00",strtotime($search_string));
                    $to_date = date("Y-m-d 23:59:59",strtotime($search_string));
                    $job_open_query = $job_open_query->orwhere('job_openings.created_at','>=',"$from_date");
                    $job_open_query = $job_open_query->Where('job_openings.created_at','<=',"$to_date");
                }
            });
        }

        $job_response = $job_open_query->get();

        return sizeof($job_response);
    }

    public static function getApplicantJobs($all=0,$user_id,$limit=0,$offset=0,$search=0,$order=NULL,$type='desc') {

        $job_onhold = getenv('ONHOLD');
        $job_client = getenv('CLOSEDBYCLIENT');
        $job_us = getenv('CLOSEDBYUS');
        $job_status = array($job_onhold,$job_us,$job_client);

        $job_open_query = JobOpen::query();

        $job_open_query = $job_open_query->select(\DB::raw("COUNT(job_associate_candidates.candidate_id) as count"),'job_openings.id','job_openings.job_id','client_basicinfo.name as company_name','job_openings.no_of_positions','job_openings.posting_title','job_openings.city','job_openings.state','job_openings.country','job_openings.qualifications','job_openings.salary_from','job_openings.salary_to','job_openings.lacs_from','job_openings.thousand_from','job_openings.lacs_to','job_openings.thousand_to','industry.name as industry_name','job_openings.desired_candidate','job_openings.date_opened','job_openings.target_date','users.name as am_name','client_basicinfo.coordinator_name as coordinator_name','job_openings.priority','job_openings.hiring_manager_id','client_basicinfo.display_name','job_openings.created_at','job_openings.updated_at as updated_at','client_basicinfo.second_line_am as second_line_am','job_openings.remote_working as remote_working');
        
        $job_open_query = $job_open_query->leftJoin('job_associate_candidates','job_openings.id','=','job_associate_candidates.job_id');
        $job_open_query = $job_open_query->join('client_basicinfo','client_basicinfo.id','=','job_openings.client_id');
        $job_open_query = $job_open_query->join('users','users.id','=','job_openings.hiring_manager_id');

        $job_open_query = $job_open_query->leftJoin('industry','industry.id','=','job_openings.industry_id');

        // assign jobs to logged in user
        if($all==0) {
            $job_open_query = $job_open_query->join('job_visible_users','job_visible_users.job_id','=','job_openings.id');
            $job_open_query = $job_open_query->where('job_visible_users.user_id','=',$user_id);
        }

        $job_open_query = $job_open_query->whereNotIn('job_openings.priority',$job_status);
        $job_open_query = $job_open_query->where('job_associate_candidates.deleted_at',NULL);
        $job_open_query = $job_open_query->where('job_openings.adler_career_checkbox','=','1');
        $job_open_query = $job_open_query->groupBy('job_openings.id');

        if (isset($order) && $order != '') {
            if ($order == 'job_openings.lacs_from') {
                $job_open_query = $job_open_query->orderBy($order,$type);
                $job_open_query = $job_open_query->orderBy('job_openings.thousand_from',$type);
            }
            else if ($order == 'job_openings.lacs_to') {
                $job_open_query = $job_open_query->orderBy($order,$type);
                $job_open_query = $job_open_query->orderBy('job_openings.thousand_to',$type);
            }
            else{
                $job_open_query = $job_open_query->orderBy($order,$type);
            }
        }

        if (isset($limit) && $limit > 0) {
            $job_open_query = $job_open_query->limit($limit);
        }
        if (isset($offset) && $offset > 0) {
            $job_open_query = $job_open_query->offset($offset);
        }

        if (isset($search) && $search != '') {

            $job_open_query = $job_open_query->where(function($job_open_query) use ($search) {

                $date_search = false;
                $date_array = explode("-",$search);
                if(isset($date_array) && sizeof($date_array)>0){
                    $stamp = strtotime($search);
                    if (is_numeric($stamp)){
                        $month = date( 'm', $stamp );
                        $day   = date( 'd', $stamp );
                        $year  = date( 'Y', $stamp );

                        if(checkdate($month, $day, $year)){
                            $date_search = true;
                        }
                    }
                }
                $job_open_query = $job_open_query->where('job_openings.posting_title','like',"%$search%");
                $job_open_query = $job_open_query->orwhere('users.name','like',"%$search%");
                $job_open_query = $job_open_query->orwhere('client_basicinfo.display_name','like',"%$search%");
                $job_open_query = $job_open_query->orwhere('client_basicinfo.coordinator_name','like',"%$search%");
                $job_open_query = $job_open_query->orwhere('job_openings.no_of_positions','like',"%$search%");
                $job_open_query = $job_open_query->orwhere('job_openings.city','like',"%$search%");

                if(($search == 'Remote') || ($search == 'remote')) {
                    
                    $job_open_query = $job_open_query->orwhere('job_openings.remote_working','=',"1");
                }

                if($date_search) {

                    $dateClass = new Date();
                    $search_string = $dateClass->changeDMYtoYMD($search);
                    $from_date = date("Y-m-d 00:00:00",strtotime($search_string));
                    $to_date = date("Y-m-d 23:59:59",strtotime($search_string));
                    $job_open_query = $job_open_query->orwhere('job_openings.created_at','>=',"$from_date");
                    $job_open_query = $job_open_query->Where('job_openings.created_at','<=',"$to_date");
                }
            });
        }

        $job_response = $job_open_query->get();

        $jobs_list = array();
        $colors = self::getJobPrioritiesColor();
        $i = 0;

        foreach ($job_response as $key => $value) {

            // value get in 2 decimal point
            if ($value->lacs_from >= '100') {
                $min_ctc = '100+';
            }
            else {
                $lacs_from = $value->lacs_from*100000;
                $thousand_from = $value->thousand_from*1000;
                $mictc = $lacs_from+$thousand_from;
                $minctc = $mictc/100000;
                $min_ctc = number_format($minctc,2);
            }

            if ($value->lacs_to >= '100') {
                $max_ctc = '100+';
            }
            else {
                $lacs_to = $value->lacs_to*100000;
                $thousand_to = $value->thousand_to*1000;
                $mactc = $lacs_to+$thousand_to;
                $maxctc = $mactc/100000;
                $max_ctc = number_format($maxctc,2);
            }
            
            $jobs_list[$i]['id'] = $value->id;
            $jobs_list[$i]['job_id'] = $value->job_id;
            $jobs_list[$i]['company_name'] = $value->company_name;
            $jobs_list[$i]['display_name'] = $value->display_name;
            $jobs_list[$i]['client'] = $value->company_name." - ".$value->coordinator_name;
            $jobs_list[$i]['no_of_positions'] = $value->no_of_positions;

            if (isset($value->level_name) && $value->level_name != '') {
                $jobs_list[$i]['posting_title'] = $value->level_name." - ".$value->posting_title;
            }
            else {
                $jobs_list[$i]['posting_title'] = $value->posting_title;
            }
            
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
            $jobs_list[$i]['hiring_manager_id'] = $value->hiring_manager_id;
            $jobs_list[$i]['associate_candidate_cnt'] = JobAssociateCandidates::getJobAssociatedCvsCount($value->id);
            $jobs_list[$i]['priority'] = $value->priority;
            $jobs_list[$i]['created_date'] = date('d-m-Y',strtotime($value->created_at));
            $jobs_list[$i]['updated_date'] = date('d-m-Y',strtotime($value->updated_at));

            if(isset($value->priority) && $value->priority!='')
                $jobs_list[$i]['color'] = $colors[$value->priority];
            else
                $jobs_list[$i]['color'] ='';

            if($all==1) {
                $jobs_list[$i]['access'] = '1';
                $jobs_list[$i]['coordinator_name'] = $value->coordinator_name;
            }
            else {
                if(isset($value->hiring_manager_id) && $value->hiring_manager_id == $user_id) {
                    $jobs_list[$i]['coordinator_name'] = $value->coordinator_name;
                    $jobs_list[$i]['access'] = '1';
                }
                else if(isset($value->second_line_am) && $value->second_line_am == $user_id) {
                    $jobs_list[$i]['coordinator_name'] = $value->coordinator_name;
                    $jobs_list[$i]['access'] = '1';
                }
                else{
                    $jobs_list[$i]['coordinator_name'] = '';
                    $jobs_list[$i]['access'] = '0';
                }
            }
            $jobs_list[$i]['level_name'] = $value->level_name;

            if($value->hiring_manager_id == 0) {

                $jobs_list[$i]['am_name'] = 'Yet to Assign';
            }
            else {

                $jobs_list[$i]['am_name'] = $value->am_name;
            }

            $jobs_list[$i]['applicant_count'] = CandidateOtherInfo::getApplicantJobCvsCount($value->id);

            if($value->remote_working == '1') {

                $jobs_list[$i]['city'] = "Remote";
            }
            else {

                $jobs_list[$i]['city'] = $value->city;
            }

            $i++;
        }
        return $jobs_list;
    }

    public static function getPriorityWiseApplicantJobs($all=0,$user_id,$priority,$limit=0,$offset=0,$search='',$order_column_name='',$type='') {

        $job_onhold = getenv('ONHOLD');
        $job_client = getenv('CLOSEDBYCLIENT');
        $job_us = getenv('CLOSEDBYUS');
        $job_status = array($job_onhold,$job_us,$job_client);

        $job_open_query = JobOpen::query();
        $job_open_query = $job_open_query->select(\DB::raw("COUNT(job_associate_candidates.candidate_id) as count"),'job_openings.id','job_openings.job_id','client_basicinfo.name as company_name','job_openings.no_of_positions','job_openings.posting_title','job_openings.city','job_openings.state','job_openings.country','job_openings.qualifications','job_openings.salary_from','job_openings.salary_to','job_openings.lacs_from','job_openings.thousand_from','job_openings.lacs_to','job_openings.thousand_to','industry.name as industry_name','job_openings.desired_candidate','job_openings.date_opened','job_openings.target_date','users.name as am_name','client_basicinfo.coordinator_name as coordinator_name','job_openings.priority','job_openings.hiring_manager_id','client_basicinfo.display_name','job_openings.created_at','job_openings.updated_at','client_basicinfo.second_line_am as second_line_am','job_openings.remote_working as remote_working');

        $job_open_query = $job_open_query->leftJoin('job_associate_candidates','job_openings.id','=','job_associate_candidates.job_id');
        $job_open_query = $job_open_query->join('client_basicinfo','client_basicinfo.id','=','job_openings.client_id');
        $job_open_query = $job_open_query->leftJoin('users','users.id','=','job_openings.hiring_manager_id');
        $job_open_query = $job_open_query->leftJoin('industry','industry.id','=','job_openings.industry_id');

        // assign jobs to logged in user
        if($all==0) {
            $job_open_query = $job_open_query->join('job_visible_users','job_visible_users.job_id','=','job_openings.id');
            $job_open_query = $job_open_query->where('user_id','=',$user_id);
        }

        if ($priority == '-None-') {
            $job_open_query = $job_open_query->where('priority','=','0');
        } else if ($priority == 'Urgent Positions') {
            $job_open_query = $job_open_query->where('priority','=','1');
        } else if ($priority == 'New Positions') {
            $job_open_query = $job_open_query->where('priority','=','2');
        } else if ($priority == 'Constant Deliveries needed') {
            $job_open_query = $job_open_query->where('priority','=','3');
        } else if ($priority == 'On Hold') {
            $job_open_query = $job_open_query->where('priority','=','4');
        } else if ($priority == 'Revived Positions') {
            $job_open_query = $job_open_query->where('priority','=','5');
        } else if ($priority == 'Constant Deliveries needed for very old positions where many deliveries are done but no result yet') {
            $job_open_query = $job_open_query->where('priority','=','6');
        } else if ($priority == 'No Deliveries Needed') {
            $job_open_query = $job_open_query->where('priority','=','7');
        } else if ($priority == 'Identified candidates') {
            $job_open_query = $job_open_query->where('priority','=','8');
        } else if ($priority == 'Closed By Us') {
            $job_open_query = $job_open_query->where('priority','=','9');
        } else if ($priority == 'Closed By Client') {
            $job_open_query = $job_open_query->where('priority','=','10');
        }
        
        if (isset($order_column_name) && $order_column_name != '') {
            $job_open_query = $job_open_query->orderBy($order_column_name,$type);
        } else {
            $job_open_query = $job_open_query->orderBy('job_openings.updated_at','desc');
        }
        if (isset($limit) && $limit > 0) {
            $job_open_query = $job_open_query->limit($limit);
        }
        if (isset($offset) && $offset > 0) {
            $job_open_query = $job_open_query->offset($offset);
        }
        if (isset($search) && $search != '') {
            $job_open_query = $job_open_query->where(function($job_open_query) use ($search) {
                $job_open_query = $job_open_query->where('job_openings.posting_title','like',"%$search%");
                $job_open_query = $job_open_query->orwhere('job_openings.city','like',"%$search%");
                if(($search == 'Remote') || ($search == 'remote')) {
                    $job_open_query = $job_open_query->orwhere('job_openings.remote_working','=',"1");
                }
            });
        }

        $job_open_query = $job_open_query->whereNotIn('job_openings.priority',$job_status);
        $job_open_query = $job_open_query->where('job_openings.adler_career_checkbox','=','1');
        $job_open_query = $job_open_query->where('job_associate_candidates.deleted_at',NULL);
        $job_open_query = $job_open_query->groupBy('job_openings.id');
        $job_response = $job_open_query->get();

        $jobs_list = array();
        $colors = self::getJobPrioritiesColor();
        $i = 0;

        foreach ($job_response as $key => $value) {

            // value get in 2 decimal point
            if ($value->lacs_from >= '100') {
                $min_ctc = '100+';
            }
            else {
                $lacs_from = $value->lacs_from*100000;
                $thousand_from = $value->thousand_from*1000;
                $mictc = $lacs_from+$thousand_from;
                $minctc = $mictc/100000;
                $min_ctc = number_format($minctc,2);
            }

            if ($value->lacs_to >= '100') {
                $max_ctc = '100+';
            }
            else {
                $lacs_to = $value->lacs_to*100000;
                $thousand_to = $value->thousand_to*1000;
                $mactc = $lacs_to+$thousand_to;
                $maxctc = $mactc/100000;
                $max_ctc = number_format($maxctc,2);
            }
        
            $jobs_list[$i]['id'] = $value->id;
            $jobs_list[$i]['job_id'] = $value->job_id;
            $jobs_list[$i]['company_name'] = $value->company_name;
            $jobs_list[$i]['display_name'] = $value->display_name;
            $jobs_list[$i]['client'] = $value->company_name." - ".$value->coordinator_name;
            $jobs_list[$i]['no_of_positions'] = $value->no_of_positions;

            if (isset($value->level_name) && $value->level_name != '') {
                $jobs_list[$i]['posting_title'] = $value->level_name." - ".$value->posting_title;
            }
            else {
                $jobs_list[$i]['posting_title'] = $value->posting_title;
            }

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
            $jobs_list[$i]['hiring_manager_id'] = $value->hiring_manager_id;
            $jobs_list[$i]['associate_candidate_cnt'] = $value->count;
            $jobs_list[$i]['priority'] = $value->priority;
            $jobs_list[$i]['created_date'] = date('d-m-Y',strtotime($value->created_at));
            $jobs_list[$i]['updated_date'] = date('d-m-Y',strtotime($value->updated_at));

            if(isset($value->priority) && $value->priority!='') 
                $jobs_list[$i]['color'] = $colors[$value->priority];
            else
                $jobs_list[$i]['color'] ='';

            if($all==1) {

                $jobs_list[$i]['access'] = '1';
                $jobs_list[$i]['coordinator_name'] = $value->coordinator_name;
            }
            else {

                if(isset($value->hiring_manager_id) && $value->hiring_manager_id == $user_id ) {
                    $jobs_list[$i]['coordinator_name'] = $value->coordinator_name;
                    $jobs_list[$i]['access'] = '1';
                }
                else if(isset($value->second_line_am) && $value->second_line_am == $user_id) {
                    $jobs_list[$i]['coordinator_name'] = $value->coordinator_name;
                    $jobs_list[$i]['access'] = '1';
                }
                else {
                    $jobs_list[$i]['coordinator_name'] = '';
                    $jobs_list[$i]['access'] = '0';
                }
            }

            if($value->hiring_manager_id == 0) {

                $jobs_list[$i]['am_name'] = 'Yet to Assign';
            }
            else {

                $jobs_list[$i]['am_name'] = $value->am_name;
            }

            $jobs_list[$i]['applicant_count'] = CandidateOtherInfo::getApplicantJobCvsCount($value->id);

            if($value->remote_working == '1') {

                $jobs_list[$i]['city'] = "Remote";
            }
            else {

                $jobs_list[$i]['city'] = $value->city;
            }

            $jobs_list[$i]['lacs_from'] = $value->lacs_from;
            $jobs_list[$i]['lacs_to'] = $value->lacs_to;

            $i++;
        }
        return $jobs_list;
    }

    public static function getPriorityWiseApplicantJobsCount($all=0,$user_id,$priority,$search='') {

        $job_onhold = getenv('ONHOLD');
        $job_client = getenv('CLOSEDBYCLIENT');
        $job_us = getenv('CLOSEDBYUS');
        $job_status = array($job_onhold,$job_us,$job_client);

        $job_open_query = JobOpen::query();
        $job_open_query = $job_open_query->select(\DB::raw("COUNT(job_associate_candidates.candidate_id) as count"),'job_openings.id','job_openings.job_id','client_basicinfo.name as company_name','job_openings.no_of_positions','job_openings.posting_title','job_openings.city','job_openings.state','job_openings.country','job_openings.qualifications','job_openings.salary_from','job_openings.salary_to','job_openings.lacs_from','job_openings.thousand_from','job_openings.lacs_to','job_openings.thousand_to','industry.name as industry_name','job_openings.desired_candidate','job_openings.date_opened','job_openings.target_date','users.name as am_name','client_basicinfo.coordinator_name as coordinator_name','job_openings.priority','job_openings.hiring_manager_id','client_basicinfo.display_name','job_openings.created_at','job_openings.updated_at','client_basicinfo.second_line_am as second_line_am','job_openings.remote_working as remote_working');

        $job_open_query = $job_open_query->leftJoin('job_associate_candidates','job_openings.id','=','job_associate_candidates.job_id');
        $job_open_query = $job_open_query->join('client_basicinfo','client_basicinfo.id','=','job_openings.client_id');
        $job_open_query = $job_open_query->leftJoin('users','users.id','=','job_openings.hiring_manager_id');
        $job_open_query = $job_open_query->leftJoin('industry','industry.id','=','job_openings.industry_id');

        // assign jobs to logged in user
        if($all==0) {
            $job_open_query = $job_open_query->join('job_visible_users','job_visible_users.job_id','=','job_openings.id');
            $job_open_query = $job_open_query->where('user_id','=',$user_id);
        }

        if ($priority == '-None-') {
            $job_open_query = $job_open_query->where('priority','=','0');
        } else if ($priority == 'Urgent Positions') {
            $job_open_query = $job_open_query->where('priority','=','1');
        } else if ($priority == 'New Positions') {
            $job_open_query = $job_open_query->where('priority','=','2');
        } else if ($priority == 'Constant Deliveries needed') {
            $job_open_query = $job_open_query->where('priority','=','3');
        } else if ($priority == 'On Hold') {
            $job_open_query = $job_open_query->where('priority','=','4');
        } else if ($priority == 'Revived Positions') {
            $job_open_query = $job_open_query->where('priority','=','5');
        } else if ($priority == 'Constant Deliveries needed for very old positions where many deliveries are done but no result yet') {
            $job_open_query = $job_open_query->where('priority','=','6');
        } else if ($priority == 'No Deliveries Needed') {
            $job_open_query = $job_open_query->where('priority','=','7');
        } else if ($priority == 'Identified candidates') {
            $job_open_query = $job_open_query->where('priority','=','8');
        } else if ($priority == 'Closed By Us') {
            $job_open_query = $job_open_query->where('priority','=','9');
        } else if ($priority == 'Closed By Client') {
            $job_open_query = $job_open_query->where('priority','=','10');
        }
        
        if (isset($search) && $search != '') {
            $job_open_query = $job_open_query->where(function($job_open_query) use ($search) {
                $job_open_query = $job_open_query->where('job_openings.posting_title','like',"%$search%");
                $job_open_query = $job_open_query->orwhere('job_openings.city','like',"%$search%");
                if(($search == 'Remote') || ($search == 'remote')) {
                    $job_open_query = $job_open_query->orwhere('job_openings.remote_working','=',"1");
                }
            });
        }

        $job_open_query = $job_open_query->whereNotIn('job_openings.priority',$job_status);
        $job_open_query = $job_open_query->where('job_openings.adler_career_checkbox','=','1');
        $job_open_query = $job_open_query->where('job_associate_candidates.deleted_at',NULL);
        $job_open_query = $job_open_query->groupBy('job_openings.id');
        $job_open_query = $job_open_query->orderBy('job_openings.updated_at','desc');
        $job_response = $job_open_query->get();
        $job_count = $job_response->count();

        return $job_count;
    }

    public static function getPriorityWiseApplicantJobsByClient($client_id,$priority,$limit=0,$offset=0,$search='',$order_column_name='',$type='') {

        $job_open_query = JobOpen::query();
        $job_open_query = $job_open_query->select(\DB::raw("COUNT(job_associate_candidates.candidate_id) as count"),'job_openings.id','job_openings.job_id','client_basicinfo.name as company_name','job_openings.no_of_positions','job_openings.posting_title','job_openings.city','job_openings.state','job_openings.country','job_openings.qualifications','job_openings.salary_from','job_openings.salary_to','job_openings.lacs_from','job_openings.thousand_from','job_openings.lacs_to','job_openings.thousand_to','industry.name as industry_name','job_openings.desired_candidate','job_openings.date_opened','job_openings.target_date','users.name as am_name','client_basicinfo.coordinator_name as coordinator_name','job_openings.priority','job_openings.hiring_manager_id','client_basicinfo.display_name','job_openings.created_at','job_openings.updated_at','job_openings.remote_working as remote_working');

        $job_open_query = $job_open_query->leftJoin('job_associate_candidates','job_openings.id','=','job_associate_candidates.job_id');
        $job_open_query = $job_open_query->join('client_basicinfo','client_basicinfo.id','=','job_openings.client_id');
        $job_open_query = $job_open_query->leftJoin('users','users.id','=','job_openings.hiring_manager_id');
        $job_open_query = $job_open_query->leftJoin('industry','industry.id','=','job_openings.industry_id');

        if ($priority == '-None-') {
            $job_open_query = $job_open_query->where('priority','=','0');
        } else if ($priority == 'Urgent Positions') {
            $job_open_query = $job_open_query->where('priority','=','1');
        } else if ($priority == 'New Positions') {
            $job_open_query = $job_open_query->where('priority','=','2');
        } else if ($priority == 'Constant Deliveries needed') {
            $job_open_query = $job_open_query->where('priority','=','3');
        } else if ($priority == 'On Hold') {
            $job_open_query = $job_open_query->where('priority','=','4');
        } else if ($priority == 'Revived Positions') {
            $job_open_query = $job_open_query->where('priority','=','5');
        } else if ($priority == 'Constant Deliveries needed for very old positions where many deliveries are done but no result yet') {
            $job_open_query = $job_open_query->where('priority','=','6');
        } else if ($priority == 'No Deliveries Needed') {
            $job_open_query = $job_open_query->where('priority','=','7');
        } else if ($priority == 'Identified candidates') {
            $job_open_query = $job_open_query->where('priority','=','8');
        } else if ($priority == 'Closed By Us') {
            $job_open_query = $job_open_query->where('priority','=','9');
        } else if ($priority == 'Closed By Client') {
            $job_open_query = $job_open_query->where('priority','=','10');
        }

        if (isset($order_column_name) && $order_column_name != '') {
            $job_open_query = $job_open_query->orderBy($order_column_name,$type);
        } else {
            $job_open_query = $job_open_query->orderBy('job_openings.updated_at','desc');
        }
        if (isset($limit) && $limit > 0) {
            $job_open_query = $job_open_query->limit($limit);
        }
        if (isset($offset) && $offset > 0) {
            $job_open_query = $job_open_query->offset($offset);
        }
        if (isset($search) && $search != '') {
            $job_open_query = $job_open_query->where(function($job_open_query) use ($search) {
                $job_open_query = $job_open_query->where('job_openings.posting_title','like',"%$search%");
                $job_open_query = $job_open_query->orwhere('job_openings.city','like',"%$search%");
                if(($search == 'Remote') || ($search == 'remote')) {
                    $job_open_query = $job_open_query->orwhere('job_openings.remote_working','=',"1");
                }
            });
        }

        $job_open_query = $job_open_query->where('job_associate_candidates.deleted_at',NULL);
        $job_open_query = $job_open_query->where('job_openings.adler_career_checkbox','=','1');
        $job_open_query = $job_open_query->groupBy('job_openings.id');
        $job_open_query = $job_open_query->where('job_openings.client_id',$client_id);
        $job_open_query = $job_open_query->orderBy('job_openings.updated_at','desc');
        $job_response = $job_open_query->get();

        $jobs_list = array();
        $colors = self::getJobPrioritiesColor();
        $i = 0;
        foreach ($job_response as $key => $value) {

            // value get in 2 decimal point
            if ($value->lacs_from >= '100') {
                $min_ctc = '100+';
            }
            else {
                $lacs_from = $value->lacs_from*100000;
                $thousand_from = $value->thousand_from*1000;
                $mictc = $lacs_from+$thousand_from;
                $minctc = $mictc/100000;
                $min_ctc = number_format($minctc,2);
            }

            if ($value->lacs_to >= '100') {
                $max_ctc = '100+';
            }
            else {
                $lacs_to = $value->lacs_to*100000;
                $thousand_to = $value->thousand_to*1000;
                $mactc = $lacs_to+$thousand_to;
                $maxctc = $mactc/100000;
                $max_ctc = number_format($maxctc,2);
            }

            $jobs_list[$i]['id'] = $value->id;
            $jobs_list[$i]['job_id'] = $value->job_id;
            $jobs_list[$i]['company_name'] = $value->company_name;
            $jobs_list[$i]['display_name'] = $value->display_name;
            $jobs_list[$i]['client'] = $value->company_name." - ".$value->coordinator_name;
            $jobs_list[$i]['no_of_positions'] = $value->no_of_positions;

            if (isset($value->level_name) && $value->level_name != '') {
                $jobs_list[$i]['posting_title'] = $value->level_name." - ".$value->posting_title;
            }
            else {
                $jobs_list[$i]['posting_title'] = $value->posting_title;
            }

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
            $jobs_list[$i]['hiring_manager_id'] = $value->hiring_manager_id;
            $jobs_list[$i]['associate_candidate_cnt'] = $value->count;
            $jobs_list[$i]['priority'] = $value->priority;
            $jobs_list[$i]['created_date'] = date('d-m-Y',strtotime($value->created_at));
            $jobs_list[$i]['updated_date'] = date('d-m-Y',strtotime($value->updated_at));

            if(isset($value->priority) && $value->priority!='')
                $jobs_list[$i]['color'] = $colors[$value->priority];
            else
                $jobs_list[$i]['color'] ='';

            $jobs_list[$i]['coordinator_name'] = $value->coordinator_name;
            $jobs_list[$i]['access'] = '0';

            if($value->hiring_manager_id == 0) {

                $jobs_list[$i]['am_name'] = 'Yet to Assign';
            }
            else {

                $jobs_list[$i]['am_name'] = $value->am_name;
            }

            $jobs_list[$i]['applicant_count'] = CandidateOtherInfo::getApplicantJobCvsCount($value->id);

            if($value->remote_working == '1') {

                $jobs_list[$i]['city'] = "Remote";
            }
            else {

                $jobs_list[$i]['city'] = $value->city;
            }

            $jobs_list[$i]['lacs_from'] = $value->lacs_from;
            $jobs_list[$i]['lacs_to'] = $value->lacs_to;
            
            $i++;
        }
        return $jobs_list;
    }

    public static function getPriorityWiseApplicantJobsByClientCount($client_id,$priority,$search='') {

        $job_open_query = JobOpen::query();
        $job_open_query = $job_open_query->select(\DB::raw("COUNT(job_associate_candidates.candidate_id) as count"),'job_openings.id','job_openings.job_id','client_basicinfo.name as company_name','job_openings.no_of_positions','job_openings.posting_title','job_openings.city','job_openings.state','job_openings.country','job_openings.qualifications','job_openings.salary_from','job_openings.salary_to','job_openings.lacs_from','job_openings.thousand_from','job_openings.lacs_to','job_openings.thousand_to','industry.name as industry_name','job_openings.desired_candidate','job_openings.date_opened','job_openings.target_date','users.name as am_name','client_basicinfo.coordinator_name as coordinator_name','job_openings.priority','job_openings.hiring_manager_id','client_basicinfo.display_name','job_openings.created_at','job_openings.updated_at','job_openings.remote_working as remote_working');

        $job_open_query = $job_open_query->leftJoin('job_associate_candidates','job_openings.id','=','job_associate_candidates.job_id');
        $job_open_query = $job_open_query->join('client_basicinfo','client_basicinfo.id','=','job_openings.client_id');
        $job_open_query = $job_open_query->leftJoin('users','users.id','=','job_openings.hiring_manager_id');
        $job_open_query = $job_open_query->leftJoin('industry','industry.id','=','job_openings.industry_id');

        if ($priority == '-None-') {
            $job_open_query = $job_open_query->where('priority','=','0');
        } else if ($priority == 'Urgent Positions') {
            $job_open_query = $job_open_query->where('priority','=','1');
        } else if ($priority == 'New Positions') {
            $job_open_query = $job_open_query->where('priority','=','2');
        } else if ($priority == 'Constant Deliveries needed') {
            $job_open_query = $job_open_query->where('priority','=','3');
        } else if ($priority == 'On Hold') {
            $job_open_query = $job_open_query->where('priority','=','4');
        } else if ($priority == 'Revived Positions') {
            $job_open_query = $job_open_query->where('priority','=','5');
        } else if ($priority == 'Constant Deliveries needed for very old positions where many deliveries are done but no result yet') {
            $job_open_query = $job_open_query->where('priority','=','6');
        } else if ($priority == 'No Deliveries Needed') {
            $job_open_query = $job_open_query->where('priority','=','7');
        } else if ($priority == 'Identified candidates') {
            $job_open_query = $job_open_query->where('priority','=','8');
        } else if ($priority == 'Closed By Us') {
            $job_open_query = $job_open_query->where('priority','=','9');
        } else if ($priority == 'Closed By Client') {
            $job_open_query = $job_open_query->where('priority','=','10');
        }

        if (isset($search) && $search != '') {
            $job_open_query = $job_open_query->where(function($job_open_query) use ($search) {
                $job_open_query = $job_open_query->where('job_openings.posting_title','like',"%$search%");
                $job_open_query = $job_open_query->orwhere('job_openings.city','like',"%$search%");
                if(($search == 'Remote') || ($search == 'remote')) {
                    $job_open_query = $job_open_query->orwhere('job_openings.remote_working','=',"1");
                }
            });
        }

        $job_open_query = $job_open_query->where('job_associate_candidates.deleted_at',NULL);
        $job_open_query = $job_open_query->where('job_openings.adler_career_checkbox','=','1');
        $job_open_query = $job_open_query->groupBy('job_openings.id');
        $job_open_query = $job_open_query->where('job_openings.client_id',$client_id);
        $job_open_query = $job_open_query->orderBy('job_openings.updated_at','desc');
        $job_response = $job_open_query->get();
        $job_count = $job_response->count();

        return $job_count;
    }

    public static function getAllApplicantJobsByCLient($client_id,$limit=0,$offset=0,$search=0,$order=NULL,$type='desc') {

        $job_onhold = getenv('ONHOLD');
        $job_client = getenv('CLOSEDBYCLIENT');
        $job_us = getenv('CLOSEDBYUS');
        $job_status = array($job_onhold,$job_us,$job_client);

        $job_open_query = JobOpen::query();
        $job_open_query = $job_open_query->select(\DB::raw("COUNT(job_associate_candidates.candidate_id) as count"),'job_openings.id','job_openings.job_id','client_basicinfo.name as company_name','job_openings.no_of_positions','job_openings.posting_title','job_openings.city','job_openings.state','job_openings.country','job_openings.qualifications','job_openings.salary_from','job_openings.salary_to','job_openings.lacs_from','job_openings.thousand_from','job_openings.lacs_to','job_openings.thousand_to','industry.name as industry_name','job_openings.desired_candidate','job_openings.date_opened','job_openings.target_date','users.name as am_name','client_basicinfo.coordinator_name as coordinator_name','job_openings.priority','job_openings.hiring_manager_id','client_basicinfo.display_name','job_openings.created_at','job_openings.remote_working as remote_working');

        $job_open_query = $job_open_query->leftJoin('job_associate_candidates','job_openings.id','=','job_associate_candidates.job_id');
        $job_open_query = $job_open_query->join('client_basicinfo','client_basicinfo.id','=','job_openings.client_id');
        $job_open_query = $job_open_query->join('users','users.id','=','job_openings.hiring_manager_id');

        $job_open_query = $job_open_query->leftJoin('industry','industry.id','=','job_openings.industry_id');
        $job_open_query = $job_open_query->whereNotIn('priority',$job_status);

        $job_open_query = $job_open_query->where('job_associate_candidates.deleted_at',NULL);
        $job_open_query = $job_open_query->where('job_openings.adler_career_checkbox','=','1');
        $job_open_query = $job_open_query->where('job_openings.client_id',$client_id);
        $job_open_query = $job_open_query->groupBy('job_openings.id');

        if (isset($order) && $order != '') {
            if ($order == 'job_openings.lacs_from') {
                $job_open_query = $job_open_query->orderBy($order,$type);
                $job_open_query = $job_open_query->orderBy('job_openings.thousand_from',$type);
            }
            else if ($order == 'job_openings.lacs_to') {
                $job_open_query = $job_open_query->orderBy($order,$type);
                $job_open_query = $job_open_query->orderBy('job_openings.thousand_to',$type);
            }
            else{
                $job_open_query = $job_open_query->orderBy($order,$type);
            }
        }

        if (isset($limit) && $limit > 0) {
            $job_open_query = $job_open_query->limit($limit);
        }
        if (isset($offset) && $offset > 0) {
            $job_open_query = $job_open_query->offset($offset);
        }
        if (isset($search) && $search != '') {

            $job_open_query = $job_open_query->where(function($job_open_query) use ($search) {

                $job_open_query = $job_open_query->where('job_openings.posting_title','like',"%$search%");
                $job_open_query = $job_open_query->orwhere('users.name','like',"%$search%");
                $job_open_query = $job_open_query->orwhere('client_basicinfo.display_name','like',"%$search%");
                $job_open_query = $job_open_query->orwhere('client_basicinfo.coordinator_name','like',"%$search%");
                $job_open_query = $job_open_query->orwhere('job_openings.no_of_positions','like',"%$search%");
                $job_open_query = $job_open_query->orwhere('job_openings.city','like',"%$search%");

                if(($search == 'Remote') || ($search == 'remote')) {
                    
                    $job_open_query = $job_open_query->orwhere('job_openings.remote_working','=',"1");
                }
            });
        }
        
        $job_response = $job_open_query->get();

        $jobs_list = array();
        $colors = self::getJobPrioritiesColor();
        $i = 0;
        foreach ($job_response as $key => $value) {

            // value get in 2 decimal point
            if ($value->lacs_from >= '100') {
                $min_ctc = '100+';
            }
            else {
                $lacs_from = $value->lacs_from*100000;
                $thousand_from = $value->thousand_from*1000;
                $mictc = $lacs_from+$thousand_from;
                $minctc = $mictc/100000;
                $min_ctc = number_format($minctc,2);
            }

            if ($value->lacs_to >= '100') {
                $max_ctc = '100+';
            }
            else {
                $lacs_to = $value->lacs_to*100000;
                $thousand_to = $value->thousand_to*1000;
                $mactc = $lacs_to+$thousand_to;
                $maxctc = $mactc/100000;
                $max_ctc = number_format($maxctc,2);
            }

            $jobs_list[$i]['id'] = $value->id;
            $jobs_list[$i]['job_id'] = $value->job_id;
            $jobs_list[$i]['company_name'] = $value->company_name;
            $jobs_list[$i]['display_name'] = $value->display_name;
            $jobs_list[$i]['client'] = $value->company_name." - ".$value->coordinator_name;
            $jobs_list[$i]['no_of_positions'] = $value->no_of_positions;

            if (isset($value->level_name) && $value->level_name != '') {
                $jobs_list[$i]['posting_title'] = $value->level_name." - ".$value->posting_title;
            }
            else {
                $jobs_list[$i]['posting_title'] = $value->posting_title;
            }

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
            $jobs_list[$i]['hiring_manager_id'] = $value->hiring_manager_id;
            $jobs_list[$i]['associate_candidate_cnt'] = $value->count;
            $jobs_list[$i]['priority'] = $value->priority;
            $jobs_list[$i]['created_date'] = date('d-m-Y',strtotime($value->created_at));
            $jobs_list[$i]['updated_date'] = date('d-m-Y',strtotime($value->updated_at));
            
            $jobs_list[$i]['coordinator_name'] = $value->coordinator_name;
            $jobs_list[$i]['access'] = '0';

            if($value->hiring_manager_id == 0) {

                $jobs_list[$i]['am_name'] = 'Yet to Assign';
            }
            else {

                $jobs_list[$i]['am_name'] = $value->am_name;
            }

            $jobs_list[$i]['applicant_count'] = CandidateOtherInfo::getApplicantJobCvsCount($value->id);

            if($value->remote_working == '1') {

                $jobs_list[$i]['city'] = "Remote";
            }
            else {

                $jobs_list[$i]['city'] = $value->city;
            }
            
            $i++;
        }
        return $jobs_list;
    }

    public static function getJobforOpentoAllOneTime() {

        $date = date('Y-m-d', strtotime('-150 days'));
        $date_time = date('Y-m-d H');

        $job_onhold = getenv('ONHOLD');
        $job_client = getenv('CLOSEDBYCLIENT');
        $job_us = getenv('CLOSEDBYUS');
        $job_status = array($job_onhold,$job_us,$job_client);

        $job = JobOpen::query();
        $job = $job->select('job_openings.id','job_openings.created_at');
        $job = $job->where('job_openings.open_to_all_date','>=',"$date");
        $job = $job->where('job_openings.open_to_all_date','<=',"$date_time");
        $job = $job->whereNotIn('priority',$job_status);
        $job = $job->where('open_to_all','=','0');
        $job = $job->where('job_open_checkbox','=','1');
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

    public static function getJobDetailsBySearchArea($key_skill,$desired_location,$min_experience,$max_experience,$min_ctc,$max_ctc,$limit=0,$offset=0) {

        $job_onhold = getenv('ONHOLD');
        $job_client = getenv('CLOSEDBYCLIENT');
        $job_us = getenv('CLOSEDBYUS');
        $job_status = array($job_onhold,$job_us,$job_client);

        $job_open_query = JobOpen::query();

        $job_open_query = $job_open_query->select('job_openings.id','client_basicinfo.name as company_name','job_openings.no_of_positions','job_openings.posting_title','job_openings.city','job_openings.state','job_openings.country','job_openings.qualifications','job_openings.lacs_from','job_openings.thousand_from','job_openings.lacs_to','job_openings.thousand_to','industry.name as industry_name','job_openings.desired_candidate','client_basicinfo.coordinator_name as coordinator_name','job_openings.job_description as job_description','job_openings.work_exp_from','job_openings.work_exp_to','job_openings.adler_job_disclosed_checkbox','job_openings.created_at as added_date','job_openings.priority','job_openings.adler_career_checkbox','job_openings.remote_working as remote_working','job_openings.updated_at as updated_at');
        
        $job_open_query = $job_open_query->join('client_basicinfo','client_basicinfo.id','=','job_openings.client_id');

        $job_open_query = $job_open_query->leftJoin('industry','industry.id','=','job_openings.industry_id');

        if (isset($limit) && $limit > 0) {
            $job_open_query = $job_open_query->limit($limit);
        }
        if (isset($offset) && $offset > 0) {
            $job_open_query = $job_open_query->offset($offset);
        }

        $job_open_query = $job_open_query->whereNotIn('priority',$job_status);
        $job_open_query = $job_open_query->where('adler_career_checkbox','=','1');

        if (isset($key_skill) && $key_skill != '') {

            $job_open_query = $job_open_query->where('job_openings.posting_title','=',"$key_skill");
        }
        if (isset($desired_location) && $desired_location != '') {

            if($desired_location == 'Remote') {

                $job_open_query = $job_open_query->where('job_openings.remote_working','=',"1");
            }
            else {

                $job_open_query = $job_open_query->where('job_openings.city','=',"$desired_location");
            }
        }

        if (isset($min_experience) && $min_experience != '') {

            $min_experience = (int)$min_experience;

            if($min_experience == '21') {

                $job_open_query = $job_open_query->where('job_openings.work_exp_from','>=',21);
            }
            else {
                $job_open_query = $job_open_query->where('job_openings.work_exp_from','>=',$min_experience);
            }
        }

        if (isset($max_experience) && $max_experience != '') {

            $max_experience = (int)$max_experience;
            
            if($max_experience == '21') {

                $job_open_query = $job_open_query->where('job_openings.work_exp_to','>=',21);   
            }
            else {
                $job_open_query = $job_open_query->where('job_openings.work_exp_to','<=',$max_experience);
            }
        }

        if (isset($min_ctc) && $min_ctc != '') {

            if($min_ctc == '30') {

                $job_open_query = $job_open_query->where('job_openings.lacs_from','>=',30);
            }
            else if($min_ctc == '0.5') {

                $job_open_query = $job_open_query->where('job_openings.lacs_from','=',0);
            }
            else {
                $job_open_query = $job_open_query->where('job_openings.lacs_from','=',$min_ctc);
            }
        }
        if (isset($max_ctc) && $max_ctc != '') {

            if($max_ctc == '30') {
                $job_open_query = $job_open_query->where('job_openings.lacs_to','>=',30);
            }
            else if($max_ctc == '0.5') {

                $job_open_query = $job_open_query->where('job_openings.lacs_to','=',0);
            }
            else {
                $job_open_query = $job_open_query->where('job_openings.lacs_to','=',$max_ctc);
            }
        }

        $job_open_query = $job_open_query->orderBy('job_openings.updated_at','desc');
        $job_open_query = $job_open_query->groupBy('job_openings.id');
        $job_response = $job_open_query->get();

        $jobs_list = array();
        $i=0;

        foreach ($job_response as $key => $value) {

            // value get in 2 decimal point
            if ($value->lacs_from >= '100') {
                $min_ctc = '100+';
            }
            else {
                $lacs_from = $value->lacs_from*100000;
                $thousand_from = $value->thousand_from*1000;
                $mictc = $lacs_from+$thousand_from;
                $minctc = $mictc/100000;
                $min_ctc = number_format($minctc,2);
            }

            if ($value->lacs_to >= '100') {
                $max_ctc = '100+';
            }
            else {
                $lacs_to = $value->lacs_to*100000;
                $thousand_to = $value->thousand_to*1000;
                $mactc = $lacs_to+$thousand_to;
                $maxctc = $mactc/100000;
                $max_ctc = number_format($maxctc,2);
            }

            $jobs_list[$i]['min_ctc'] = $min_ctc;
            $jobs_list[$i]['max_ctc'] = $max_ctc;

            $jobs_list[$i]['id'] = $value->id;
            $jobs_list[$i]['company_name'] = $value->company_name;
            $jobs_list[$i]['no_of_positions'] = $value->no_of_positions;
            $jobs_list[$i]['posting_title'] = $value->posting_title;

            $jobs_list[$i]['work_exp_from'] = $value->work_exp_from;
            $jobs_list[$i]['work_exp_to'] = $value->work_exp_to;

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

            $jobs_list[$i]['industry'] = $value->industry_name;
            $jobs_list[$i]['qualifications'] = $value->qualifications;
            $jobs_list[$i]['desired_candidate'] = $value->desired_candidate;

            $desc_len = strlen($value->job_description);

            if ($desc_len >= 70) {

                $desc = substr($value->job_description,0,70);
                $desc .= '[...]';
            }
            else {
                $desc = strip_tags($value->job_description);
            }

            $jobs_list[$i]['job_description'] = $desc;
            $jobs_list[$i]['adler_job_disclosed_checkbox'] = $value->adler_job_disclosed_checkbox;

            $jobs_list[$i]['added_date'] = date('d-m-Y',strtotime($value->added_date));

            $to_date = date('Y-m-d');

            $to = strtotime($to_date);
            $from = strtotime($value->updated_at);
            $diff_in_days = ($to - $from)/60/60/24;

            $jobs_list[$i]['diff_in_days'] = $diff_in_days;

            if($value->remote_working == '1') {

                $jobs_list[$i]['city'] = "Remote";
            }
            else {
                
                $jobs_list[$i]['city'] = $value->city;
            }

            $i++;
        }
        return $jobs_list;
    }

    public static function getJobsByMB($user_id) {

        $from_date = date('Y-m-d 00:00:00',strtotime("-1 days"));
        $to_date = date("Y-m-d 23:59:59", strtotime("-1 days"));

        $job_onhold = getenv('ONHOLD');
        $job_client = getenv('CLOSEDBYCLIENT');
        $job_us = getenv('CLOSEDBYUS');
        $job_status = array($job_onhold,$job_us,$job_client);

        $job_open_query = JobOpen::query();

        $job_open_query = $job_open_query->select('job_openings.id','job_openings.job_id','client_basicinfo.name as company_name','job_openings.no_of_positions','job_openings.posting_title','job_openings.city','job_openings.state','job_openings.country','job_openings.qualifications','job_openings.salary_from','job_openings.salary_to','job_openings.lacs_from','job_openings.thousand_from','job_openings.lacs_to','job_openings.thousand_to','job_openings.desired_candidate','job_openings.date_opened','job_openings.target_date','users.name as am_name','client_basicinfo.coordinator_name as coordinator_name','job_openings.priority','job_openings.hiring_manager_id','client_basicinfo.display_name','job_openings.created_at','job_openings.updated_at as updated_at','client_basicinfo.second_line_am as second_line_am','job_openings.remote_working as remote_working');

        $job_open_query = $job_open_query->join('client_basicinfo','client_basicinfo.id','=','job_openings.client_id');
        $job_open_query = $job_open_query->join('users','users.id','=','job_openings.hiring_manager_id');

        $job_open_query = $job_open_query->where('job_openings.hiring_manager_id','=',$user_id);
        $job_open_query = $job_open_query->whereNotIn('job_openings.priority',$job_status);
        $job_open_query = $job_open_query->where('job_openings.adler_career_checkbox','=','1');
        $job_open_query = $job_open_query->orderBy('job_openings.id','desc');
        $job_open_query = $job_open_query->groupBy('job_openings.id');

        $job_response = $job_open_query->get();

        $jobs_list = array();
        $colors = self::getJobPrioritiesColor();
        $i = 0;

        foreach ($job_response as $key => $value) {
            
            $jobs_list[$i]['id'] = $value->id;
            $jobs_list[$i]['job_id'] = $value->job_id;
            $jobs_list[$i]['company_name'] = $value->company_name;
            $jobs_list[$i]['client'] = $value->company_name." - ".$value->coordinator_name;
            $jobs_list[$i]['posting_title'] = $value->posting_title;
                    
            $location ='';
            if($value->city!='') {

                $location .= $value->city;
            }
            if($value->state!='') {

                if($location=='')
                    $location .= $value->state;
                else
                    $location .= ", ".$value->state;
            }
            if($value->country!='') {
                
                if($location=='')
                    $location .= $value->country;
                else
                    $location .= ", ".$value->country;
            }

            if($value->remote_working == '1') {

                $jobs_list[$i]['location'] = "Remote";
                $jobs_list[$i]['city'] = "Remote";
            }
            else {

                $jobs_list[$i]['location'] = $location;
                $jobs_list[$i]['city'] = $value->city;
            }

            $jobs_list[$i]['hiring_manager_id'] = $value->hiring_manager_id;

            $jobs_list[$i]['applicant_candidates'] = CandidateOtherInfo::getApplicantCandidatesByJobId($value->id,$from_date,$to_date);

            $i++;
        }
        return $jobs_list;
    }

    public static function getHiringManagerEmailByJobId($job_id) {

        $job_query = JobOpen::query();

        $job_query = $job_query->leftjoin('users','users.id','=','job_openings.hiring_manager_id');
        $job_query = $job_query->select('users.email as hiring_manager_email');
        $job_query = $job_query->where('job_openings.id', '=', $job_id);
        $job_response = $job_query->first();

        $hiring_manager_email = '';

        if(isset($job_response) && $job_response != '') {
            $hiring_manager_email = $job_response->hiring_manager_email;
        }
        else {
            $hiring_manager_email = '';
        }
        return $hiring_manager_email;
    }

    public static function getAllJobsCountByDepartment($all=0,$user_id,$search,$department_id=0) {

        $job_onhold = getenv('ONHOLD');
        $job_client = getenv('CLOSEDBYCLIENT');
        $job_us = getenv('CLOSEDBYUS');
        $job_status = array($job_onhold,$job_us,$job_client);

        $job_open_query = JobOpen::query();
        $job_open_query = $job_open_query->select(\DB::raw("COUNT(job_associate_candidates.candidate_id) as count"),'job_openings.id','job_openings.job_id','client_basicinfo.name as company_name','job_openings.no_of_positions','job_openings.posting_title','job_openings.city','job_openings.state','job_openings.country','job_openings.qualifications','job_openings.salary_from','job_openings.salary_to','job_openings.lacs_from','job_openings.thousand_from','job_openings.lacs_to','job_openings.thousand_to','job_openings.desired_candidate','job_openings.date_opened','job_openings.target_date','users.name as am_name','client_basicinfo.coordinator_name as coordinator_name','job_openings.priority','job_openings.hiring_manager_id','client_basicinfo.display_name','job_openings.created_at','job_openings.updated_at as updated_at','job_openings.remote_working as remote_working');

        $job_open_query = $job_open_query->leftJoin('job_associate_candidates','job_openings.id','=','job_associate_candidates.job_id');
        $job_open_query = $job_open_query->join('client_basicinfo','client_basicinfo.id','=','job_openings.client_id');
        $job_open_query = $job_open_query->join('users','users.id','=','job_openings.hiring_manager_id');

        // assign jobs to logged in user
        if($all==0) {
            $job_open_query = $job_open_query->join('job_visible_users','job_visible_users.job_id','=','job_openings.id');
            $job_open_query = $job_open_query->where('user_id','=',$user_id);
        }

        $job_open_query = $job_open_query->whereNotIn('job_openings.priority',$job_status);
        $job_open_query = $job_open_query->where('job_associate_candidates.deleted_at',NULL);
        $job_open_query = $job_open_query->groupBy('job_openings.id');

        if (isset($search) && $search != '') {

            $job_open_query = $job_open_query->where(function($job_open_query) use ($search) {

                $date_search = false;
                $date_array = explode("-",$search);
                if(isset($date_array) && sizeof($date_array)>0) {

                    $stamp = strtotime($search);
                    if (is_numeric($stamp)){
                        $month = date( 'm', $stamp );
                        $day   = date( 'd', $stamp );
                        $year  = date( 'Y', $stamp );

                        if(checkdate($month, $day, $year)){
                            $date_search = true;
                        }
                    }
                }

                $job_open_query = $job_open_query->where('job_openings.posting_title','like',"%$search%");
                $job_open_query = $job_open_query->orwhere('users.name','like',"%$search%");
                $job_open_query = $job_open_query->orwhere('client_basicinfo.display_name','like',"%$search%");
                $job_open_query = $job_open_query->orwhere('client_basicinfo.coordinator_name','like',"%$search%");
                $job_open_query = $job_open_query->orwhere('job_openings.no_of_positions','like',"%$search%");
                $job_open_query = $job_open_query->orwhere('job_openings.city','like',"%$search%");
                $job_open_query = $job_open_query->orwhere('job_openings.qualifications','like',"%$search%");

                if(($search == 'Remote') || ($search == 'remote')) {
                    
                    $job_open_query = $job_open_query->orwhere('job_openings.remote_working','=',"1");
                }

                if($date_search) {

                    $dateClass = new Date();
                    $search_string = $dateClass->changeDMYtoYMD($search);
                    $from_date = date("Y-m-d 00:00:00",strtotime($search_string));
                    $to_date = date("Y-m-d 23:59:59",strtotime($search_string));
                    $job_open_query = $job_open_query->orwhere('job_openings.created_at','>=',"$from_date");
                    $job_open_query = $job_open_query->Where('job_openings.created_at','<=',"$to_date");
                }
            });
        }

        // For Department
        if (isset($department_id) && $department_id > 0) {
            $job_open_query = $job_open_query->where('client_basicinfo.department_id','=',$department_id);
        }

        $job_response = $job_open_query->get();

        return sizeof($job_response);
    }

    public static function getAllJobsByDepartment($all=0,$user_id,$limit=0,$offset=0,$search=0,$order=NULL,$type='desc',$department_id=0) {

        $job_onhold = getenv('ONHOLD');
        $job_client = getenv('CLOSEDBYCLIENT');
        $job_us = getenv('CLOSEDBYUS');
        $job_status = array($job_onhold,$job_us,$job_client);

        $job_open_query = JobOpen::query();

        $job_open_query = $job_open_query->select(\DB::raw("COUNT(job_associate_candidates.candidate_id) as count"),'job_openings.id','job_openings.job_id','client_basicinfo.name as company_name','job_openings.no_of_positions','job_openings.posting_title','job_openings.city','job_openings.state','job_openings.country','job_openings.qualifications','job_openings.salary_from','job_openings.salary_to','job_openings.lacs_from','job_openings.thousand_from','job_openings.lacs_to','job_openings.thousand_to','job_openings.desired_candidate','job_openings.date_opened','job_openings.target_date','users.name as am_name','client_basicinfo.coordinator_name as coordinator_name','job_openings.priority','job_openings.hiring_manager_id','client_basicinfo.display_name','job_openings.created_at','job_openings.updated_at as updated_at','client_basicinfo.second_line_am as second_line_am','job_openings.remote_working as remote_working');
        
        $job_open_query = $job_open_query->leftJoin('job_associate_candidates','job_openings.id','=','job_associate_candidates.job_id');
        $job_open_query = $job_open_query->join('client_basicinfo','client_basicinfo.id','=','job_openings.client_id');
        $job_open_query = $job_open_query->join('users','users.id','=','job_openings.hiring_manager_id');

        // assign jobs to logged in user
        if($all==0) {
            $job_open_query = $job_open_query->join('job_visible_users','job_visible_users.job_id','=','job_openings.id');
            $job_open_query = $job_open_query->where('job_visible_users.user_id','=',$user_id);
        }

        // For Department
        if (isset($department_id) && $department_id > 0) {
            $job_open_query = $job_open_query->where('client_basicinfo.department_id','=',$department_id);
        }
        
        $job_open_query = $job_open_query->whereNotIn('job_openings.priority',$job_status);
        $job_open_query = $job_open_query->where('job_associate_candidates.deleted_at',NULL);
        $job_open_query = $job_open_query->groupBy('job_openings.id');

        if (isset($order) && $order != '') {
            if ($order == 'job_openings.lacs_from') {
                $job_open_query = $job_open_query->orderBy($order,$type);
                $job_open_query = $job_open_query->orderBy('job_openings.thousand_from',$type);
            }
            else if ($order == 'job_openings.lacs_to') {
                $job_open_query = $job_open_query->orderBy($order,$type);
                $job_open_query = $job_open_query->orderBy('job_openings.thousand_to',$type);
            }
            else{
                $job_open_query = $job_open_query->orderBy($order,$type);
            }
        }

        if (isset($limit) && $limit > 0) {
            $job_open_query = $job_open_query->limit($limit);
        }
        if (isset($offset) && $offset > 0) {
            $job_open_query = $job_open_query->offset($offset);
        }

        if (isset($search) && $search != '') {

            $job_open_query = $job_open_query->where(function($job_open_query) use ($search) {

                $date_search = false;
                $date_array = explode("-",$search);
                if(isset($date_array) && sizeof($date_array)>0){
                    $stamp = strtotime($search);
                    if (is_numeric($stamp)){
                        $month = date( 'm', $stamp );
                        $day   = date( 'd', $stamp );
                        $year  = date( 'Y', $stamp );

                        if(checkdate($month, $day, $year)){
                            $date_search = true;
                        }
                    }
                }
                $job_open_query = $job_open_query->where('job_openings.posting_title','like',"%$search%");
                $job_open_query = $job_open_query->orwhere('users.name','like',"%$search%");
                $job_open_query = $job_open_query->orwhere('client_basicinfo.display_name','like',"%$search%");
                $job_open_query = $job_open_query->orwhere('client_basicinfo.coordinator_name','like',"%$search%");
                $job_open_query = $job_open_query->orwhere('job_openings.no_of_positions','like',"%$search%");
                $job_open_query = $job_open_query->orwhere('job_openings.city','like',"%$search%");
                $job_open_query = $job_open_query->orwhere('job_openings.qualifications','like',"%$search%");

                if(($search == 'Remote') || ($search == 'remote')) {
                    
                    $job_open_query = $job_open_query->orwhere('job_openings.remote_working','=',"1");
                }

                if($date_search) {

                    $dateClass = new Date();
                    $search_string = $dateClass->changeDMYtoYMD($search);
                    $from_date = date("Y-m-d 00:00:00",strtotime($search_string));
                    $to_date = date("Y-m-d 23:59:59",strtotime($search_string));
                    $job_open_query = $job_open_query->orwhere('job_openings.created_at','>=',"$from_date");
                    $job_open_query = $job_open_query->Where('job_openings.created_at','<=',"$to_date");
                }
            });
        }

        $job_response = $job_open_query->get();

        $jobs_list = array();
        $colors = self::getJobPrioritiesColor();
        $i = 0;

        foreach ($job_response as $key => $value) {

            // value get in 2 decimal point
            if ($value->lacs_from >= '100') {
                $min_ctc = '100+';
            }
            else {
                $lacs_from = $value->lacs_from*100000;
                $thousand_from = $value->thousand_from*1000;
                $mictc = $lacs_from+$thousand_from;
                $minctc = $mictc/100000;
                $min_ctc = number_format($minctc,2);
            }

            if ($value->lacs_to >= '100') {
                $max_ctc = '100+';
            }
            else {
                $lacs_to = $value->lacs_to*100000;
                $thousand_to = $value->thousand_to*1000;
                $mactc = $lacs_to+$thousand_to;
                $maxctc = $mactc/100000;
                $max_ctc = number_format($maxctc,2);
            }
            
            $jobs_list[$i]['id'] = $value->id;
            $jobs_list[$i]['job_id'] = $value->job_id;
            $jobs_list[$i]['company_name'] = $value->company_name;
            $jobs_list[$i]['display_name'] = $value->display_name;
            $jobs_list[$i]['client'] = $value->company_name." - ".$value->coordinator_name;
            $jobs_list[$i]['no_of_positions'] = $value->no_of_positions;

            if (isset($value->level_name) && $value->level_name != '') {
                $jobs_list[$i]['posting_title'] = $value->level_name." - ".$value->posting_title;
            }
            else {
                $jobs_list[$i]['posting_title'] = $value->posting_title;
            }
            
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
            $jobs_list[$i]['desired_candidate'] = $value->desired_candidate;
            $jobs_list[$i]['associate_candidate_cnt'] = JobAssociateCandidates::getJobAssociatedCvsCount($value->id);
            $jobs_list[$i]['priority'] = $value->priority;
            $jobs_list[$i]['created_date'] = date('d-m-Y',strtotime($value->created_at));
            $jobs_list[$i]['updated_date'] = date('d-m-Y',strtotime($value->updated_at));

            if(isset($value->priority) && $value->priority!='')
                $jobs_list[$i]['color'] = $colors[$value->priority];
            else
                $jobs_list[$i]['color'] ='';

            if($all==1) {
                $jobs_list[$i]['access'] = '1';
                $jobs_list[$i]['coordinator_name'] = $value->coordinator_name;
            }
            else {
                if(isset($value->hiring_manager_id) && $value->hiring_manager_id == $user_id) {
                    $jobs_list[$i]['coordinator_name'] = $value->coordinator_name;
                    $jobs_list[$i]['access'] = '1';
                }
                else if(isset($value->second_line_am) && $value->second_line_am == $user_id) {
                    $jobs_list[$i]['coordinator_name'] = $value->coordinator_name;
                    $jobs_list[$i]['access'] = '1';
                }
                else{
                    $jobs_list[$i]['coordinator_name'] = '';
                    $jobs_list[$i]['access'] = '0';
                }
            }

            if($value->hiring_manager_id == 0) {

                $jobs_list[$i]['am_name'] = 'Yet to Assign';
            }
            else {

                $jobs_list[$i]['am_name'] = $value->am_name;
            }

            if($value->remote_working == '1') {

                $jobs_list[$i]['city'] = "Remote";
            }
            else {

                $jobs_list[$i]['city'] = $value->city;
            } 

            $i++;
        }
        return $jobs_list;
    }

    public static function getJobsByClientStatus() {

        //Get Only Active & Passive Clients
        $status_array = array(0,1);

        $query = JobOpen::query();
        $query = $query->join('client_basicinfo','client_basicinfo.id','=','job_openings.client_id');
        $query = $query->select('job_openings.id','job_openings.client_id',\DB::raw("MAX(job_openings.created_at) as created_at"),'client_basicinfo.status as client_status','client_basicinfo.created_at as client_created_at');
        $query = $query->whereIn('client_basicinfo.status',$status_array);
        $query = $query->where('client_basicinfo.delete_client','=',0);
        $query = $query->groupBy('job_openings.client_id');
        $response = $query->get();

        $jobs = array();
        $i = 0;

        foreach ($response as $key => $value) {

            $jobs[$i]['job_id'] = $value->id;
            $jobs[$i]['client_id'] = $value->client_id;
            $jobs[$i]['created_at'] = $value->created_at;
            $jobs[$i]['client_status'] = $value->client_status;
            $jobs[$i]['client_created_at'] = $value->client_created_at;

            $i++;
        }
        return $jobs;
    }

    public static function getJobDetailsById($job_ids) {

        $job_query = JobOpen::query();

        $job_query = $job_query->join('client_basicinfo','client_basicinfo.id','=','job_openings.client_id');
        $job_query = $job_query->leftjoin('users','users.id','=','job_openings.hiring_manager_id');
        $job_query = $job_query->select('job_openings.posting_title as posting_title','job_openings.client_id as client_id','job_openings.remote_working as remote_working','job_openings.city as city','client_basicinfo.name as client_name','client_basicinfo.coordinator_name as contact_person','users.name as user_name');

        $job_query = $job_query->whereIn('job_openings.id',$job_ids);
        $job_response = $job_query->get();

        $job_details = array();
        $i=0;

        if(isset($job_response) && $job_response != '') {

            foreach ($job_response as $key => $value) {
                
                $job_details[$i]['client_id'] = $value->client_id;
                $job_details[$i]['company_name'] = $value->client_name;
                $job_details[$i]['contact_person'] = $value->contact_person;
                $job_details[$i]['posting_title'] = $value->posting_title;
                $job_details[$i]['user_name'] = $value->user_name;

                if($value->remote_working == '1') {
                    $job_details[$i]['city'] = "Remote";
                }
                else {
                    $job_details[$i]['city'] = $value->city;
                }

                $i++;
            }
        }
        return $job_details;
    }
}