<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\JobAssociateCandidates;
use App\Lead;
use App\User;
use App\Interview;

class ReportController extends Controller
{
    /*public function FunctionName($value='')
    {
        $from_name = getenv('FROM_NAME');
        $from_address = getenv('FROM_ADDRESS');
        $to_address = 'meet@trajinfotech.com';

        $users = User::getAllUsers('recruiter');


        foreach ($users as $key => $value) {

            // data of cvs associated
            $from_date = date("Y-m-d 00:00:00");
            $to_date = date("Y-m-d 23:59:59");
            $status = 'CVs sent';
            
            $query = JobAssociateCandidates::query();
            $query = $query->join('job_openings','job_openings.id','=','job_associate_candidates.job_id');
            $query = $query->join('client_basicinfo','client_basicinfo.id','=','job_openings.client_id');
            //$query = $query->join('interview','interview.posting_title','=','job_openings.id');
            $query = $query->select(/*\DB::raw("COUNT(job_associate_candidates.candidate_id) as count"),'job_associate_candidates.*','job_associate_candidates.date as date','job_openings.posting_title as posting_title','client_basicinfo.display_name as company','job_openings.city as location','job_openings.hiring_manager_id as user_ids');
            $query = $query->where('date','>',"$from_date");
            $query = $query->where('date','<',"$to_date");
            $query = $query->where('job_associate_candidates.associate_by','=',$key);

            $associate_res = $query->get();

            $response = array();
            $i = 0;
            foreach ($associate_res as $key => $value) {
               // $response[$i]['id'] = $value->id;
                $response[$i]['date'] = $value->date;
                $response[$i]['posting_title'] = $value->posting_title;
                $response[$i]['company'] = $value->company;
                $response[$i]['location'] = $value->location;
                $response[$i]['associate_candidate_count'] = $value->count;
                $response[$i]['status'] = $status;
                $i++;
            }
            //print_r($response);exit;
            return $response;    

            // count of cvs associated


            // interview schedule    

        }
    }*/


    public function dailyreport(){

        $from_name = getenv('FROM_NAME');
        $from_address = getenv('FROM_ADDRESS');
        $to_address = 'meet@trajinfotech.com';

        $users = User::getAllUsers('recruiter');
        //print_r($users);exit;

            $input = array();
            $input['from_name'] = $from_name;
            $input['from_address'] = $from_address;
            $input['to'] = $to_address;
        foreach ($users as $key => $value) {

            $associate_daily = JobAssociateCandidates::getDailyReportAssociate();
            $lead_daily = Lead::getDailyReportLead();
            $interview_daily = Interview::getDailyReportInterview();

            $associate_count = sizeof($associate_daily);
            $lead_count = sizeof($lead_daily);
            $interview_count = sizeof($interview_daily);

            $input['associate_daily'] = $associate_daily;

            foreach($associate_daily as $key1=>$value1){
            $input['associate_count'] = $associate_count;
            $input['posting_title'] = $value1['posting_title'];
            $input['company'] = $value1['company'];
            $input['location'] = $value1['location'];
            $input['associate_candidate_count'] = $value1['associate_candidate_count'];
            $input['status'] = $value1['status'];
            }
            $input['lead_count'] = $lead_count;

            $input['interview_daily'] = $interview_daily;

            foreach ($interview_daily as $key2 => $value2) {
            	$input['interview_location'] = $value2['interview_location'];
            	$input['cname'] = $value2['cname'];
            	$input['interview_date'] = $value2['interview_date'];
            	$input['interview_time'] = $value2['interview_time'];
            	$input['ccity'] = $value2['ccity'];
            	$input['interview_type'] = $value2['interview_type'];
            	$input['cmobile'] = $value2['cmobile'];
            	$input['cemail'] = $value2['cemail'];
            	$input['interview_count'] =$interview_count;
            }

            $input['value'] = $value;

       /* \Mail::send('adminlte::emails.DailyReport', $input, function ($message) use($input) {
            $message->from($input['from_address'], $input['from_name']);
            $message->to($input['to'])->subject('Activity Report (Daily Report & Interview Report) - '.$input['value']);
        });*/
    
        return view('adminlte::emails.DailyReport', compact('associate_daily','associate_count','lead_count','interview_daily','interview_count','users'));
        }
    }

    public function weeklyreport(){

    	$from_name = getenv('FROM_NAME');
        $from_address = getenv('FROM_ADDRESS');
        $to_address = 'meet@trajinfotech.com';

        $input['from_name'] = $from_name;
        $input['from_address'] = $from_address;
        $input['to'] = $to_address;


		\Mail::send('adminlte::emails.WeeklyReport', $input, function ($message) use($input) {
            $message->from($input['from_address'], $input['from_name']);
            $message->to($input['to'])->subject('Weelky Activity Report ');
        });

    	//return view('adminlte::emails.WeeklyReport');
    }
}
