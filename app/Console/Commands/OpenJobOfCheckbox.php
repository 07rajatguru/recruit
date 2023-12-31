<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\User;
use App\JobOpen;
use App\JobVisibleUsers;
use App\JobAssociateCandidates;
use App\ClientBasicinfo;
use App\Events\NotificationMail;
use App\Events\NotificationEvent;

class OpenJobOfCheckbox extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'existjob:opentoall';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command for Job Open to All';

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
        $superadminuserid = getenv('SUPERADMINUSERID');

        $recruitment = getenv('RECRUITMENT');
        $hr_advisory = getenv('HRADVISORY');
        $type_array = array($recruitment,$hr_advisory);

        $job_data = JobOpen::getJobforOpentoAllOneTime();
        $job = array();
        $i = 0;

        if (isset($job_data) && $job_data != '') {

            foreach ($job_data as $key => $value) {

                $job[$i]['id'] = $value['id'];
                $job_id = $job[$i]['id'];

                $cv_count = JobAssociateCandidates::getJobAssociatedCvsCount($job_id);
                
                if ($cv_count < 5) {

                    $users = User::getAllUsers($type_array);

                    if(isset($users) && sizeof($users)>0) {

                        $user_emails = array();
                        JobVisibleUsers::where('job_id',$job_id)->delete();

                        foreach ($users as $key1=>$value1) {

                            $job_visible_users = new JobVisibleUsers();
                            $job_visible_users->job_id = $value['id'];
                            $job_visible_users->user_id = $key1;
                            $job_visible_users->save();
                            
                            $email = User::getUserEmailById($key1);
                            $user_emails[] = $email;
                        }
                        
                        $superadminsecondemail = User::getUserEmailById($superadminuserid);

                        $job_details = JobOpen::getJobById($job_id);

                        $client_name = ClientBasicinfo::getCompanyOfClientByID($job_details['client_id']);
                        $client_city = ClientBasicinfo::getBillingCityOfClientByID($job_details['client_id']);

                        $module = "Job Open to All";
                        $sender_name = $superadminuserid;
                        $to = implode(",",$user_emails);
                        $cc = $superadminsecondemail;

                        $subject = "Job opened by ". $job_details['user_name'] ." - " . $job_details['posting_title'] . " @ " .$client_name . " - " . $client_city;

                        $message = "<tr><th>" . $job_details['posting_title'] . " / " . $job_details['job_unique_id'] . "</th></tr>";

                        $module_id = $job_id;

                        event(new NotificationMail($module,$sender_name,$to,$subject,$message,$module_id,$cc));

                        foreach ($users as $key2=>$value2) {

                            $module_id = $job_id;
                            $module = 'Job Open to All';
                            $message = "Job opened by ". $job_details['user_name'] ." - " . $job_details['posting_title'] . " @ " .$client_name . " - " . $client_city;
                            $link = route('jobopen.show',$job_id);
                            $user_arr = trim($key2);

                            event(new NotificationEvent($module_id, $module, $message, $link, $user_arr));
                        }
                    }
                    
                    $open_to_all = 1;
                    $job_open_checkbox = 0;
                    \DB::statement("UPDATE job_openings SET open_to_all = $open_to_all AND job_open_checkbox = $job_open_checkbox where id = $job_id");
                }
                $i++;
            }
        }
    }
}