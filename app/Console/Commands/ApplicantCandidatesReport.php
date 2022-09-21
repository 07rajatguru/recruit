<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\JobOpen;
use App\User;
use App\Events\NotificationMail;

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
        $recruitment = getenv('RECRUITMENT');
        $hr_advisory = getenv('HRADVISORY');
        $type_array = array($recruitment,$hr_advisory);

        $users = User::getAllUsers($type_array);
        if(isset($users) && sizeof($users) > 0) {
            foreach ($users as $key => $value) {
                $jobs = JobOpen::getJobsByMB($key);
                $applicant_data = array();$i=0;
                if(isset($jobs) && sizeof($jobs) > 0) {
                    foreach ($jobs as $k1 => $v1) {
                        if(isset($v1['applicant_candidates']) && sizeof($v1['applicant_candidates']) > 0) {
                            $applicant_data[$i]['applicant_candidates'] = $v1['applicant_candidates'];
                            $applicant_data[$i]['posting_title'] = $v1['posting_title'];
                            $applicant_data[$i]['city'] = $v1['city'];
                            $i++;
                        }
                    }
                }
                // print_r($applicant_data);exit;
                if (isset($applicant_data) && sizeof($applicant_data) > 0) {
                    // Mail Content
                    $report_view = \View::make('adminlte::emails.applicantcandidatesreport',['applicant_data' => $applicant_data]);
                    $report = $report_view->render();

                    $module = "Applicant Candidates Report";
                    $sender_name = $key;
                    $to = User::getUserEmailById($key);
                    $subject = "Applicant Candidates Report - " . $value . " - " . date('d-m-Y');
                    // $subject = $v1['posting_title'] . " - " . $v1['city'] . " - Applicant Candidates Report";
                    $message = $report;
                    $module_id = 0;
                    $cc = "";

                    event(new NotificationMail($module,$sender_name,$to,$subject,$message,$module_id,$cc));
                }
            }
        }
    }
}