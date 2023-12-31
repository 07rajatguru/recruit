<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Interview extends Model
{
    //
    public $table = "interview";

    public static $rules = array(
        'candidate_id' => 'required',
        'interview_date' => 'required',
    );

    public function messages() {

        return [
            'candidate_id.required' => 'Candidate is required field',
            'interview_date.required' => 'Interview Date is required field'
        ];
    }

    public static function getAllinterviewCount($all=0,$user_id,$search=0,$company_name,$posting_title,$full_name,$mobile,$email,$interview_date,$owner_id,$status) {

        $query = Interview::query();
        $query = $query->leftjoin('job_openings','job_openings.id','=','interview.posting_title');
        $query = $query->leftjoin('client_basicinfo','client_basicinfo.id','=','job_openings.client_id');
        $query = $query->join('candidate_basicinfo','candidate_basicinfo.id','=', 'interview.candidate_id');
        $query = $query->join('candidate_otherinfo','candidate_otherinfo.id','=', 'interview.candidate_id');
        $query = $query->join('users','users.id','=','candidate_otherinfo.owner_id');
        $query = $query->select('interview.*', 'job_openings.posting_title as posting_title','client_basicinfo.name as client_name','candidate_basicinfo.full_name as full_name','candidate_basicinfo.mobile as mobile','candidate_basicinfo.email as email','users.name as candidate_owner');
        if ($all == 0) {
            $query = $query->where(function($query) use ($user_id) {
                $query = $query->where('client_basicinfo.account_manager_id',$user_id);
                $query = $query->orwhere('client_basicinfo.second_line_am',$user_id);
                $query = $query->orwhere('candidate_otherinfo.owner_id',$user_id);
                $query = $query->orwhere('interviewer_id',$user_id);
            });
        }
        if (isset($search) && $search != '') {
            $query = $query->where(function($query) use ($search) {
                $query = $query->where('client_basicinfo.name','=',"$search");
                $query = $query->orwhere('job_openings.posting_title','=',"$search");
                $query = $query->orwhere('candidate_basicinfo.full_name','=',"$search");
                $query = $query->orwhere('candidate_basicinfo.mobile','=',"%$search%");
                $query = $query->orwhere('candidate_basicinfo.email','like',"%$search%");
                $query = $query->orwhere('interview.interview_date','like',"%$search%");
                $query = $query->orwhere('candidate_otherinfo.owner_id','like',"%$search");
                $query = $query->orwhere('interview.status','like',"%$search%");

                if ($search == 'Yes' || $search == 'yes') {
                    $search = 1;
                    $query = $query->orwhere('interview.status','like',"%$search%");
                }
                if ($search == 'No' || $search == 'no') {
                    $search = 0;
                    $query = $query->orwhere('interview.status','like',"%$search%");
                }
                if ($search == 'Attended' || $search == 'attended') {
                    $search = 2;
                    $query = $query->orwhere('interview.status','like',"%$search%");
                }
                if ($search == 'Not attended' || $search == 'not attended') {
                    $search = 3;
                    $query = $query->orwhere('interview.status','like',"%$search%");
                }
                
                if(($search == 'Yes') || ($search == 'Yes ') || ($search == 'Yes') || ($search == 'Yes ') || ($search == 'No') || ($search == 'No ' ) || ($search == 'No') || ($search == 'No') || ($search == 'no') || ($search == 'no') || ($search == 'no') || ($search == 'no') || ($search == 'attended') || ($search == 'Attended') || ($search == 'Not attended') || ($search == 'not attended') || ($search == 'attended') || ($search == 'not Attended')) {
                    $search = 0;
                }
            });
        }
        // Master Search Condidtions
        if(isset($company_name) && $company_name != '') {
            $query = $query->where('client_basicinfo.name','Like',"%$company_name%");
        } else if(isset($posting_title) && $posting_title != '') {
            $query = $query->where('job_openings.posting_title','Like',"%$posting_title%");
        } else if(isset($full_name) && $full_name != '') {
            $query = $query->where('candidate_basicinfo.full_name','like',"%$full_name%");
        } else if(isset($mobile) && $mobile != '') {
            $query = $query->where('candidate_basicinfo.mobile','like',"%$mobile%");
        } else if(isset($email) && $email != '') {
            $query = $query->where('candidate_basicinfo.email','like',"%$email%");
        } else if(isset($interview_date) && $interview_date != '') {
            $interview_date = date('Y-m-d', strtotime($interview_date));
            $query = $query->where('interview.interview_date','like',"%$interview_date%");
        } else if(isset($owner_id) && $owner_id != '') {
            $query = $query->where('candidate_otherinfo.owner_id','=',"$owner_id");
        } else if(isset($status) && $status != '') {
            $query = $query->where('interview.status','=',$status);
        }
        $query = $query->get();
        $count = $query->count();

        return $count;
    }

    public static function getAllInterview($all=0,$user_id,$limit=0,$offset=0,$search=0,$order=0,$type='asc',$company_name,$posting_title,$full_name,$mobile,$email,$interview_date,$owner_id,$status) {

        $query = Interview::query();
        $query = $query->leftjoin('job_openings','job_openings.id','=','interview.posting_title');
        $query = $query->leftjoin('client_basicinfo','client_basicinfo.id','=','job_openings.client_id');
        $query = $query->join('candidate_basicinfo','candidate_basicinfo.id','=','interview.candidate_id');
        $query = $query->join('candidate_otherinfo','candidate_otherinfo.candidate_id','=','candidate_basicinfo.id');
        $query = $query->join('users','users.id','=','candidate_otherinfo.owner_id');
        $query = $query->select('interview.*', 'job_openings.posting_title as posting_title','client_basicinfo.name as client_name','candidate_basicinfo.full_name as full_name','candidate_basicinfo.mobile as mobile','candidate_basicinfo.email as email','users.name as candidate_owner','job_openings.remote_working as remote_working','job_openings.city as job_city','candidate_otherinfo.owner_id as owner_id');
        if ($all == 0) {
            $query = $query->where(function($query) use ($user_id) {
                $query = $query->where('client_basicinfo.account_manager_id',$user_id);
                $query = $query->orwhere('client_basicinfo.second_line_am',$user_id);
                $query = $query->orwhere('candidate_otherinfo.owner_id',$user_id);
                $query = $query->orwhere('interviewer_id',$user_id);
            });
        }
        if (isset($search) && $search != '') {
            $query = $query->where(function($query) use ($search) {
                $query = $query->where('client_basicinfo.name','=',"$search");
                $query = $query->orwhere('job_openings.posting_title','=',"$search");
                $query = $query->orwhere('candidate_basicinfo.full_name','=',"$search");
                $query = $query->orwhere('candidate_basicinfo.mobile','=',"%$search%");
                $query = $query->orwhere('candidate_basicinfo.email','like',"%$search%");
                $query = $query->orwhere('interview.interview_date','like',"%$search%");
                $query = $query->orwhere('candidate_otherinfo.owner_id','like',"%$search%");
                $query = $query->orwhere('interview.status','like',"%$search%");

                if ($search == 'Yes' || $search == 'yes') {
                    $search = 1;
                    $query = $query->orwhere('interview.status','like',"%$search%");
                }
                if ($search == 'No' || $search == 'no') {
                    $search = 0;
                    $query = $query->orwhere('interview.status','like',"%$search%");
                }
                if ($search == 'Attended' || $search == 'attended') {
                    $search = 2;
                    $query = $query->orwhere('interview.status','like',"%$search%");
                }
                if ($search == 'Not attended' || $search == 'not attended') {
                    $search = 3;
                    $query = $query->orwhere('interview.status','like',"%$search%");
                }
                
                if(($search == 'Yes') || ($search == 'Yes ') || ($search == 'Yes') || ($search == 'Yes ') || ($search == 'No') || ($search == 'No ' ) || ($search == 'No') || ($search == 'No') || ($search == 'no') || ($search == 'no') || ($search == 'no') || ($search == 'no') || ($search == 'attended') || ($search == 'Attended') || ($search == 'Not attended') || ($search == 'not attended') || ($search == 'attended') || ($search == 'not Attended')) {
                    $search = 0;
                }
            });
        }
        // Master Search Conditions
        if(isset($company_name) && $company_name != '') {
            $query = $query->where('client_basicinfo.name','like',"%$company_name%");
        } else if(isset($posting_title) && $posting_title != '') {
            $query = $query->where('job_openings.posting_title','like',"%$posting_title%");
        } else if(isset($full_name) && $full_name != '') {
            $query = $query->where('candidate_basicinfo.full_name','like',"%$full_name%");
        } else if(isset($mobile) && $mobile != '') {
            $query = $query->where('candidate_basicinfo.mobile','like',"%$mobile%");
        } else if(isset($email) && $email != '') {
            $query = $query->where('candidate_basicinfo.email','like',"%$email%");
        } else if(isset($interview_date) && $interview_date != '') {
            $interview_date = date('Y-m-d', strtotime($interview_date));
            $query = $query->where('interview.interview_date','like',"%$interview_date%");
        } else if(isset($owner_id) && $owner_id != '') {
            $query = $query->where('candidate_otherinfo.owner_id','=',"$owner_id");
        } else if(isset($status) && $status != '') {
            $query = $query->where('interview.status','=',$status);
        }

        if (isset($limit) && $limit > 0) {
            $query = $query->limit($limit);
        }
        if (isset($offset) && $offset > 0) {
            $query = $query->offset($offset);
        }
        if (isset($order) && $order !='') {
            $query = $query->orderBy($order,$type);
        }
        $res = $query->get();
        // print_r($res);exit;

        $interview_array = array();
        $i = 0;
        foreach ($res as $key => $value) {
            $interview_array[$i]['id'] = $value->id;
            if($value->remote_working == '1') {
                $interview_array[$i]['city'] = "Remote";
            } else {
                $interview_array[$i]['city'] = $value->job_city;
            }
            $interview_array[$i]['client_name'] = $value->client_name;
            $interview_array[$i]['posting_title'] = $value->posting_title;
            $interview_array[$i]['full_name'] = $value->full_name;
            $interview_array[$i]['mobile'] = $value->mobile;
            $interview_array[$i]['email'] = $value->email;
            $interview_array[$i]['interview_date'] = $value->interview_date;
            $interview_array[$i]['candidate_owner'] = $value->candidate_owner;
            $interview_array[$i]['status'] = $value->status;
            $i++;
        }

        return $interview_array;
    }

    public static function getAllStatus() {

        $status = array();
        $status[''] = 'Select Status';
        $status['No'] = 'No';
        $status['Yes'] = 'Yes';
        $status['Attended'] = 'Attended';
        $status['Not Attended'] = 'Not Attended';
        return $status;
    }

    public static function getAllDetailsByUserID($user_id) {

        $user_query = User::query();
        $user_query = $user_query->where('users.id',$user_id);
        $user_query = $user_query->select('users.*');
        $response = $user_query->first();

        return $response;
    }
    // public static function getCategory() {

    //     $type = array();
    //     $type[''] = 'Select Category';
    //     $type['Yes'] = 'Yes';
    //     $type['No'] = 'No';
    //     $type['Attened'] = 'Attened';
    //     $type['Not Attened'] = 'Not Attened';

    //     return $type;
    // }

    public static function getInterviewFieldsList() {

        $field_list = array();
        
        $field_list[''] = 'Select Field';
        $field_list['Company Name'] = 'Company Name';
        $field_list['Posting Title'] = 'Posting Title';
        $field_list['Candidate Name'] = 'Candidate Name';
        $field_list['Mobile'] = 'Mobile';
        $field_list['Email'] = 'Email';
        $field_list['Interview Date'] = 'Interview Date';
        $field_list['Candidate owner'] = 'Candidate owner';
        $field_list['Status'] = 'Status';

        return $field_list;
    }
          
    public static function createInterview($data) {

        $interview = new Interview();
        $interview->interview_name = '';
        $interview->candidate_id = $data['candidate_id'];
        $interview->posting_title = $data['posting_title'];

        if(isset($data['interviewer_id']) && $data['interviewer_id']!='')
            $interview->interviewer_id = $data['interviewer_id'];

        $interview->type = $data['type'];
        $interview->interview_date = $data['interview_date'];
        $interview->location = $data['location'];
        $interview->status = $data['status'];
        $interview->about = $data['about'];
        $interview->comments = $data['comments'];
        $interview->remarks = $data['remarks'];
        $interview->interview_owner_id = $data['interview_owner_id'];

        if (isset($data['skype_id']) && $data['skype_id'] != '') {
            $interview->skype_id = $data['skype_id'];
        }

        $interview->select_round = $data['round'];
        $interview->candidate_location = $data['candidate_location'];
        $interview->interview_location = $data['interview_location'];

        return $interview;
    }

    public static function getTypeArray() {

        $typeArray = array('' => 'Select Interview Type');
        $typeArray['Telephonic Interview'] = 'Telephonic Interview';
        $typeArray['Personal Interview'] = 'Personal Interview';
        $typeArray['Video-G Meet'] = 'Video-G Meet';
        $typeArray['Video-Zoom'] = 'Video-Zoom';
        $typeArray['Video-Microsoft Teams'] = 'Video-Microsoft Teams';
        $typeArray['Video-Others'] = 'Video-Others';

        return $typeArray;
    }

    public static function getCreateInterviewStatus() {

        /*$interviewStatus['Selected'] = 'Selected';
        $interviewStatus['Rejected'] = 'Rejected';
        $interviewStatus['OnHold'] = 'OnHold';*/

        $interviewStatus['Yes'] = 'Yes';
        $interviewStatus['Attended'] = 'Attended';

        return $interviewStatus;
    }

    public static function getEditInterviewStatus() {

        /*$interviewStatus['Selected'] = 'Selected';
        $interviewStatus['Rejected'] = 'Rejected';
        $interviewStatus['OnHold'] = 'OnHold';*/

        $interviewStatus['Yes'] = 'Yes';
        $interviewStatus['No'] = 'No';
        $interviewStatus['Attended'] = 'Attended';
        $interviewStatus['Not Attended'] = 'Not Attended';

        return $interviewStatus;
    }

    public static function getSelectRound() {

        $interviewround['1'] = 'Round 1';
        $interviewround['2'] = 'Round 2';
        $interviewround['3'] = 'Round 3';

        return $interviewround;
    }

    public static function getAllInterviews($all=0,$user_id) {

        $query = Interview::query();
        $query = $query->join('candidate_basicinfo','candidate_basicinfo.id','=','interview.candidate_id');
        $query = $query->join('candidate_otherinfo','candidate_otherinfo.candidate_id','=','candidate_basicinfo.id');
        $query = $query->join('users','users.id','=','candidate_otherinfo.owner_id');
        $query = $query->join('job_openings','job_openings.id','=','interview.posting_title');
        $query = $query->join('client_basicinfo','client_basicinfo.id','=','job_openings.client_id');

        $query = $query->select('interview.id as id','interview.interview_date','client_basicinfo.name as client_name','job_openings.posting_title as posting_title','job_openings.city as job_city','interview.type as interview_type','interview.interview_location as interview_location','job_openings.remote_working as remote_working');

        $query = $query->orderby('interview.interview_date','asc');

        if($all == 0) {

            $query = $query->where(function($query) use ($user_id) {

                $query = $query->where('client_basicinfo.account_manager_id',$user_id);
                $query = $query->orwhere('client_basicinfo.second_line_am',$user_id);
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

            if($value->remote_working == '1') {

                $interview[$i]['city'] = "Remote";
            }
            else {

                $interview[$i]['city'] = $value->job_city;
            }
            
            $interview[$i]['interview_type'] = $value->interview_type;
            $interview[$i]['interview_location'] = $value->interview_location;            
            $i++;
        }

        return $interview;
    }

    //function for indax using ajax call
    public static function getAllInterviewsByAjax($all=0,$user_id,$limit=0,$offset=0,$search=0,$order=NULL,$type='desc',$current_year=NULL,$next_year=NULL) {

        $query = Interview::query();
        $query = $query->join('candidate_basicinfo','candidate_basicinfo.id','=','interview.candidate_id');
        $query = $query->join('candidate_otherinfo','candidate_otherinfo.candidate_id','=','candidate_basicinfo.id');
        $query = $query->join('users','users.id','=','candidate_otherinfo.owner_id');
        $query = $query->join('job_openings','job_openings.id','=','interview.posting_title');
        $query = $query->join('client_basicinfo','client_basicinfo.id','=','job_openings.client_id');
        $query = $query->select('interview.id as id','interview.location','interview.remarks', 'interview.interview_name as interview_name','interview.interview_date','interview.status','client_basicinfo.name as client_name','interview.candidate_id as candidate_id', 'candidate_basicinfo.full_name as candidate_fname','candidate_basicinfo.lname as candidate_lname', 'interview.posting_title as posting_title_id','job_openings.posting_title as posting_title', 'job_openings.city as city','candidate_basicinfo.mobile as contact','users.name as candidate_owner','job_openings.remote_working as remote_working','candidate_basicinfo.email as candidate_email');

        if($all==0) {

            $query = $query->where(function($query) use ($user_id) {

                $query = $query->where('client_basicinfo.account_manager_id',$user_id);
                $query = $query->orwhere('client_basicinfo.second_line_am',$user_id);
                $query = $query->orwhere('candidate_otherinfo.owner_id',$user_id);
                $query = $query->orwhere('interviewer_id',$user_id);
            });
        }
        if (isset($limit) && $limit > 0) {
            $query = $query->limit($limit);
        }
        if (isset($offset) && $offset > 0) {
            $query = $query->offset($offset);
        }
        if (isset($order) && $order != '') {
            $query = $query->orderBy($order,$type);
        }

        // Get data by financial year
        if (isset($current_year) && $current_year != NULL) {
            $query = $query->where('interview.interview_date','>=',$current_year);
        }
        if (isset($next_year) && $next_year != NULL) {
            $next_year = date("Y-m-d 23:59:59",strtotime($next_year));
            $query = $query->where('interview.interview_date','<=',$next_year);
        }

        if (isset($search) && $search != '') {
            $query = $query->where(function($query) use ($search) {

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

                $query = $query->where('job_openings.posting_title','like',"%$search%");
                $query = $query->orwhere('job_openings.city','like',"%$search%");
                $query = $query->orwhere('users.name','like',"%$search%");
                $query = $query->orwhere('candidate_basicinfo.full_name','like',"%$search%");
                $query = $query->orwhere('candidate_basicinfo.mobile','like',"%$search%");
                $query = $query->orwhere('interview.location','like',"%$search%");
                $query = $query->orwhere('interview.remarks','like',"%$search%");
                $query = $query->orwhere('interview.status','like',"%$search%");
                $query = $query->orwhere('candidate_basicinfo.email','like',"%$search%");

                if(($search == 'Remote') || ($search == 'remote')) {

                    $query = $query->orwhere('job_openings.remote_working','=',"1");
                }

                if($date_search) {
                   
                    $dateClass = new Date();
                    $search_string = $dateClass->changeDMYtoYMD($search);
                    $from_date = date("Y-m-d 00:00:00",strtotime($search_string));
                    $to_date = date("Y-m-d 23:59:59",strtotime($search_string));
                    $query = $query->orwhere('interview.interview_date','>=',"$from_date");
                    $query = $query->Where('interview.interview_date','<=',"$to_date");
                }
            });
        }
        $response = $query->get();

        $interview = array();
        $i=0;

        foreach ($response as $key => $value) {

            $interview[$i]['id'] = $value->id;
            $interview[$i]['client_name'] = $value->client_name;
            $interview[$i]['posting_title'] = $value->posting_title;

            if($value->remote_working == '1') {

                $interview[$i]['city'] = "Remote";
            }
            else {

                $interview[$i]['city'] = $value->city;
            }

            $interview[$i]['candidate_fname'] = $value->candidate_fname;
            $interview[$i]['contact'] = $value->contact;
            $interview[$i]['interview_date'] = date('d-m-Y h:i A', strtotime("$value->interview_date"));
            $interview[$i]['interview_date_ts'] = strtotime($value->interview_date);
            $interview[$i]['location'] = $value->location;
            $interview[$i]['remarks'] = $value->remarks;
            $interview[$i]['status'] = $value->status;
            $interview[$i]['candidate_owner'] = $value->candidate_owner;
            $interview[$i]['candidate_email'] = $value->candidate_email;
            $i++;
        }
        return $interview;
    }

    public static function getAllInterviewsCountByAjax($all=0,$user_id,$search=0,$current_year=NULL,$next_year=NULL) {

        $query = Interview::query();
        $query = $query->join('candidate_basicinfo','candidate_basicinfo.id','=','interview.candidate_id');
        $query = $query->join('candidate_otherinfo','candidate_otherinfo.candidate_id','=','candidate_basicinfo.id');
        $query = $query->join('users','users.id','=','candidate_otherinfo.owner_id');
        $query = $query->join('job_openings','job_openings.id','=','interview.posting_title');
        $query = $query->join('client_basicinfo','client_basicinfo.id','=','job_openings.client_id');
        
        $query = $query->select('interview.id as id','interview.location', 'interview.interview_name as interview_name','interview.interview_date','interview.status','client_basicinfo.name as client_name','interview.candidate_id as candidate_id', 'candidate_basicinfo.full_name as candidate_fname','candidate_basicinfo.lname as candidate_lname', 'interview.posting_title as posting_title_id','job_openings.posting_title as posting_title', 'job_openings.city as city','candidate_basicinfo.mobile as contact','users.name as candidate_owner','job_openings.remote_working as remote_working');

        if($all==0) {

            $query = $query->where(function($query) use ($user_id) {

                $query = $query->where('client_basicinfo.account_manager_id',$user_id);
                $query = $query->orwhere('client_basicinfo.second_line_am',$user_id);
                $query = $query->orwhere('candidate_otherinfo.owner_id',$user_id);
                $query = $query->orwhere('interviewer_id',$user_id);
            });
        }

        // Get data by financial year
        if (isset($current_year) && $current_year != NULL) {
            $query = $query->where('interview.interview_date','>=',$current_year);
        }
        if (isset($next_year) && $next_year != NULL) {
            $next_year = date("Y-m-d 23:59:59",strtotime($next_year));
            $query = $query->where('interview.interview_date','<=',$next_year);
        }
       
        if (isset($search) && $search != '') {

            $query = $query->where(function($query) use ($search) {

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

                $query = $query->where('job_openings.posting_title','like',"%$search%");
                $query = $query->orwhere('job_openings.city','like',"%$search%");
                $query = $query->orwhere('users.name','like',"%$search%");
                $query = $query->orwhere('candidate_basicinfo.full_name','like',"%$search%");
                $query = $query->orwhere('candidate_basicinfo.mobile','like',"%$search%");
                $query = $query->orwhere('interview.location','like',"%$search%");
                $query = $query->orwhere('interview.remarks','like',"%$search%");
                $query = $query->orwhere('interview.status','like',"%$search%");

                if(($search == 'Remote') || ($search == 'remote')) {

                    $query = $query->orwhere('job_openings.remote_working','=',"1");
                }

                if($date_search) {
                   
                    $dateClass = new Date();
                    $search_string = $dateClass->changeDMYtoYMD($search);
                    $from_date = date("Y-m-d 00:00:00",strtotime($search_string));
                    $to_date = date("Y-m-d 23:59:59",strtotime($search_string));
                    $query = $query->orwhere('interview.interview_date','>=',"$from_date");
                    $query = $query->Where('interview.interview_date','<=',"$to_date");
                }
            });
        }
        $response = $query->count();

        return $response;
    }

    // function for today, tomorrow, this week & Upcoming/Previous interview page
    public static function getInterviewsByType($all=0,$user_id,$time,$limit=0,$offset=0,$search=0,$order=NULL,$type='desc') {

        $query = Interview::query();
        $query = $query->join('candidate_basicinfo','candidate_basicinfo.id','=','interview.candidate_id');
        $query = $query->join('candidate_otherinfo','candidate_otherinfo.candidate_id','=','candidate_basicinfo.id');
        $query = $query->join('users','users.id','=','candidate_otherinfo.owner_id');
        $query = $query->join('job_openings','job_openings.id','=','interview.posting_title');
        $query = $query->join('client_basicinfo','client_basicinfo.id','=','job_openings.client_id');

        $query = $query->select('interview.id as id','interview.location', 'interview.interview_name as interview_name','interview.interview_date','interview.status','client_basicinfo.name as client_name','interview.candidate_id as candidate_id', 'candidate_basicinfo.full_name as candidate_fname','candidate_basicinfo.lname as candidate_lname', 'interview.posting_title as posting_title_id','job_openings.posting_title as posting_title', 'job_openings.city as city','candidate_basicinfo.mobile as contact','users.name as candidate_owner','job_openings.remote_working as remote_working','candidate_basicinfo.email as candidate_email');

        if($all==0) {

            $query = $query->where(function($query) use ($user_id) {

                $query = $query->where('client_basicinfo.account_manager_id',$user_id);
                $query = $query->orwhere('client_basicinfo.second_line_am',$user_id);
                $query = $query->orwhere('candidate_otherinfo.owner_id',$user_id);
                $query = $query->orwhere('interviewer_id',$user_id);
            });
        }

        if (isset($limit) && $limit > 0) {
            $query = $query->limit($limit);
        }
        if (isset($offset) && $offset > 0) {
            $query = $query->offset($offset);
        }
        if (isset($order) && $order != '') {
            $query = $query->orderBy($order,$type);
        }
        if (isset($search) && $search != '') {

            $query = $query->where(function($query) use ($search) {

                $date_search = false;
                $date_array = explode("-",$search);

                if(isset($date_array) && sizeof($date_array)>0) {

                    $stamp = strtotime($search);
                    if (is_numeric($stamp)) {

                        $month = date( 'm', $stamp );
                        $day   = date( 'd', $stamp );
                        $year  = date( 'Y', $stamp );

                        if(checkdate($month, $day, $year)) {
                            $date_search = true;
                        }
                    }
                }

                $query = $query->where('job_openings.posting_title','like',"%$search%");
                $query = $query->orwhere('job_openings.city','like',"%$search%");
                $query = $query->orwhere('candidate_basicinfo.full_name','like',"%$search%");
                $query = $query->orwhere('candidate_basicinfo.mobile','like',"%$search%");
                $query = $query->orwhere('interview.location','like',"%$search%");
                $query = $query->orwhere('interview.status','like',"%$search%");
                $query = $query->orwhere('users.name','like',"%$search%");
                $query = $query->orwhere('candidate_basicinfo.email','like',"%$search%");

                if(($search == 'Remote') || ($search == 'remote')) {

                    $query = $query->orwhere('job_openings.remote_working','=',"1");
                }

                if($date_search) {
                   
                    $dateClass = new Date();
                    $search_string = $dateClass->changeDMYtoYMD($search);
                    $from_date = date("Y-m-d 00:00:00",strtotime($search_string));
                    $to_date = date("Y-m-d 23:59:59",strtotime($search_string));
                    $query = $query->orwhere('interview.interview_date','>=',"$from_date");
                    $query = $query->Where('interview.interview_date','<=',"$to_date");
                }
            });
        }

        if ($time == 'today') {
            $today = date("Y-m-d");
            $query = $query->where('interview.interview_date','like',"%$today%");
        }
        if ($time == 'tomorrow') {

            $tomorrow = date("Y-m-d",strtotime('tomorrow'));
            $query = $query->where('interview.interview_date','like',"%$tomorrow%");
        }
        if ($time == 'thisweek') {

            $from_date = date("Y-m-d", strtotime('this week'));
            $to_date = date("Y-m-d",strtotime("$from_date +6 days"));
            $query = $query->where('interview.interview_date','>',"$from_date");
            $query = $query->where('interview.interview_date','<',"$to_date");
        }
        if ($time == 'upcomingprevious') {

            // $from_date = date("Y-m-d", strtotime('this week'));
            // $to_date = date("Y-m-d",strtotime("$from_date +6 days"));

            $from_date = date("Y-m-d", strtotime('first day of this month'));
            $to_date = date("Y-m-d", strtotime('last day of this month'));
            $query = $query->where('interview.interview_date','>',"$from_date");
            $query = $query->where('interview.interview_date','<',"$to_date");
        }
        $response = $query->get();

        $interview = array();
        $i=0;

        foreach ($response as $key => $value) {

            $interview[$i]['id'] = $value->id;
            $interview[$i]['client_name'] = $value->client_name;
            $interview[$i]['posting_title'] = $value->posting_title;

            if($value->remote_working == '1') {

                $interview[$i]['city'] = "Remote";
            }
            else {

                $interview[$i]['city'] = $value->city;
            }

            $interview[$i]['candidate_fname'] = $value->candidate_fname;
            $interview[$i]['contact'] = $value->contact;
            $interview[$i]['interview_date'] = $value->interview_date;
            $interview[$i]['interview_date_ts'] = strtotime($value->interview_date);
            $interview[$i]['location'] = $value->location;
            $interview[$i]['status'] = $value->status;
            $interview[$i]['candidate_owner'] = $value->candidate_owner;
            $interview[$i]['candidate_email'] = $value->candidate_email;
            $i++;
        }

        return $interview;
    }

    public static function getInterviewsCountByType($all=0,$user_id,$time,$search=0) {

        $query = Interview::query();
        $query = $query->join('candidate_basicinfo','candidate_basicinfo.id','=','interview.candidate_id');
        $query = $query->join('candidate_otherinfo','candidate_otherinfo.candidate_id','=','candidate_basicinfo.id');
        $query = $query->join('users','users.id','=','candidate_otherinfo.owner_id');
        $query = $query->join('job_openings','job_openings.id','=','interview.posting_title');
        $query = $query->join('client_basicinfo','client_basicinfo.id','=','job_openings.client_id');
        
        $query = $query->select('interview.id as id','interview.location', 'interview.interview_name as interview_name','interview.interview_date','interview.status','client_basicinfo.name as client_name','interview.candidate_id as candidate_id', 'candidate_basicinfo.full_name as candidate_fname','candidate_basicinfo.lname as candidate_lname', 'interview.posting_title as posting_title_id','job_openings.posting_title as posting_title', 'job_openings.city as city','candidate_basicinfo.mobile as contact','users.name as candidate_owner','job_openings.remote_working as remote_working');

        if($all==0) {

            $query = $query->where(function($query) use ($user_id) {

                $query = $query->where('client_basicinfo.account_manager_id',$user_id);
                $query = $query->orwhere('client_basicinfo.second_line_am',$user_id);
                $query = $query->orwhere('candidate_otherinfo.owner_id',$user_id);
                $query = $query->orwhere('interviewer_id',$user_id);
            });
        }

        if (isset($search) && $search != '') {

            $query = $query->where(function($query) use ($search) {

                $date_search = false;
                $date_array = explode("-",$search);

                if(isset($date_array) && sizeof($date_array)>0) {

                    $stamp = strtotime($search);
                    if (is_numeric($stamp)) {

                        $month = date( 'm', $stamp );
                        $day   = date( 'd', $stamp );
                        $year  = date( 'Y', $stamp );

                        if(checkdate($month, $day, $year)) {
                            $date_search = true;
                        }
                    }
                }

                $query = $query->where('job_openings.posting_title','like',"%$search%");
                $query = $query->orwhere('job_openings.city','like',"%$search%");
                $query = $query->orwhere('candidate_basicinfo.full_name','like',"%$search%");
                $query = $query->orwhere('candidate_basicinfo.mobile','like',"%$search%");
                $query = $query->orwhere('interview.location','like',"%$search%");
                $query = $query->orwhere('interview.status','like',"%$search%");
                $query = $query->orwhere('users.name','like',"%$search%");

                if(($search == 'Remote') || ($search == 'remote')) {

                    $query = $query->orwhere('job_openings.remote_working','=',"1");
                }

                if($date_search) {
                   
                    $dateClass = new Date();
                    $search_string = $dateClass->changeDMYtoYMD($search);
                    $from_date = date("Y-m-d 00:00:00",strtotime($search_string));
                    $to_date = date("Y-m-d 23:59:59",strtotime($search_string));
                    $query = $query->orwhere('interview.interview_date','>=',"$from_date");
                    $query = $query->Where('interview.interview_date','<=',"$to_date");
                }
            });
        }

        if ($time == 'today') {
            
            $today = date("Y-m-d");
            $query = $query->where('interview.interview_date','like',"%$today%");
        }
        if ($time == 'tomorrow') {

            $tomorrow = date("Y-m-d",strtotime('tomorrow'));
            $query = $query->where('interview.interview_date','like',"%$tomorrow%");
        }
        if ($time == 'thisweek') {

            $from_date = date("Y-m-d", strtotime('this week'));
            $to_date = date("Y-m-d",strtotime("$from_date +6 days"));
            $query = $query->where('interview.interview_date','>',"$from_date");
            $query = $query->where('interview.interview_date','<',"$to_date");
        }
        if ($time == 'upcomingprevious') {

            // $from_date = date("Y-m-d", strtotime('this week'));
            // $to_date = date("Y-m-d",strtotime("$from_date +6 days"));

            $from_date = date("Y-m-d", strtotime('first day of this month'));
            $to_date = date("Y-m-d", strtotime('last day of this month'));
            $query = $query->where('interview.interview_date','>',"$from_date");
            $query = $query->where('interview.interview_date','<',"$to_date");
        }

        $response = $query->count();

        return $response;
    }

    public static function getTodayTomorrowsInterviews($all=0,$user_id,$department_id=0) {

        $from_date = date("Y-m-d 00:00:00");
        $to_date = date("Y-m-d 23:59:59", time() + 86400);

        $query = Interview::query();
        $query = $query->join('candidate_basicinfo','candidate_basicinfo.id','=','interview.candidate_id');
        $query = $query->join('candidate_otherinfo','candidate_otherinfo.candidate_id','=','candidate_basicinfo.id');
        $query = $query->join('job_openings','job_openings.id','=','interview.posting_title');
        $query = $query->join('client_basicinfo','client_basicinfo.id','=','job_openings.client_id');
        $query = $query->leftJoin('users','users.id','=','interview.interviewer_id');
        $query = $query->select('interview.id as id','interview.location', 'interview.interview_name as interview_name','interview.interview_date','interview.status','client_basicinfo.name as client_name','interview.candidate_id as candidate_id', 'candidate_basicinfo.full_name as candidate_fname','candidate_basicinfo.lname as candidate_lname', 'interview.posting_title as posting_title_id','job_openings.posting_title as posting_title', 'job_openings.city as city','candidate_basicinfo.mobile as contact','job_openings.remote_working as remote_working','candidate_basicinfo.email as candidate_email');

        $query = $query->orderby('interview.interview_date','desc');

        if($all==0) {

            $query = $query->where(function($query) use ($user_id) {

                $query = $query->where('client_basicinfo.account_manager_id',$user_id);
                $query = $query->orwhere('client_basicinfo.second_line_am',$user_id);
                $query = $query->orwhere('candidate_otherinfo.owner_id',$user_id);
                $query = $query->orwhere('interviewer_id',$user_id);
            });
        }

        // For Department
        if (isset($department_id) && $department_id > 0) {
            $query = $query->where('client_basicinfo.department_id','=',$department_id);
        }

        $query = $query->where('interview_date','>',"$from_date");
        $query = $query->where('interview_date','<',"$to_date");
        $query = $query->orderby('interview.interview_date','asc');

        $response = $query->get();

        return $response;
    }

    public static function getAttendedInterviews($all=0,$user_id,$month=NULL,$year=NULL,$department_id=0) {

        $tanisha_user_id = getenv('TANISHAUSERID');
        $hr_user_id = getenv('HRUSERID');

        $query = Interview::query();
        $query = $query->join('candidate_basicinfo','candidate_basicinfo.id','=','interview.candidate_id');
        $query = $query->join('candidate_otherinfo','candidate_otherinfo.candidate_id','=','candidate_basicinfo.id');
        $query = $query->join('job_openings','job_openings.id','=','interview.posting_title');
        $query = $query->join('client_basicinfo','client_basicinfo.id','=','job_openings.client_id');
        $query = $query->leftJoin('users','users.id','=','interview.interviewer_id');

        $query = $query->select('interview.id as id','interview.location', 'interview.interview_name as interview_name','interview.interview_date','interview.status','client_basicinfo.name as client_name','interview.candidate_id as candidate_id', 'candidate_basicinfo.full_name as candidate_fname','candidate_basicinfo.lname as candidate_lname', 'interview.posting_title as posting_title_id','job_openings.posting_title as posting_title', 'job_openings.city as city','candidate_basicinfo.mobile as contact','job_openings.remote_working as remote_working','candidate_basicinfo.email as candidate_email');

        if($all == 0) {

            if($user_id == $tanisha_user_id) {
                $query = $query->where('job_openings.hiring_manager_id',$user_id);
            }
            else if($user_id == $hr_user_id) {
                
                $query = $query->where(function($query) {

                    $query = $query->where('client_basicinfo.name','=','Adler Talent Solution Pvt Ltd.');
                    $query = $query->orwhere('client_basicinfo.name','=','Traj Infotech Pvt. Ltd.');
                });
            }
            else {
                $query = $query->where('candidate_otherinfo.owner_id',$user_id);
            }
        }

        // For Department
        if (isset($department_id) && $department_id > 0) {
            $query = $query->where('client_basicinfo.department_id','=',$department_id);
        }

        $query = $query->where('interview.status','=','Attended');
        $query = $query->where(\DB::raw('MONTH(interview_date)'),'=',$month);
        $query = $query->where(\DB::raw('YEAR(interview_date)'),'=',$year);
        $query = $query->orderby('interview.interview_date','desc');
        
        $response = $query->get();

        return $response;
    }

    public static function getDashboardInterviews($all=0,$user_id,$department_id=0) {

        $from_date = date("Y-m-d H:i:s");
        $to_date = date("Y-m-d 23:59:59", time() + 86400);
        $hr_user_id = getenv('HRUSERID');

        // Get Current Date & Time with different zone
        $dt = new \DateTime($from_date);
        $tz = new \DateTimeZone('Asia/Kolkata'); // or whatever zone you're after
        $dt->setTimezone($tz);
        $get_current_time = $dt->format('Y-m-d H:i:s');

        $query = Interview::query();

        $query = $query->join('candidate_basicinfo','candidate_basicinfo.id','=','interview.candidate_id');
        $query = $query->join('candidate_otherinfo','candidate_otherinfo.candidate_id','=','candidate_basicinfo.id');
        $query = $query->join('job_openings','job_openings.id','=','interview.posting_title');
        $query = $query->join('client_basicinfo','client_basicinfo.id','=','job_openings.client_id');
        $query = $query->leftJoin('users as u1','u1.id','=','interview.interviewer_id');
        $query = $query->leftJoin('users as u2','u2.id','=','candidate_otherinfo.owner_id');

        $query = $query->select('interview.id as id','interview.location', 'interview.interview_name as interview_name','interview.interview_date','client_basicinfo.name as client_name','client_basicinfo.display_name as display_name','interview.candidate_id as candidate_id', 'candidate_basicinfo.full_name as candidate_fname','candidate_basicinfo.lname as candidate_lname', 'interview.posting_title as posting_title_id','job_openings.posting_title as posting_title','job_openings.city','candidate_basicinfo.mobile as contact','u2.name as candidate_owner_name','job_openings.remote_working as remote_working');

        if($user_id == $hr_user_id) {
                
            $query = $query->where(function($query) {

                $query = $query->where('client_basicinfo.name','=','Adler Talent Solution Pvt Ltd.');
                $query = $query->orwhere('client_basicinfo.name','=','Traj Infotech Pvt. Ltd.');
            });
        }

        if($all==0) {
            
            $query = $query->where(function($query) use ($user_id) {

                $query = $query->where('client_basicinfo.account_manager_id',$user_id);
                $query = $query->orwhere('client_basicinfo.second_line_am',$user_id);
                $query = $query->orwhere('candidate_otherinfo.owner_id',$user_id);
                $query = $query->orwhere('interviewer_id',$user_id);
            });
        }

        // For Department
        if (isset($department_id) && $department_id > 0) {
            $query = $query->where('client_basicinfo.department_id','=',$department_id);
        }

        $query = $query->where('interview_date','>=',"$get_current_time");
        $query = $query->where('interview_date','<=',"$to_date");
        $query = $query->orderby('interview.interview_date','asc');

        $response = $query->get();

        return $response;
    }

    public static function getInterviewById($id) {

        $query = Interview::query();
        $query = $query->join('candidate_basicinfo','candidate_basicinfo.id','=','interview.candidate_id');
        $query = $query->join('job_openings','job_openings.id','=','interview.posting_title');
        $query = $query->join('client_basicinfo','client_basicinfo.id','=','job_openings.client_id');
        $query = $query->leftJoin('users','users.id','=','interview.interviewer_id');
        $query = $query->select('interview.id as id','interview.location', 'interview.interview_name as interview_name','interview.interview_date',
            'client_basicinfo.name as client_name','interview.candidate_id as candidate_id', 'candidate_basicinfo.full_name as full_name','candidate_basicinfo.email as candidate_email','candidate_basicinfo.mobile as candidate_mobile','interview.posting_title as posting_title_id','job_openings.posting_title as posting_title','job_openings.city as job_city','job_openings.state as job_state','job_openings.country as job_country','interview.type as interview_type','interview.skype_id as skype_id','interview.candidate_location as candidate_location','interview.interview_location as interview_location','job_openings.remote_working as remote_working');
        $query = $query->where('interview.id',$id);
        $query = $query->orderBy('interview.interview_date','asc');
        $response = $query->first();

        return $response;
    }

    public static function getDailyReportInterview($user_id,$date=NULL) {

        $from_date = date("Y-m-d 00:00:00");
        $to_date = date("Y-m-d 23:59:59");

        $query = Interview::query();
        $query = $query->join('job_openings','job_openings.id','=','interview.posting_title');
        $query = $query->join('candidate_basicinfo','candidate_basicinfo.id','=','interview.candidate_id');
        $query = $query->select('job_openings.posting_title as posting_title','job_openings.city as location','interview.interview_date as date', 'interview.location as interview_location','interview.type as interview_type','candidate_basicinfo.full_name as cname','candidate_basicinfo.city as ccity','candidate_basicinfo.mobile as cmobile','candidate_basicinfo.email as cemail','job_openings.remote_working as remote_working');
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
            
            $datearray = explode(' ', $value->date);
            $response[$i]['interview_date'] = $datearray[0];
            $response[$i]['interview_time'] = $datearray[1];
            $response[$i]['interview_location'] = $value->interview_location;
            $response[$i]['interview_type'] = $value->interview_type;
            $response[$i]['cname'] = $value->cname;
            $response[$i]['ccity'] = $value->ccity;
            $response[$i]['cmobile'] = $value->cmobile;
            $response[$i]['cemail'] = $value->cemail;

            if($value->remote_working == '1') {

                $response[$i]['location'] = "Remote";
            }
            else {

                $response[$i]['location'] = $value->location;
            }

            $i++;
        }
        return $response;
    }

    public static function getWeeklyReportInterview($user_id,$from_date=NULL,$to_date=NULL) {

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
        return $response;
    }

    public static function getUserWiseMonthlyReportInterview($users,$month,$year) {

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

        if($query_response->count()>0) {

            foreach ($query_response  as $k=>$v) {
                $interview_count[$v->interview_owner_id] = $v->count;
            }
        }
        return $interview_count;
    }

    public static function getMonthlyReportInterview($user_id,$month,$year) {

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

    public static function getInterviewids($interview_id) {

        $interviewDetails = Interview::join('candidate_basicinfo','candidate_basicinfo.id','=','interview.candidate_id')->join('candidate_otherinfo','candidate_otherinfo.candidate_id','=','candidate_basicinfo.id')->join('job_openings','job_openings.id','=','interview.posting_title')->join('client_basicinfo','client_basicinfo.id','=','job_openings.client_id')->select('candidate_otherinfo.owner_id as candidate_owner_id','client_basicinfo.account_manager_id as client_owner_id')->where('interview.id','=',$interview_id)->first();

            return $interviewDetails;
    }

    public static function getCandidateOwnerEmail($interview_id) {

        $query = Interview::query();
        $query = $query->join('candidate_otherinfo','candidate_otherinfo.candidate_id','=','interview.candidate_id');
        $query = $query->join('users','users.id','=','candidate_otherinfo.owner_id');
        $query = $query->where('interview.id','=',$interview_id);
        $query = $query->select('users.email as candidateowneremail','users.secondary_email as candidateownersemail');
        $res = $query->first();

        return $res;
    }

    public static function getClientOwnerEmail($interview_id) {

        $query = Interview::query();
        $query = $query->join('job_openings','job_openings.id','=','interview.posting_title');
        $query = $query->join('client_basicinfo','client_basicinfo.id','=','job_openings.client_id');
        $query = $query->leftjoin('users','users.id','=','client_basicinfo.account_manager_id');
        $query = $query->where('interview.id','=',$interview_id);
        $query = $query->select('users.email as clientowneremail','users.secondary_email as clientownersemail');
        $response = $query->first();

        return $response;
    }

    public static function getSecondlineClientOwnerEmail($interview_id) {

        $query = Interview::query();
        $query = $query->join('job_openings','job_openings.id','=','interview.posting_title');
        $query = $query->join('client_basicinfo','client_basicinfo.id','=','job_openings.client_id');
        $query = $query->leftjoin('users','users.id','=','client_basicinfo.second_line_am');
        $query = $query->where('interview.id','=',$interview_id);
        $query = $query->select('users.email as secondlineclientowneremail','users.secondary_email as secondlineclientownersemail');
        $response = $query->first();

        return $response;
    }

    public static function getCandidateEmail($user_id,$candidate_id,$posting_title,$interview_id) {

        $from_name = getenv('FROM_NAME');
        $from_address = getenv('FROM_ADDRESS');
        $app_url = getenv('APP_URL');

        $input['from_name'] = $from_name;
        $input['from_address'] = $from_address;
        //$input['to'] = $user_email;
        $input['app_url'] = $app_url;

        // get user company description by logged in user
        $user_company_details = User::getCompanyDetailsByUserID($user_id);

        $candidate_email = Interview::getCandidateOwnerEmail($interview_id);
        $candidate_owner_email = $candidate_email->candidateowneremail;

        $client_email = Interview::getClientOwnerEmail($interview_id);
        $client_owner_email = $client_email->clientowneremail;

        $secondline_client_email = Interview::getSecondlineClientOwnerEmail($interview_id);

        if(isset($secondline_client_email->secondlineclientowneremail) && $secondline_client_email->secondlineclientowneremail != '') {
            $secondline_client_owner_email = $secondline_client_email->secondlineclientowneremail;
        }

        // Candidate details
        $candidate_response  = CandidateBasicInfo::find($candidate_id);
        $cname = $candidate_response->full_name;

        $to_address = array();
        $to_address[] = $candidate_owner_email;
        $to_address[] = $client_owner_email;

        if(isset($secondline_client_owner_email) && $secondline_client_owner_email != '') {
            $to_address[] = $secondline_client_owner_email;
        }

        $input['to'] = $to_address;

        // job Details
        $job_details = JobOpen::getJobById($posting_title);
        $attachments = JobOpenDoc::getJobDocByJobId($posting_title,'Job Description');

        $file_path_array = array();
        $j=0;

        if (isset($attachments) && $attachments != '') {

            foreach ($attachments as $key => $value) {

                if (isset($value) && $value != '') {
                    $file_path = public_path() . "/" . $value['file'];
                }
                else {
                    $file_path = '';
                }
                
                $file_path_array[$j] = $file_path;
                $j++;
            }
        }

        // Get Interview Date & Time
        $interview = Interview::getInterviewById($interview_id);
        $datearray = explode(' ', $interview->interview_date);
        $interview_date = $datearray[0];
        $interview_time = $datearray[1];

        $interview_type = $interview->interview_type;
        $skype_id = $interview->skype_id;

        $input['cname'] = $cname;
        $input['company_name'] = $job_details['company_name'];
        $input['company_url'] = $job_details['company_url'];
        $input['company_desc'] = $user_company_details['description'];
        $input['client_desc'] = $job_details['client_desc'];
        $input['job_designation'] = $job_details['new_posting_title'];
        $input['job_location'] = $job_details['job_location'];
        $input['job_description'] = $job_details['job_description'];
        $input['interview_date'] = $interview_date;
        $input['interview_time'] = $interview_time;
        $input['interview_location'] = $job_details['interview_location'];
        $input['contact_person'] = $job_details['contact_person'];
        $input['interview_type'] = $interview_type;
        $input['skype_id'] = $skype_id;
        $input['city'] = $job_details['city'];

        if (isset($file_path_array) && sizeof($file_path_array) > 0) {
            $input['file_path'] = $file_path_array;
        }

        \Mail::send('adminlte::emails.interviewcandidate', $input, function ($message) use($input) {
            $message->from($input['from_address'], $input['from_name']);
            $message->to($input['to'])->subject('Interview Details - '.$input['company_name'].' - '. $input['city']);

            if (isset($input['file_path']) && sizeof($input['file_path']) > 0) {

                foreach ($input['file_path'] as $k1 => $v1) {

                    if(isset($v1) && $v1 != '') {
                        $message->attach($v1);
                    }
                }
            }
        });
    }

    public static function getScheduleEmail($candidate_id,$posting_title,$interview_id) {

        $from_name = getenv('FROM_NAME');
        $from_address = getenv('FROM_ADDRESS');
        $app_url = getenv('APP_URL');

        $candidate_email = Interview::getCandidateOwnerEmail($interview_id);
        $candidate_owner_email = $candidate_email->candidateowneremail;

        $client_email = Interview::getClientOwnerEmail($interview_id);
        $client_owner_email = $client_email->clientowneremail;

        $secondline_client_email = Interview::getSecondlineClientOwnerEmail($interview_id);

        if(isset($secondline_client_email->secondlineclientowneremail) && $secondline_client_email->secondlineclientowneremail != '') {
            $secondline_client_owner_email = $secondline_client_email->secondlineclientowneremail;
        }

        // Candidate details
        $candidate_response  = CandidateBasicInfo::find($candidate_id);
        $cname = $candidate_response->full_name;

        $to_address = array();
        $to_address[] = $candidate_owner_email;
        $to_address[] = $client_owner_email;

        if(isset($secondline_client_owner_email) && $secondline_client_owner_email != '') {
            $to_address[] = $secondline_client_owner_email;
        }
        
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
        $attachments = CandidateUploadedResume::getCandidateAttachment($candidate_id);
        $file_path_array = array();
        $j=0;

        if (isset($attachments) && $attachments != '') {

            foreach ($attachments as $key => $value) {

                if (isset($value) && $value != '') {

                    $file_path = public_path() . "/" . $value->file;
                }
                else {
                    $file_path = '';
                }
                
                $file_path_array[$j] = $file_path;
                $j++;
            }
        }

        $interview = Interview::getInterviewById($interview_id);

        $location ='';
        if($interview->job_city!=''){
            $location .= $interview->job_city;
        }
        if($interview->job_state!=''){
            if($location=='')
                $location .= $interview->job_state;
            else
                $location .= ", ".$interview->job_state;
        }
        if($interview->job_country!=''){
            if($location=='')
                $location .= $interview->job_country;
            else
                $location .= ", ".$interview->job_country;
        }

        $datearray = explode(' ', $interview->interview_date);
        $interview_date = $datearray[0];
        $interview_time = $datearray[1];

        $input['cname'] = $cname;
        $input['ccity'] = '';
        $input['cmobile'] = $cmobile;
        $input['cemail'] = $cemail;


        $input['job_designation'] = $interview->posting_title;

        if(isset($interview->remote_working) && $interview->remote_working == '1') {
            
            $input['job_location'] = "Remote";
            $input['city'] = "Remote";
        }
        else {
            $input['job_location'] = $location;
            $input['city'] = $interview->job_city;
        }

        $input['interview_date'] = $interview_date;
        $input['interview_time'] = $interview_time;
        $input['interview_type'] =$interview->interview_type;
        $input['skype_id'] = $interview->skype_id;
        $input['candidate_location'] = $interview->candidate_location;
        $input['company_name'] = $interview->client_name;
        $input['interview_location'] = $interview->interview_location;

        if (isset($file_path_array) && sizeof($file_path_array) > 0) {
            $input['file_path'] = $file_path_array;
        }

        \Mail::send('adminlte::emails.interviewschedule', $input, function ($message) use($input) {

            if($input['city'] == "Remote") {

                $message->from($input['from_address'], $input['from_name']);
                $message->to($input['to_address'])->subject('Interview Schedule for '.$input['company_name'].' Position  - '. $input['city']);
            }
            else {

                $message->from($input['from_address'], $input['from_name']);
                $message->to($input['to_address'])->subject('Interview Schedule for '.$input['company_name'].' Position in '. $input['city']);
            }

            if (isset($input['file_path']) && sizeof($input['file_path']) > 0) {

                foreach ($input['file_path'] as $k1 => $v1) {

                    if(isset($v1) && $v1 != '') {
                        $message->attach($v1);
                    }
                }
            }
        });
    }

    public static function ScheduleMailMultiple($value) {

        $interview_data = Interview::find($value);

        $client_email = Interview::getClientOwnerEmail($value);
        $client_owner_email = $client_email->clientowneremail;

        $secondline_client_email = Interview::getSecondlineClientOwnerEmail($value);

        if(isset($secondline_client_email->secondlineclientowneremail) && $secondline_client_email->secondlineclientowneremail != '') {
            $secondline_client_owner_email = $secondline_client_email->secondlineclientowneremail;
        }

        // Candidate details
        $candidate_response  = CandidateBasicInfo::find($interview_data['candidate_id']);
        $cname = $candidate_response->full_name;
        $cmobile = $candidate_response->mobile;
        $cemail = $candidate_response->email;

        // Candidate Attachments
        $attachments = CandidateUploadedResume::getCandidateAttachment($interview_data['candidate_id']);
        $file_path_array = array();
        $j=0;

        if (isset($attachments) && $attachments != '') {

            foreach ($attachments as $k1 => $v1) {

                if (isset($v1) && $v1 != '') {

                    $file_path = public_path() . "/" . $v1->file;
                }
                else {
                    $file_path = '';
                }
                
                $file_path_array[$j] = $file_path;
                $j++;
            }
        }

        $interview = Interview::getInterviewById($value);

        $location ='';
        if($interview->job_city!=''){
            $location .= $interview->job_city;
        }
        if($interview->job_state!=''){
            if($location=='')
                $location .= $interview->job_state;
            else
                $location .= ", ".$interview->job_state;
        }
        if($interview->job_country!=''){
            if($location=='')
                $location .= $interview->job_country;
            else
                $location .= ", ".$interview->job_country;
        }

        $city = $interview->job_city;

        $datearray = explode(' ', $interview->interview_date);
        $interview_date = $datearray[0];
        $interview_time = $datearray[1];

        $interview_details = array();
        $interview_details['cname'] = $cname;
        $interview_details['cmobile'] = $cmobile;
        $interview_details['cemail'] = $cemail;
        $interview_details['job_designation'] = $interview->posting_title;
        $interview_details['client_name'] = $interview->client_name;

        if(isset($interview->remote_working) && $interview->remote_working == '1') {
            $interview_details['job_location'] = "Remote";
        }
        else {
            $interview_details['job_location'] = $city;
        }

        $interview_details['interview_date'] = $interview_date;
        $interview_details['interview_time'] = $interview_time;
        $interview_details['interview_type'] =$interview->interview_type;
        $interview_details['client_owner_email'] = $client_owner_email;
        $interview_details['skype_id'] = $interview->skype_id;
        $interview_details['candidate_location'] = $interview->candidate_location;
        $interview_details['interview_location'] = $interview->interview_location;

        if (isset($file_path_array) && sizeof($file_path_array) > 0) {
            $interview_details['file_path'] = $file_path_array;
        }
        else {
            $interview_details['file_path'] = '';
        }

        if(isset($secondline_client_owner_email) && $secondline_client_owner_email != '') {
            $interview_details['secondline_client_owner_email'] = $secondline_client_owner_email;
        }
        
        return $interview_details;
    }

    public static function getInterviewIdInASCDate($ids) {

        $interview_ids = explode(',', $ids);

        $query = Interview::query();
        $query = $query->select('interview.id','interview.interview_date');
        $query = $query->whereIn('interview.id',$interview_ids);
        $query = $query->orderBy('interview.interview_date','asc');
        $res = $query->get();
        
        $interview_id = array();
        $i = 0;
        foreach ($res as $key => $value) {
            $interview_id[$i] = $value->id;
            $i++;
        }
        return $interview_id;
    }

    // function for get interview by ids for todos edit,show page
    public static function getTodosInterviewsByIds($ids) {

        $query = Interview::query();
        $query = $query->join('candidate_basicinfo','candidate_basicinfo.id','=','interview.candidate_id');
        $query = $query->join('job_openings','job_openings.id','=','interview.posting_title');
        $query = $query->join('client_basicinfo','client_basicinfo.id','=','job_openings.client_id');
        $query = $query->leftJoin('users','users.id','=','interview.interviewer_id');
        $query = $query->select('interview.id as id','interview.location', 'interview.interview_name as interview_name','interview.interview_date',
            'client_basicinfo.name as client_name','interview.candidate_id as candidate_id', 'candidate_basicinfo.full_name as candidate_fname','candidate_basicinfo.lname as candidate_lname', 'interview.posting_title as posting_title_id','job_openings.posting_title as posting_title','job_openings.city as job_city','job_openings.state as job_state','job_openings.country as job_country','interview.type as interview_type','interview.skype_id as skype_id','interview.candidate_location as candidate_location','job_openings.remote_working as remote_working');
        $query = $query->whereIn('interview.id',$ids);
        $query = $query->orderBy('interview.interview_date','asc');
        $response = $query->get();

        return $response;
    }

    public static function getProductivityReportInterviewCount($user_id=0,$from_date=NULL,$to_date=NULL) {

        $query = Interview::query();
        $query = $query->leftjoin('candidate_otherinfo','candidate_otherinfo.candidate_id','=','interview.candidate_id');
        $query = $query->select(\DB::raw("COUNT(interview.candidate_id) as count"));

        if(isset($user_id) && $user_id > 0) {
            $query = $query->where('candidate_otherinfo.owner_id','=',$user_id);
        }

        $query = $query->where('interview.interview_date','>=',$from_date);

        $to_date = date("Y-m-d 23:59:59",strtotime($to_date));
        $query = $query->where('interview.interview_date','<=',$to_date);
        $query = $query->where('interview.status','=','Attended');
     
        $query = $query->groupBy(\DB::raw('Date(interview.interview_date)'));
        $query_response = $query->get();
       
        $cnt= 0;
        
        foreach ($query_response as $key => $value) {
            $cnt += $value->count;
        }
        return $cnt;
    }

    public static function getAllInterviewsByReminders($user_id,$from_date='',$to_date='') {

        $query = Interview::query();
        $query = $query->join('candidate_basicinfo','candidate_basicinfo.id','=','interview.candidate_id');
        $query = $query->join('candidate_otherinfo','candidate_otherinfo.candidate_id','=','candidate_basicinfo.id');
        $query = $query->join('users','users.id','=','candidate_otherinfo.owner_id');
        $query = $query->join('job_openings','job_openings.id','=','interview.posting_title');
        $query = $query->join('client_basicinfo','client_basicinfo.id','=','job_openings.client_id');
        $query = $query->select('interview.id as id','interview.interview_date','client_basicinfo.name as client_name','candidate_basicinfo.full_name as full_name','candidate_basicinfo.email as candidate_email','job_openings.posting_title as posting_title','job_openings.city as job_city','candidate_basicinfo.mobile as candidate_mobile','users.name as candidate_owner','interview.type as interview_type','interview.interview_location as interview_location','interview.candidate_location as candidate_location','interview.skype_id as skype_id','interview.candidate_id as candidate_id','client_basicinfo.account_manager_id as am_id','job_openings.remote_working as remote_working');

        $query = $query->orderby('interview.interview_date','asc');

        $query = $query->where(function($query) use ($user_id) {

            $query = $query->where('client_basicinfo.account_manager_id',$user_id);
            $query = $query->orwhere('candidate_otherinfo.owner_id',$user_id);
        });

        if(isset($from_date) && $from_date != '' && isset($to_date) && $to_date != '') {

            $query = $query->where('interview_date','>=',"$from_date");
            $query = $query->where('interview_date','<=',"$to_date");
        }

        $response = $query->get();

        $interview = array();
        $i=0;

        foreach ($response as $key => $value) {

            $interview[$i]['id'] = $value->id;
            $interview[$i]['am_id'] = $value->am_id;
            $interview[$i]['client_name'] = $value->client_name;
            $interview[$i]['posting_title'] = $value->posting_title;
            $interview[$i]['interview_type'] = $value->interview_type;
            $interview[$i]['interview_location'] = $value->interview_location;
            
            $interview[$i]['candidate_id'] = $value->candidate_id;
            $interview[$i]['candidate_owner'] = $value->candidate_owner;
            $interview[$i]['cname'] = $value->full_name;
            $interview[$i]['cemail'] = $value->candidate_email;
            $interview[$i]['cmobile'] = $value->candidate_mobile;
            $interview[$i]['candidate_location'] = $value->candidate_location;
            $interview[$i]['skype_id'] = $value->skype_id;

            $datearray = explode(' ', $value->interview_date);
            $interview_date = $datearray[0];
            $interview_time = $datearray[1];
            $interview[$i]['interview_date'] = $interview_date;
            $interview[$i]['interview_time'] = $interview_time;

            $interview[$i]['job_designation'] = $value->posting_title;

            if($value->remote_working == '1') {

                $interview[$i]['job_location'] = "Remote";
            }
            else {

                $interview[$i]['job_location'] = $value->job_city;
            }
            
            $interview[$i]['interview_date_actual'] = $value->interview_date;
            
            $i++;
        }
        return $interview;
    }

    public static function getAttendedInterviewsByWeek($job_id,$from_date=NULL,$to_date=NULL) {

        $query = Interview::query();
        $query = $query->join('candidate_basicinfo','candidate_basicinfo.id','=','interview.candidate_id');

        $query = $query->select('candidate_basicinfo.id as id', 'candidate_basicinfo.full_name as candidate_name');

        if(isset($from_date) && $from_date != NULL) {
        
            $query = $query->where('interview.interview_date','>=',$from_date);

            $to_date = date("Y-m-d 23:59:59",strtotime($to_date));
            $query = $query->where('interview.interview_date','<=',$to_date);
        }
        
        $query = $query->where('interview.status','=','Attended');
        $query = $query->where('interview.posting_title','=',$job_id);
        $query = $query->orderBy('interview.interview_date','desc');
        $response = $query->get();
       
        $list = array();
        $i=0;

        if(isset($response) && sizeof($response) > 0) {

            foreach ($response as $key => $value) {

                $list[$i]['candidate_name'] = $value->candidate_name;
                $i++;
            }
        }
        return $list;
    }

    // function for Interview reminder mail by status
    public static function getInterviewsByStatus($status=array(),$from_date='',$to_date='') {
        
        $query = Interview::query();
        $query = $query->join('candidate_basicinfo','candidate_basicinfo.id','=','interview.candidate_id');
        $query = $query->join('candidate_otherinfo','candidate_otherinfo.candidate_id','=','candidate_basicinfo.id');
        $query = $query->join('users','users.id','=','candidate_otherinfo.owner_id');
        $query = $query->join('job_openings','job_openings.id','=','interview.posting_title');
        $query = $query->join('client_basicinfo','client_basicinfo.id','=','job_openings.client_id');
        $query = $query->select('interview.id as id','interview.interview_owner_id as interview_owner_id','interview.interview_date','client_basicinfo.name as client_name','candidate_basicinfo.full_name as full_name','candidate_basicinfo.email as candidate_email','job_openings.posting_title as posting_title','job_openings.city as job_city','candidate_basicinfo.mobile as candidate_mobile','users.name as candidate_owner','interview.type as interview_type','interview.interview_location as interview_location','interview.candidate_location as candidate_location','interview.skype_id as skype_id','interview.candidate_id as candidate_id','client_basicinfo.account_manager_id as am_id','job_openings.remote_working as remote_working');
        $query = $query->orderby('interview.interview_date','desc');
        if (isset($status) && sizeof($status)>0) {
            $query = $query->whereIn('interview.status',$status);
        }
        if(isset($from_date) && $from_date != '' && isset($to_date) && $to_date != '') {
            $query = $query->where('interview_date','>=',"$from_date");
            $query = $query->where('interview_date','<=',"$to_date");
        }
        $response = $query->get();

        $interview = array();$i=0;
        if (isset($response) && sizeof($response)>0) {
            foreach ($response as $key => $value) {
                $interview[$value->interview_owner_id][$i]['id'] = $value->id;
                $interview[$value->interview_owner_id][$i]['am_id'] = $value->am_id;
                $interview[$value->interview_owner_id][$i]['client_name'] = $value->client_name;
                $interview[$value->interview_owner_id][$i]['posting_title'] = $value->posting_title;
                $interview[$value->interview_owner_id][$i]['interview_type'] = $value->interview_type;
                $interview[$value->interview_owner_id][$i]['interview_location'] = $value->interview_location;
                $interview[$value->interview_owner_id][$i]['candidate_id'] = $value->candidate_id;
                $interview[$value->interview_owner_id][$i]['candidate_owner'] = $value->candidate_owner;
                $interview[$value->interview_owner_id][$i]['cname'] = $value->full_name;
                $interview[$value->interview_owner_id][$i]['cemail'] = $value->candidate_email;
                $interview[$value->interview_owner_id][$i]['cmobile'] = $value->candidate_mobile;
                $interview[$value->interview_owner_id][$i]['candidate_location'] = $value->candidate_location;
                $interview[$value->interview_owner_id][$i]['skype_id'] = $value->skype_id;

                $datearray = explode(' ', $value->interview_date);
                $interview_date = $datearray[0];
                $interview_time = $datearray[1];
                $interview[$value->interview_owner_id][$i]['interview_date'] = $interview_date;
                $interview[$value->interview_owner_id][$i]['interview_time'] = $interview_time;

                $interview[$value->interview_owner_id][$i]['job_designation'] = $value->posting_title;

                if($value->remote_working == '1') {
                    $interview[$value->interview_owner_id][$i]['job_location'] = "Remote";
                }
                else {
                    $interview[$value->interview_owner_id][$i]['job_location'] = $value->job_city;
                }
                $interview[$value->interview_owner_id][$i]['interview_date_actual'] = $value->interview_date;
                
                $i++;
            }
        }
        return $interview;
    }
}