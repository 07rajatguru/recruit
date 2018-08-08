<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\JobAssociateCandidates;
use App\Lead;
use App\User;
use App\Interview;

class WeeklyReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'report:weekly';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command to Sent Weekly Report';

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
        // $to_address = 'rajlalwani@adlertalent.com';
        // $cc_address = 'tarikapanjwani@gmail.com';
        $app_url = getenv('APP_URL');

        $users = User::getAllUsersEmails('recruiter');

        //$users[4] = 'kazvin@adlertalent.com';
        foreach ($users as $key => $value) {

            $report_email = '';
            $floor_incharge_email = '';
            $res = User::getReportsToUsersEmail($key);

            if(isset($res->remail) && $res->remail!='')
                $report_email = $res->remail;
            if(isset($res->femail) && $res->femail!='')
                $floor_incharge_email = $res->femail;

            $to_array = array();
            $to_array[] = $value;

            $cc_array = array();
            $cc_array[] = $report_email;
            $cc_array[] = $floor_incharge_email;
            $cc_array[] = 'tarikapanjwani@gmail.com';
            $cc_array[] = 'rajlalwani@adlertalent.com';

            $input['from_name'] = $from_name;
            $input['from_address'] = $from_address;
            $input['app_url'] = $app_url;
            $input['to_array'] = array_unique($to_array);
            $input['cc_array'] = array_unique($cc_array);


            $associate_weekly_response = JobAssociateCandidates::getWeeklyReportAssociate($key,NULL,NULL);

            $associate_weekly = $associate_weekly_response['associate_data'];
            $associate_count = $associate_weekly_response['cvs_cnt'];

            $interview_weekly_response = Interview::getWeeklyReportInterview($key,NULL,NULL);
            $interview_weekly = $interview_weekly_response['interview_data'];
            $interview_count = $interview_weekly_response['interview_cnt'];

            $lead_count = Lead::getWeeklyReportLeadCount($key,NULL,NULL);
            $user_name = User::getUserNameById($key);

            $input['value'] = $user_name;
            $input['associate_weekly'] = $associate_weekly;
            $input['associate_count'] = $associate_count;
            $input['interview_weekly'] = $interview_weekly;
            $input['interview_count'] = $interview_count;
            $input['lead_count'] = $lead_count;


            \Mail::send('adminlte::emails.WeeklyReport', $input, function ($message) use($input) {
                $message->from($input['from_address'], $input['from_name']);
                $message->to($input['to_array'])->cc($input['cc_array'])->subject('Weekly Activity Report -'.$input['value']);
            });
        }
    }
}
