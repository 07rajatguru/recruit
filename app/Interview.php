<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Interview extends Model
{
    //
    public $table = "interview";

    public static $rules = array(
        //'interview_name' => 'required',
        'candidate_id' => 'required',
        'interview_date' => 'required',
    );

    public function messages()
    {
        return [
            //'interview_name.required' => 'Interview Name is required field',
            'candidate_id.required' => 'Candidate is required field',
            'interview_date.required' => 'Interview Date is required field'
        ];
    }

    public static function createInterview($data){
        $interview = new Interview();
        $interview->interview_name = '';
        $interview->candidate_id = $data['candidate_id'];
       // $interview->client_id = $data['client'];
        $interview->posting_title = $data['posting_title'];

        if(isset($data['interviewer_id']) && $data['interviewer_id']!='')
            $interview->interviewer_id = $data['interviewer_id'];

        $interview->type = $data['type'];
        $interview->interview_date = $data['interview_date'];
        //$interview->to = $to;
        $interview->location = $data['location'];
        $interview->status = $data['status'];
        $interview->about = $data['about'];
        $interview->comments = $data['comments'];
        $interview->interview_owner_id = $data['interview_owner_id'];
        if (isset($data['skype_id']) && $data['skype_id'] != '') {
            $interview->skype_id = $data['skype_id'];
        }
        $interview->select_round = $data['round'];
        $interview->candidate_location = $data['candidate_location'];

        return $interview;
    }

    public static function getTypeArray(){

        $typeArray = array('' => 'Select Interview Type');
        $typeArray['Telephonic Interview'] = 'Telephonic Interview';
        $typeArray['General Interview'] = 'Skype Interview';
        $typeArray['Personal Interview'] = 'Personal Interview';

        return $typeArray;
    }

    public static function getInterviewStatus() {
        /*$interviewStatus['Selected'] = 'Selected';
        $interviewStatus['Rejected'] = 'Rejected';
        $interviewStatus['OnHold'] = 'OnHold';*/

        $interviewStatus['Yes'] = 'Yes';
        $interviewStatus['No'] = 'No';
        $interviewStatus['Attended'] = 'Attended';
        $interviewStatus['Not Attended'] = 'Not Attended';

        return $interviewStatus;
    }

    public static function getSelectRound(){

        $interviewround['1'] = 'Round 1';
        $interviewround['2'] = 'Round 2';
        $interviewround['3'] = 'Final Round';

        return $interviewround;
    }

    public static function getAllInterviews($all=0,$user_id){

        $query = Interview::query();
        $query = $query->join('candidate_basicinfo','candidate_basicinfo.id','=','interview.candidate_id');
        $query = $query->join('candidate_otherinfo','candidate_otherinfo.candidate_id','=','candidate_basicinfo.id');
        $query = $query->join('users','users.id','=','candidate_otherinfo.owner_id');
        $query = $query->join('job_openings','job_openings.id','=','interview.posting_title');
        $query = $query->join('client_basicinfo','client_basicinfo.id','=','job_openings.client_id');
        //$query = $query->leftJoin('users','users.id','=','interview.interviewer_id');
        $query = $query->select('interview.id as id','interview.location', 'interview.interview_name as interview_name','interview.interview_date','interview.status','client_basicinfo.name as client_name','interview.candidate_id as candidate_id', 'candidate_basicinfo.full_name as candidate_fname','candidate_basicinfo.lname as candidate_lname', 'interview.posting_title as posting_title_id','job_openings.posting_title as posting_title', 'job_openings.city as city','candidate_basicinfo.mobile as contact','users.name as candidate_owner');
        $query = $query->orderby('interview.interview_date','desc');

        if($all==0){
            $query = $query->where(function($query) use ($user_id){
                $query = $query->where('client_basicinfo.account_manager_id',$user_id);
                $query = $query->orwhere('candidate_otherinfo.owner_id',$user_id);
                $query = $query->orwhere('interviewer_id',$user_id);
            });
        }

        $response = $query->get();

        $interview = array();
        $i=0;
        foreach ($response as $key => $value) {
            $interview[$i]['id'] = $value->id;
            $interview[$i]['client_name'] = $value->client_name;
            $interview[$i]['posting_title'] = $value->posting_title;
            $interview[$i]['city'] = $value->city;
            $interview[$i]['candidate_fname'] = $value->candidate_fname;
            $interview[$i]['contact'] = $value->contact;
            $interview[$i]['interview_date'] = $value->interview_date;
            $interview[$i]['interview_date_ts'] = strtotime($value->interview_date);
            $interview[$i]['location'] = $value->location;
            $interview[$i]['status'] = $value->status;
            $interview[$i]['candidate_owner'] = $value->candidate_owner;
            $i++;
        }

        return $interview;
    }

    // function for today, tomorrow, this week & Upcoming/Previous interview page
    public static function getInterviewsByTime($all=0,$user_id,$time){

        $query = Interview::query();
        $query = $query->join('candidate_basicinfo','candidate_basicinfo.id','=','interview.candidate_id');
        $query = $query->join('candidate_otherinfo','candidate_otherinfo.candidate_id','=','candidate_basicinfo.id');
        $query = $query->join('users','users.id','=','candidate_otherinfo.owner_id');
        $query = $query->join('job_openings','job_openings.id','=','interview.posting_title');
        $query = $query->join('client_basicinfo','client_basicinfo.id','=','job_openings.client_id');
        //$query = $query->leftJoin('users','users.id','=','interview.interviewer_id');
        $query = $query->select('interview.id as id','interview.location', 'interview.interview_name as interview_name','interview.interview_date','interview.status','client_basicinfo.name as client_name','interview.candidate_id as candidate_id', 'candidate_basicinfo.full_name as candidate_fname','candidate_basicinfo.lname as candidate_lname', 'interview.posting_title as posting_title_id','job_openings.posting_title as posting_title', 'job_openings.city as city','candidate_basicinfo.mobile as contact','users.name as candidate_owner');
        $query = $query->orderby('interview.interview_date','desc');

        if($all==0){
            $query = $query->where(function($query) use ($user_id){
                $query = $query->where('client_basicinfo.account_manager_id',$user_id);
                $query = $query->orwhere('candidate_otherinfo.owner_id',$user_id);
                $query = $query->orwhere('interviewer_id',$user_id);
            });
        }
        if ($time == 'today') {
            $today = date("Y-m-d");
            $query = $query->where('interview_date','like',"%$today%");
        }
        if ($time == 'tomorrow') {
            $tomorrow = date("Y-m-d",strtotime('tomorrow'));
            $query = $query->where('interview_date','like',"%$tomorrow%");
        }
        if ($time == 'thisweek'){
            $from_date = date("Y-m-d", strtotime('this week'));
            $to_date = date("Y-m-d",strtotime("$from_date +6 days"));
            $query = $query->where('interview_date','>',"$from_date");
            $query = $query->where('interview_date','<',"$to_date");
        }
        if ($time == 'upcomingprevious') {
            $from_date = date("Y-m-d", strtotime('this week'));
            $to_date = date("Y-m-d",strtotime("$from_date +6 days"));

            $query = $query->where('interview_date','<',"$from_date");
            $query = $query->orwhere('interview_date','>',"$to_date");
        }

        $response = $query->get();

        $interview = array();
        $i=0;
        foreach ($response as $key => $value) {
            $interview[$i]['id'] = $value->id;
            $interview[$i]['client_name'] = $value->client_name;
            $interview[$i]['posting_title'] = $value->posting_title;
            $interview[$i]['city'] = $value->city;
            $interview[$i]['candidate_fname'] = $value->candidate_fname;
            $interview[$i]['contact'] = $value->contact;
            $interview[$i]['interview_date'] = $value->interview_date;
            $interview[$i]['interview_date_ts'] = strtotime($value->interview_date);
            $interview[$i]['location'] = $value->location;
            $interview[$i]['status'] = $value->status;
            $interview[$i]['candidate_owner'] = $value->candidate_owner;
            $i++;
        }

        return $interview;
    }

    public static function getTodayTomorrowsInterviews($all=0,$user_id){

        $from_date = date("Y-m-d 00:00:00");
        $to_date = date("Y-m-d 23:59:59", time() + 86400);

        $query = Interview::query();
        $query = $query->join('candidate_basicinfo','candidate_basicinfo.id','=','interview.candidate_id');
        $query = $query->join('candidate_otherinfo','candidate_otherinfo.candidate_id','=','candidate_basicinfo.id');
        $query = $query->join('job_openings','job_openings.id','=','interview.posting_title');
        $query = $query->join('client_basicinfo','client_basicinfo.id','=','job_openings.client_id');
        $query = $query->leftJoin('users','users.id','=','interview.interviewer_id');
        $query = $query->select('interview.id as id','interview.location', 'interview.interview_name as interview_name','interview.interview_date','interview.status',
            'client_basicinfo.name as client_name','interview.candidate_id as candidate_id', 'candidate_basicinfo.full_name as candidate_fname',
            'candidate_basicinfo.lname as candidate_lname', 'interview.posting_title as posting_title_id',
            'job_openings.posting_title as posting_title', 'job_openings.city as city','candidate_basicinfo.mobile as contact');
        $query = $query->orderby('interview.interview_date','desc');

        if($all==0){
            $query = $query->where(function($query) use ($user_id){
                $query = $query->where('client_basicinfo.account_manager_id',$user_id);
                $query = $query->orwhere('candidate_otherinfo.owner_id',$user_id);
                $query = $query->orwhere('interviewer_id',$user_id);
            });
        }

        $query = $query->where('interview_date','>',"$from_date");
        $query = $query->where('interview_date','<',"$to_date");
        $query = $query->orderby('interview.interview_date','asc');

        $response = $query->get();

        return $response;
    }

    public static function getAttendedInterviews($all=0,$user_id){

        $month = date('m');

        $query = Interview::query();
        $query = $query->join('candidate_basicinfo','candidate_basicinfo.id','=','interview.candidate_id');
        $query = $query->join('candidate_otherinfo','candidate_otherinfo.candidate_id','=','candidate_basicinfo.id');
        $query = $query->join('job_openings','job_openings.id','=','interview.posting_title');
        $query = $query->join('client_basicinfo','client_basicinfo.id','=','job_openings.client_id');
        $query = $query->leftJoin('users','users.id','=','interview.interviewer_id');
        $query = $query->select('interview.id as id','interview.location', 'interview.interview_name as interview_name','interview.interview_date','interview.status',
            'client_basicinfo.name as client_name','interview.candidate_id as candidate_id', 'candidate_basicinfo.full_name as candidate_fname',
            'candidate_basicinfo.lname as candidate_lname', 'interview.posting_title as posting_title_id',
            'job_openings.posting_title as posting_title', 'job_openings.city as city','candidate_basicinfo.mobile as contact');
        $query = $query->where('interview.status','=','Attended');
        $query = $query->where(\DB::raw('MONTH(interview_date)'),'=',$month);
        $query = $query->orderby('interview.interview_date','desc');
    
        if($all==0){
            $query = $query->where('interview_owner_id',$user_id);
        }
        $response = $query->get();

        return $response;
    }

    public static function getDashboardInterviews($all=0,$user_id){

        date_default_timezone_set("Asia/kolkata");
        $from_date = date("Y-m-d H:i:s");
        $to_date = date("Y-m-d 23:59:59", time() + 86400);

        $query = Interview::query();
        $query = $query->join('candidate_basicinfo','candidate_basicinfo.id','=','interview.candidate_id');
        $query = $query->join('candidate_otherinfo','candidate_otherinfo.candidate_id','=','candidate_basicinfo.id');
        $query = $query->join('job_openings','job_openings.id','=','interview.posting_title');
        $query = $query->join('client_basicinfo','client_basicinfo.id','=','job_openings.client_id');
        $query = $query->leftJoin('users','users.id','=','interview.interviewer_id');
        $query = $query->select('interview.id as id','interview.location', 'interview.interview_name as interview_name','interview.interview_date',
            'client_basicinfo.name as client_name','interview.candidate_id as candidate_id', 'candidate_basicinfo.full_name as candidate_fname',
            'candidate_basicinfo.lname as candidate_lname', 'interview.posting_title as posting_title_id',
            'job_openings.posting_title as posting_title','job_openings.city','candidate_basicinfo.mobile as contact');

        if($all==0){
            $query = $query->where(function($query) use ($user_id){
                $query = $query->where('client_basicinfo.account_manager_id',$user_id);
                $query = $query->orwhere('candidate_otherinfo.owner_id',$user_id);
                $query = $query->orwhere('interviewer_id',$user_id);
            });
        }

        $query = $query->where('interview_date','>',"$from_date");
        $query = $query->where('interview_date','<',"$to_date");
        $query = $query->orderby('interview.interview_date','asc');

        $response = $query->get();
//print_r($response);exit;
        return $response;
    }
    public static function getTodaysInterviews($all=0,$user_id){

        $from_date = date("Y-m-d 00:00:00");
        $to_date = date("Y-m-d 23:59:59");

        $query = Interview::query();
        $query = $query->join('candidate_basicinfo','candidate_basicinfo.id','=','interview.candidate_id');
        $query = $query->join('job_openings','job_openings.id','=','interview.posting_title');
        $query = $query->join('client_basicinfo','client_basicinfo.id','=','job_openings.client_id');
        $query = $query->leftJoin('users','users.id','=','interview.interviewer_id');
        $query = $query->select('interview.id as id','interview.location', 'interview.interview_name as interview_name','interview.interview_date',
            'client_basicinfo.name as client_name','interview.candidate_id as candidate_id', 'candidate_basicinfo.full_name as candidate_fname',
            'candidate_basicinfo.lname as candidate_lname', 'interview.posting_title as posting_title_id',
            'job_openings.posting_title as posting_title');

        if($all==0){
            $query = $query->where('interviewer_id',$user_id);
        }

        $query = $query->where('interview_date','>',"$from_date");
        $query = $query->where('interview_date','<',"$to_date");

        $response = $query->get();

        return $response;
    }

    public static function getInterviewsByIds($ids){
        $query = Interview::query();
        $query = $query->join('candidate_basicinfo','candidate_basicinfo.id','=','interview.candidate_id');
        $query = $query->join('job_openings','job_openings.id','=','interview.posting_title');
        $query = $query->join('client_basicinfo','client_basicinfo.id','=','job_openings.client_id');
        $query = $query->leftJoin('users','users.id','=','interview.interviewer_id');
        $query = $query->select('interview.id as id','interview.location', 'interview.interview_name as interview_name','interview.interview_date',
            'client_basicinfo.name as client_name','interview.candidate_id as candidate_id', 'candidate_basicinfo.full_name as candidate_fname',
            'candidate_basicinfo.lname as candidate_lname', 'interview.posting_title as posting_title_id',
            'job_openings.posting_title as posting_title');
        $query = $query->whereIn('interview.id',$ids);
        $response = $query->get();

        return $response;
    }

    public static function getDailyReportInterview($user_id,$date=NULL){
        $from_date = date("Y-m-d 00:00:00");
        $to_date = date("Y-m-d 23:59:59");

        $query = Interview::query();
        $query = $query->join('job_openings','job_openings.id','=','interview.posting_title');
        $query = $query->join('candidate_basicinfo','candidate_basicinfo.id','=','interview.candidate_id');
        $query = $query->select('job_openings.posting_title as posting_title','job_openings.city as location','interview.interview_date as date', 'interview.location as interview_location','interview.type as interview_type','candidate_basicinfo.full_name as cname','candidate_basicinfo.city as ccity','candidate_basicinfo.mobile as cmobile','candidate_basicinfo.email as cemail');
        $query = $query->where('interview.interview_owner_id','=',$user_id);

        if ($date == NULL) {
            $query = $query->where('interview_date','>',"$from_date");
            $query = $query->where('interview_date','<',"$to_date");
        }

        if ($date != '') {
            $query = $query->where(\DB::raw('date(interview_date)'),$date);
        }

        $interview_res = $query->get();

        $response = array();
        $i = 0;
        foreach ($interview_res as $key => $value) {
            $response[$i]['posting_title'] = $value->posting_title;
            $response[$i]['location'] = $value->location;
            $datearray = explode(' ', $value->date);
            $response[$i]['interview_date'] = $datearray[0];
            $response[$i]['interview_time'] = $datearray[1];
            $response[$i]['interview_location'] = $value->interview_location;
            $response[$i]['interview_type'] = $value->interview_type;
            $response[$i]['cname'] = $value->cname;
            $response[$i]['ccity'] = $value->ccity;
            $response[$i]['cmobile'] = $value->cmobile;
            $response[$i]['cemail'] = $value->cemail;
            $i++;
        }

       // print_r($response);exit;
        return $response;


    }

    public static function getWeeklyReportInterview($user_id,$from_date=NULL,$to_date=NULL){

        $date = date('Y-m-d',strtotime('Monday this week'));

        $query = Interview::query();
        $query = $query->select(\DB::raw("COUNT(interview.candidate_id) as count"),'interview.interview_date as interview_date');
        $query = $query->where('interview.interview_owner_id',$user_id);

        if ($from_date == NULL && $to_date == NULL) {
            $query = $query->where('interview.interview_date','>=',date('Y-m-d',strtotime('Monday this week')));
            $query = $query->where('interview.interview_date','<=',date('Y-m-d',strtotime("$date +6days")));
        }

        if ($from_date != '' && $from_date != '') {
            $query = $query->where(\DB::raw('date(interview.interview_date)'),'>=',$from_date);
            $query = $query->where(\DB::raw('date(interview.interview_date)'),'<=',$to_date);
        }

        $query = $query->groupBy(\DB::raw('Date(interview.interview_date)'));
        $query_response = $query->get();

        $response['interview_data'] = array();
        $i = 0;
        $cnt= 0;
        foreach ($query_response as $key => $value) {
            $cnt += $value->count;
            $datearry = explode(' ', $value->interview_date);
            $response['interview_data'][$i]['interview_date'] = $datearry[0];
            $response['interview_data'][$i]['interview_daily_count'] = $value->count;
            $i++;
        }
        $response['interview_cnt'] = $cnt;
        //print_r($response);exit;
        return $response;  


    }

    public static function getUserWiseMonthlyReportInterview($users,$month,$year){

        $u_keys = array_keys($users);

        $query = Interview::query();
        $query = $query->select(\DB::raw("COUNT(interview.candidate_id) as count"),'interview.interview_owner_id');
        $query = $query->whereIn('interview.interview_owner_id',$u_keys);

        if ($month != '' && $year != '') {
            $query = $query->where(\DB::raw('month(interview.interview_date)'),'=',$month);
            $query = $query->where(\DB::raw('year(interview.interview_date)'),'=',$year);
        }

        $query = $query->where('status','like','Attended');
        $query = $query->groupBy('interview.interview_owner_id');

        $query_response = $query->get();

        $interview_count = array();
        if($query_response->count()>0){
            foreach ($query_response  as $k=>$v){
                $interview_count[$v->interview_owner_id] = $v->count;
            }
        }

        return $interview_count;
    }

    public static function getMonthlyReportInterview($user_id,$month,$year){

        $query = Interview::query();
        $query = $query->select(\DB::raw("COUNT(interview.candidate_id) as count"),'interview.interview_date as interview_date');
        $query = $query->where('interview.interview_owner_id',$user_id);

        if ($month != '' && $year != '') {
            $query = $query->where(\DB::raw('month(interview.interview_date)'),'=',$month);
            $query = $query->where(\DB::raw('year(interview.interview_date)'),'=',$year);
        }

        $query = $query->where('status','like','Attended');
        $query = $query->groupBy(\DB::raw('Date(interview.interview_date)'));
        $query_response = $query->get();

        $response['interview_data'] = array();
        $i = 0;
        $cnt= 0;
        foreach ($query_response as $key => $value) {
            $cnt += $value->count;
            $datearry = explode(' ', $value->interview_date);
            $response['interview_data'][$i]['interview_date'] = $datearry[0];
            $response['interview_data'][$i]['interview_daily_count'] = $value->count;
            $i++;
        }
        $response['interview_cnt'] = $cnt;

        return $response;  
    }

    public static function getInterviewids($interview_id){

        $interviewDetails = Interview::join('candidate_basicinfo','candidate_basicinfo.id','=','interview.candidate_id')
            ->join('candidate_otherinfo','candidate_otherinfo.candidate_id','=','candidate_basicinfo.id')
            ->join('job_openings','job_openings.id','=','interview.posting_title')
            ->join('client_basicinfo','client_basicinfo.id','=','job_openings.client_id')
            ->select('candidate_otherinfo.owner_id as candidate_owner_id','client_basicinfo.account_manager_id as client_owner_id')
            ->where('interview.id','=',$interview_id)
            ->first();

            return $interviewDetails;
    }

    public static function getCandidateOwnerEmail($interview_id){

        $query = Interview::query();
        $query = $query->join('candidate_otherinfo','candidate_otherinfo.candidate_id','=','interview.candidate_id');
        $query = $query->join('users','users.id','=','candidate_otherinfo.owner_id');
        $query = $query->where('interview.id','=',$interview_id);
        $query = $query->select('users.email as candidateowneremail','users.secondary_email as candidateownersemail');
        $res = $query->first();

        return $res;
    }

    public static function getClientOwnerEmail($interview_id){

        $query = Interview::query();
        $query = $query->join('job_openings','job_openings.id','=','interview.posting_title');
        $query = $query->join('client_basicinfo','client_basicinfo.id','=','job_openings.client_id');
        $query = $query->join('users','users.id','=','client_basicinfo.account_manager_id');
        $query = $query->where('interview.id','=',$interview_id);
        $query = $query->select('users.email as clientowneremail','users.secondary_email as clientownersemail');
        $response = $query->first();

        return $response;
    }

    public static function getCandidateEmail($candidate_id,$posting_title,$interview_id){

        $from_name = getenv('FROM_NAME');
        $from_address = getenv('FROM_ADDRESS');
        $app_url = getenv('APP_URL');

        $input['from_name'] = $from_name;
        $input['from_address'] = $from_address;
        //$input['to'] = $user_email;
        $input['app_url'] = $app_url;


        $candidate_email = Interview::getCandidateOwnerEmail($interview_id);
        $candidate_owner_email = $candidate_email->candidateowneremail;

        $client_email = Interview::getClientOwnerEmail($interview_id);
        $client_owner_email = $client_email->clientowneremail;

        // Candidate details
        $candidate_response  = CandidateBasicInfo::find($candidate_id);
        $cname = $candidate_response->full_name;

        $to_address = array();
        $to_address[] = $candidate_owner_email;
        $to_address[] = $client_owner_email;

        //$to_address[] = 'tarikapanjwani@gmail.com';
       // $to_address[] = 'rajlalwani@adlertalent.com';

        $input['to'] = $to_address;

        // job Details
        $job_details = JobOpen::getJobById($posting_title);

        $input['cname'] = $cname;
        $input['city'] = $job_details['city'];
        $input['company_name'] = $job_details['company_name'];
        $input['company_url'] =$job_details['company_url'];
        $input['client_desc'] = $job_details['client_desc'];
        $input['job_designation'] = $job_details['posting_title'];
        $input['job_location'] = $job_details['job_location'];
        $input['job_description'] = $job_details['job_description'];
        $input['interview_date'] = $job_details['interview_date'];
        $input['interview_day'] = '';
        $input['interview_time'] = $job_details['interview_time'];
        $input['interview_location'] = $job_details['interview_location'];
        $input['contact_person'] = $job_details['contact_person'];

        \Mail::send('adminlte::emails.interviewcandidate', $input, function ($message) use($input) {
            $message->from($input['from_address'], $input['from_name']);
            $message->to($input['to'])->subject('Interview Details - '.$input['company_name'].' - '. $input['city']);
        });

    }

    public static function getScheduleEmail($candidate_id,$posting_title,$interview_id){

        $from_name = getenv('FROM_NAME');
        $from_address = getenv('FROM_ADDRESS');
        $app_url = getenv('APP_URL');

        $candidate_email = Interview::getCandidateOwnerEmail($interview_id);
        $candidate_owner_email = $candidate_email->candidateowneremail;

        $client_email = Interview::getClientOwnerEmail($interview_id);
        $client_owner_email = $client_email->clientowneremail;

        // Candidate details
        $candidate_response  = CandidateBasicInfo::find($candidate_id);
        $cname = $candidate_response->full_name;

        $to_address = array();
        $to_address[] = $candidate_owner_email;
        $to_address[] = $client_owner_email;
        //$to_address[] = 'tarikapanjwani@gmail.com';
        
        $input['from_name'] = $from_name;
        $input['from_address'] = $from_address;
        $input['to_address'] = $to_address;
        $input['app_url'] = $app_url;

        // Candidate details
        $candidate_response  = CandidateBasicInfo::find($candidate_id);
        $cname = $candidate_response->full_name;
        $ccity = $candidate_response->city;
        $cmobile = $candidate_response->mobile;
        $cemail = $candidate_response->email;

        // Candidate Attachment
        $attachment = CandidateUploadedResume::getCandidateAttachment($candidate_id);
        if (isset($attachment) && $attachment!='') {
            $file = $attachment->file_name;
        }
        $file_path = 'uploads/candidate/3/1401306.pdf';
            //print_r($file_path);exit;

        // job Details
        $job_details = JobOpen::getJobById($posting_title);

        $input['cname'] = $cname;
        $input['ccity'] = $ccity;
        $input['cmobile'] = $cmobile;
        $input['cemail'] = $cemail;
        $input['city'] = $job_details['city'];
        $input['company_name'] = $job_details['company_name'];
        $input['company_url'] =$job_details['company_url'];
        $input['client_desc'] = $job_details['client_desc'];
        $input['job_designation'] = $job_details['posting_title'];
        $input['job_location'] = $job_details['job_location'];
        $input['job_description'] = $job_details['job_description'];
        $input['interview_date'] = $job_details['interview_date'];
        $input['interview_day'] = '';
        $input['interview_time'] = $job_details['interview_time'];
        $input['interview_location'] = $job_details['interview_location'];
        $input['interview_type'] =$job_details['interview_type'];
        $input['skype_id'] = $job_details['skype_id'];
        $input['file'] = $file_path;
        $input['candidate_location'] = $job_details['candidate_location'];
        
        //return view('adminlte::emails.interviewschedule',compact('app_url','input'));

        \Mail::send('adminlte::emails.interviewschedule', $input, function ($message) use($input) {
            $message->from($input['from_address'], $input['from_name']);
            $message->to($input['to_address'])->subject('Interview Schedule for '.$input['company_name'].' position in '. $input['city']);
           /* $message->attach($input['file'], [
                        'as' => 'file.pdf',
                        'mime' => 'application/pdf',
                    ]);*/
        });
    }

    public static function ScheduleMailMultiple($value){

        $interview = Interview::find($value);

        /*$candidate_email = Interview::getCandidateOwnerEmail($value);
        $candidate_owner_email = $candidate_email->candidateowneremail;*/

        $client_email = Interview::getClientOwnerEmail($value);
        $client_owner_email = $client_email->clientowneremail;

        // Candidate details
        $candidate_response  = CandidateBasicInfo::find($interview['candidate_id']);
        $cname = $candidate_response->full_name;

        /*$to_address = array();
        $to_address[] = $candidate_owner_email;
        $to_address[] = $client_owner_email;*/

        $candidate_response  = CandidateBasicInfo::find($interview['candidate_id']);
        $cname = $candidate_response->full_name;
        //$ccity = $candidate_response->city;
        $cmobile = $candidate_response->mobile;
        $cemail = $candidate_response->email;

        $job_details = JobOpen::getJobById($interview['posting_title']);

        $interview_details = array();
        $interview_details['cname'] = $cname;
        $interview_details['ccity'] = '';
        $interview_details['cmobile'] = $cmobile;
        $interview_details['cemail'] = $cemail;
        $interview_details['city'] = $job_details['city'];
        $interview_details['company_name'] = $job_details['company_name'];
        $interview_details['company_url'] =$job_details['company_url'];
        $interview_details['client_desc'] = $job_details['client_desc'];
        $interview_details['job_designation'] = $job_details['posting_title'];
        $interview_details['job_location'] = $job_details['job_location'];
        $interview_details['job_description'] = $job_details['job_description'];
        $interview_details['interview_date'] = $job_details['interview_date'];
        $interview_details['interview_day'] = '';
        $interview_details['interview_time'] = $job_details['interview_time'];
        $interview_details['interview_location'] = $job_details['interview_location'];
        $interview_details['interview_type'] =$job_details['interview_type'];
        //$interview_details['candidate_owner_email'] = $candidate_owner_email;
        $interview_details['client_owner_email'] = $client_owner_email;
        $interview_details['skype_id'] = $job_details['skype_id'];
        $interview_details['candidate_location'] = $job_details['candidate_location'];

        return $interview_details;

    }

}
