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
        $to_address = 'tarikapanjwani@gmail.com';
        $cc_address = 'tarikapanjwani@gmail.com';

        $users = User::getAllUsersEmails('recruiter');

        $input = array();
        $input['from_name'] = $from_name;
        $input['from_address'] = $from_address;
        $input['to'] = $to_address;
        $input['cc'] = $cc_address;

        foreach ($users as $key => $value) {

            $associate_response = JobAssociateCandidates::getDailyReportAssociate($key);
            $associate_daily = $associate_response['associate_data'];
            $associate_count = $associate_response['cvs_cnt'];

            $lead_count = Lead::getDailyReportLeadCount($key);

            $interview_daily = Interview::getDailyReportInterview($key);

            $input['value'] = $value;
            $input['associate_daily'] = $associate_daily;
            $input['associate_count'] = $associate_count;
            $input['lead_count'] = $lead_count;
            $input['interview_daily'] = $interview_daily;

            \Mail::send('adminlte::emails.dailyReport', $input, function ($message) use($input) {
                $message->from($input['from_address'], $input['from_name']);
                $message->to($input['to'])->cc($input['cc'])->subject('Activity Report (Daily Report & Interview Report) - '.$input['value']);
            });

            //return view('adminlte::emails.DailyReport', compact('associate_daily','associate_count','lead_count','interview_daily','interview_count','users'));
        }
    }

    public function weeklyreport(){

    	$from_name = getenv('FROM_NAME');
        $from_address = getenv('FROM_ADDRESS');
        $to_address = 'meet@trajinfotech.com';
        $cc_address = 'meet@trajinfotech.com';

        $input['from_name'] = $from_name;
        $input['from_address'] = $from_address;
        $input['to'] = $to_address;
        $input['cc'] = $cc_address;

        $users = User::getAllUsersEmails('recruiter');

        foreach ($users as $key => $value) {

            $associate_weekly_response = JobAssociateCandidates::getWeeklyReportAssociate($key);
            $associate_weekly = $associate_weekly_response['associate_data'];
            $associate_count = $associate_weekly_response['cvs_cnt'];

            $interview_weekly_response = Interview::getWeeklyReportInterview($key);
            $interview_weekly = $interview_weekly_response['interview_data'];
            $interview_count = $interview_weekly_response['interview_cnt'];

            $lead_count = Lead::getWeeklyReportLeadCount($key);

            $input['value'] = $value;
            $input['associate_weekly'] = $associate_weekly;
            $input['associate_count'] = $associate_count;
            $input['interview_weekly'] = $interview_weekly;
            $input['interview_count'] = $interview_count;
            $input['lead_count'] = $lead_count;


            \Mail::send('adminlte::emails.WeeklyReport', $input, function ($message) use($input) {
                $message->from($input['from_address'], $input['from_name'])->cc($input['cc']);
                $message->to($input['to'])->subject('Weekly Activity Report -'.$input['value']);
            });

            //return view('adminlte::emails.WeeklyReport',compact('associate_weekly_response','associate_weekly','associate_count','interview_weekly_response','interview_weekly','interview_count','lead_count'));
        }
    }
}
