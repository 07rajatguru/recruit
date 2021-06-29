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

        $candidate_details = CandidateBasicInfo::getAllCandidatesDetails(0,0,NULL,'','',NULL,'','','','',$from_date,$to_date);

        if(isset($candidate_details) && sizeof($candidate_details) > 0) {

            foreach ($candidate_details as $key => $value) {

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
    }
}