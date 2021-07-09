<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\CandidateBasicInfo;
use App\Events\NotificationMail;

class NewCandidateEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'candidate:autoemail';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command for send autoscript email to candidate';

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
        $from_date = date("Y-m-d 00:00:00");
        $to_date = date("Y-m-d 23:59:59");

        $today_candidate_details = CandidateBasicInfo::getAllCandidates($from_date,$to_date);

        if(isset($today_candidate_details) && sizeof($today_candidate_details) > 0) {

            foreach ($today_candidate_details as $key => $value) {

                $module = "New Candidate AutoScript Mail";
                $sender_name = $value['owner_id'];
                $to = $value['email'];
                $subject = 'Thanks for your application - Adler Talent Solution';
                $message = "<tr><td>" . $value['owner'] . " added new Candidate </td></tr>";
                $module_id = $value['id'];

                $cc = '';

                event(new NotificationMail($module,$sender_name,$to,$subject,$message,$module_id,$cc));
            }
        }

        // Get Candidates of Yesterdays if Any Added After 8 PM

        $yesterday_from_date = date('Y-m-d 00:00:00',strtotime("-1 days"));
        $yesterday_to_date = date("Y-m-d 23:59:59", strtotime("-1 days"));
        
        $yesterday_candidate_details = CandidateBasicInfo::getAllCandidates($yesterday_from_date,$yesterday_to_date);

        print_r($yesterday_candidate_details);exit;

        if(isset($yesterday_candidate_details) && sizeof($yesterday_candidate_details) > 0) {

            foreach ($yesterday_candidate_details as $key1 => $value1) {

                $module = "New Candidate AutoScript Mail";
                $sender_name = $value1['owner_id'];
                $to = $value1['email'];
                $subject = 'Thanks for your application - Adler Talent Solution';
                $message = "<tr><td>" . $value1['owner'] . " added new Candidate </td></tr>";
                $module_id = $value1['id'];

                $cc = '';

                event(new NotificationMail($module,$sender_name,$to,$subject,$message,$module_id,$cc));
            }
        }
    }
}