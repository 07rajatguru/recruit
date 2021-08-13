<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\User;
use App\ClientBasicinfo;
use App\JobOpen;
use App\JobAssociateCandidates;
use App\Interview;
use App\Events\NotificationMail;

class ClientAutoGenerateReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'client:report';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command for Generate Client Report';

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
        $from_date = date('Y-m-d',strtotime("monday this week"));
        $to_date = date('Y-m-d',strtotime("$from_date +6days"));

        $users = User::getAllUsers(NULL,'Yes');

        if(isset($users) && sizeof($users) > 0) {

            foreach ($users as $key => $value) {

                $client_res = ClientBasicinfo::getUserClientsByUserID($key);
                
                if(isset($client_res) && sizeof($client_res) > 0) {

                    foreach ($client_res as $client_res_key => $client_res_value) {

                        $job_ids_array = array();
                        $j = 0;
                     
                        $client_jobs = JobOpen::getAllJobsByCLient($client_res_value->id);

                        if(isset($client_jobs) && sizeof($client_jobs) > 0) {

                            foreach ($client_jobs as $client_jobs_key => $client_jobs_value) {

                                $associate_candidates = JobAssociateCandidates::getAssociatedCandidatesByWeek($client_jobs_value['id'],$from_date,$to_date);

                                $shortlisted_candidates = JobAssociateCandidates::getShortlistedCandidatesByWeek($client_jobs_value['id'],$from_date,$to_date);

                                $attended_interviews = Interview::getAttendedInterviewsByWeek($client_jobs_value['id'],$from_date,$to_date);

                                if((isset($associate_candidates) && sizeof($associate_candidates) > 0) || (isset($shortlisted_candidates) && sizeof($shortlisted_candidates) > 0) || (isset($attended_interviews) && sizeof($attended_interviews) > 0)) {

                                    $job_ids_array[$j] = $client_jobs_value['id'];
                                    $j++;
                                }
                            }
                        }

                        if(isset($job_ids_array) && sizeof($job_ids_array) > 0) {

                            $module = "Hiring Report";
                            $sender_name = $key;
                            $to = User::getUserEmailById($key);
                            $subject = "Hiring Report - " . $client_res_value->name;
                            $message = "";
                            $module_id = implode(",", $job_ids_array);
                            $cc = "";

                            event(new NotificationMail($module,$sender_name,$to,$subject,$message,$module_id,$cc));
                        }
                    }
                }
            }
        }
    }
}