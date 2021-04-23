<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\JobOpen;
use App\User;

class ApplicantCandidatesReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'applicantcandidate:report';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command for send applicant candidates report to Job AM';

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
        $from_date = date('Y-m-d 00:00:00',strtotime("-1 days"));
        $to_date = date("Y-m-d 23:59:59", strtotime("-1 days"));

        $users = User::getAllUsers('recruiter');

        if(isset($users) && sizeof($users) > 0) {

            foreach ($users as $key => $value) {

                $jobs = JobOpen::getJobsByMB($key,$from_date,$to_date);

                if(isset($jobs) && sizeof($jobs) > 0) {

                    foreach ($jobs as $k1 => $v1) {

                        if(isset($v1['applicant_candidates']) && sizeof($v1['applicant_candidates']) > 0) {
                        
                            $to = User::getUserEmailById($v1['hiring_manager_id']);
                            $from_name = getenv('FROM_NAME');
                            $from_address = getenv('FROM_ADDRESS');
                            $app_url = getenv('APP_URL');

                            $input['to'] = $to;
                            $input['from_name'] = $from_name;
                            $input['from_address'] = $from_address;
                            $input['app_url'] = $app_url;
                            $input['applicant_candidates'] = $v1['applicant_candidates'];
                            $input['subject'] = $v1['posting_title'] . " - " . $v1['city'] . " - Applicant Candidates Report";

                            \Mail::send('adminlte::emails.applicantcandidatesreport', $input, function ($message) use($input) {

                                $message->from($input['from_address'], $input['from_name']);
                                $message->to($input['to'])->subject($input['subject']);
                            });
                        }
                    }
                }
            }
        }
    }
}