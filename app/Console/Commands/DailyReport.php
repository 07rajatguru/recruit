<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\User;
use App\JobAssociateCandidates;
use App\Lead;
use App\Interview;

class DailyReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'report:daily';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'command to sent daily report';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $from_name = getenv('FROM_NAME');
        $from_address = getenv('FROM_ADDRESS');
       // $to_address = 'saloni@trajinfotech.com';
       // $cc_address = 'tarikapanjwani@gmail.com';
        $app_url = getenv('APP_URL');

        $users = User::getAllUsersSecondaryEmails('recruiter');
        
        foreach ($users as $key => $value) {

            //Get Reports to Email
            $report_res = User::getUsersReportToEmail($key);
            $report_email = $report_res->secondary_email;

            //Get Floor Incharge Email
            $floor_res = User::getUsersFloorInchargeEmail($key);
            $floor_incharge_email = $floor_res->secondary_email;

            $to_array = array();
            $to_array[] = $value;

            $cc_array = array();
            $cc_array[] = $report_email;
            $cc_array[] = $floor_incharge_email;
            $cc_array[] = 'adler.rgl@gmail.com';
        
            $input = array();
            $input['from_name'] = $from_name;
            $input['from_address'] = $from_address;
            // $input['to'] = $to_address;
            // $input['cc'] = $cc_address;
            $input['app_url'] = $app_url;
            $input['to_array'] = array_unique($to_array);
            $input['cc_array'] = array_unique($cc_array);

            $associate_response = JobAssociateCandidates::getDailyReportAssociate($key,NULL);
            //print_r($associate_response);exit;
            $associate_daily = $associate_response['associate_data'];
            $associate_count = $associate_response['cvs_cnt'];

            $lead_count = Lead::getDailyReportLeadCount($key,NULL);

            $interview_daily = Interview::getDailyReportInterview($key,NULL);
            $user_name = User::getUserNameById($key);

            $input['value'] = $user_name;
            $input['associate_daily'] = $associate_daily;
            $input['associate_count'] = $associate_count;
            $input['lead_count'] = $lead_count;
            $input['interview_daily'] = $interview_daily;

            \Mail::send('adminlte::emails.dailyReport', $input, function ($message) use ($input) {
                $message->from($input['from_address'], $input['from_name']);
                $message->to($input['to_array'])->cc($input['cc_array'])->subject('Daily Activity Report - ' . $input['value'] . ' - ' . date("d-m-Y"));
            });

        }
    }
}
