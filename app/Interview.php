<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Interview extends Model
{
    //
    public $table = "interview";

    public static $rules = array(
        'interview_name' => 'required',
        'candidate_id' => 'required',
        'interview_date' => 'required',
    );

    public function messages()
    {
        return [
            'interview_name.required' => 'Interview Name is required field',
            'candidate_id.required' => 'Candidate is required field',
            'interview_date.required' => 'Interview Date is required field'
        ];
    }

    public static function createInterview($data){
        $interview = new Interview();
        $interview->interview_name = $data['interview_name'];
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

        return $interviewStatus;
    }

    public static function getAllInterviews($all=0,$user_id){

        $query = Interview::query();
        $query = $query->join('candidate_basicinfo','candidate_basicinfo.id','=','interview.candidate_id');
        $query = $query->join('job_openings','job_openings.id','=','interview.posting_title');
        $query = $query->join('client_basicinfo','client_basicinfo.id','=','job_openings.client_id');
        $query = $query->leftJoin('users','users.id','=','interview.interviewer_id');
        $query = $query->select('interview.id as id','interview.location', 'interview.interview_name as interview_name','interview.interview_date',
            'client_basicinfo.name as client_name','interview.candidate_id as candidate_id', 'candidate_basicinfo.full_name as candidate_fname',
            'candidate_basicinfo.lname as candidate_lname', 'interview.posting_title as posting_title_id',
            'job_openings.posting_title as posting_title', 'job_openings.city as city');
        $query = $query->orderby('interview.interview_date','desc');

        if($all==0){
            $query = $query->where('interviewer_id',$user_id);
        }

        $response = $query->get();

        return $response;
    }

    public static function getDashboardInterviews($all=0,$user_id){
        $from_date = date("Y-m-d 00:00:00");
        $to_date = date("Y-m-d 23:59:59", time() + 86400);

        $query = Interview::query();
        $query = $query->join('candidate_basicinfo','candidate_basicinfo.id','=','interview.candidate_id');
        $query = $query->join('job_openings','job_openings.id','=','interview.posting_title');
        $query = $query->join('client_basicinfo','client_basicinfo.id','=','job_openings.client_id');
        $query = $query->leftJoin('users','users.id','=','interview.interviewer_id');
        $query = $query->select('interview.id as id','interview.location', 'interview.interview_name as interview_name','interview.interview_date',
            'client_basicinfo.name as client_name','interview.candidate_id as candidate_id', 'candidate_basicinfo.full_name as candidate_fname',
            'candidate_basicinfo.lname as candidate_lname', 'interview.posting_title as posting_title_id',
            'job_openings.posting_title as posting_title','job_openings.city');

        if($all==0){
            $query = $query->where('interviewer_id',$user_id);
        }

        $query = $query->where('interview_date','>',"$from_date");
        $query = $query->where('interview_date','<',"$to_date");
        $query = $query->orderby('interview.interview_date','asc');

        $response = $query->get();

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

    public static function getDailyReportInterview(){

        $from_date = date("Y-m-d 00:00:00");
        $to_date = date("Y-m-d 23:59:59");

        $query = Interview::query();
        $query = $query->join('job_openings','job_openings.id','=','interview.posting_title');
        $query = $query->join('candidate_basicinfo','candidate_basicinfo.id','=','interview.candidate_id');
        $query = $query->select('job_openings.posting_title as posting_title','job_openings.city as location','interview.interview_date as date', 'interview.location as interview_location','interview.type as interview_type','candidate_basicinfo.full_name as cname','candidate_basicinfo.city as ccity','candidate_basicinfo.mobile as cmobile','candidate_basicinfo.email as cemail');
        $query = $query->where('interview_date','>',"$from_date");
        $query = $query->where('interview_date','<',"$to_date");

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

        //print_r($response);exit;
        return $response;

    }

}
