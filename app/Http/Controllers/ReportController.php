<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\JobAssociateCandidates;
use App\Lead;
use App\Interview;

class ReportController extends Controller
{
    public function dailyreport(){

        $from_name = getenv('FROM_NAME');
        $from_address = getenv('FROM_ADDRESS');
        $to_address = 'meet@trajinfotech.com';

        $input['from_name'] = $from_name;
        $input['from_address'] = $from_address;
        $input['to'] = $to_address;

        $associate_daily = JobAssociateCandidates::getDailyReportAssociate();
        $lead_daily = Lead::getDailyReportLead();
        $interview_daily = Interview::getDailyReportInterview();

        $associate_count = sizeof($associate_daily);
        $lead_count = sizeof($lead_daily);
        $interview_count = sizeof($interview_daily);

        $input['associate_daily'] = $associate_daily;

        foreach($associate_daily as $key=>$value){
        $input['associate_count'] = $associate_count;
        $input['posting_title'] = $value['posting_title'];
        $input['company'] = $value['company'];
        $input['location'] = $value['location'];
        $input['associate_candidate_count'] = $value['associate_candidate_count'];
        $input['status'] = $value['status'];
        $input['lead_count'] = $lead_count;
        }

        $input['interview_daily'] = $interview_daily;

        foreach ($interview_daily as $key => $value) {
        	$input['interview_location'] = $value['interview_location'];
        	$input['cname'] = $value['cname'];
        	$input['interview_date'] = $value['interview_date'];
        	$input['interview_time'] = $value['interview_time'];
        	$input['ccity'] = $value['ccity'];
        	$input['interview_type'] = $value['interview_type'];
        	$input['cmobile'] = $value['cmobile'];
        	$input['cemail'] = $value['cemail'];
        	$input['interview_count'] =$interview_count;
        }

        \Mail::send('adminlte::emails.DailyReport', $input, function ($message) use($input) {
            $message->from($input['from_address'], $input['from_name']);
            $message->to($input['to'])->subject('Activity Report (Daily Report & Interview Report)');
        });
    
      //  return view('adminlte::emails.DailyReport', compact('associate_daily','associate_count','lead_count','interview_daily','interview_count'));
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
