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
use App\RoleUser;

class NewJobOpentoall extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cmd:jobopentoall';

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
        $hr_role_id = getenv('HR');
        
        $recruitment = getenv('RECRUITMENT');
        $hr_advisory = getenv('HRADVISORY');
        $management = getenv('MANAGEMENT');

        $type_array = array($recruitment,$hr_advisory,$management);

        $job_data = JobOpen::getJobforOpentoAll();
        $job = array();
        $i = 0;

        if (isset($job_data) && $job_data != '') {

            foreach ($job_data as $key => $value) {

                $get_role_id = RoleUser::getRoleIdByUserId($value['hiring_manager_id']);

                if($hr_role_id == $get_role_id) {

                }
                else {

                    $job[$i]['id'] = $value['id'];
                    $job_id = $job[$i]['id'];
                    $cv_count = JobAssociateCandidates::getJobAssociatedCvsCount($job_id);

                    if ($cv_count < 5) {

                        $users_array = User::getAllUsers($type_array);
                        $users = array();

                        if(isset($users_array) && sizeof($users_array) > 0) {

                            foreach ($users_array as $k1 => $v1) {
                               
                               $user_details = User::getAllDetailsByUserID($k1);

                               if($user_details->type == '2') {
                                    if($user_details->hr_adv_recruitemnt == 'Yes') {
                                        $users[$k1] = $v1;
                                    }
                               }
                               else {
                                    $users[$k1] = $v1;
                               }    
                            }
                        }

                        if(isset($users) && sizeof($users)>0) {

                            $user_emails = array();
                            JobVisibleUsers::where('job_id',$job_id)->delete();

                            $strategyuserid = getenv('STRATEGYUSERID');
                            $shatakshi_user_id = getenv('SHATAKSHIUSERID');

                            foreach ($users as $key1=>$value1) {

                                if($value['hiring_manager_id'] == $strategyuserid) {

                                    $job_visible_users = new JobVisibleUsers();
                                    $job_visible_users->job_id = $value['id'];
                                    $job_visible_users->user_id = $key1;
                                    $job_visible_users->save();
                                
                                    $email = User::getUserEmailById($key1);
                                    $user_emails[] = $email;
                                }
                                else {

                                    if($key1 == $shatakshi_user_id) {

                                    }
                                    else {

                                        $job_visible_users = new JobVisibleUsers();
                                        $job_visible_users->job_id = $value['id'];
                                        $job_visible_users->user_id = $key1;
                                        $job_visible_users->save();
                                    
                                        $email = User::getUserEmailById($key1);
                                        $user_emails[] = $email;
                                    }
                                }
                            }
                            
                            $superadminsecondemail = User::getUserEmailById($superadminuserid);
                            $cc_user = $superadminsecondemail;

                            $job_details = JobOpen::getJobById($job_id);

                            $client_name = ClientBasicinfo::getCompanyOfClientByID($job_details['client_id']);
                            $client_city = ClientBasicinfo::getBillingCityOfClientByID($job_details['client_id']);

                            $module = "Job Open to All";
                            $sender_name = $superadminuserid;
                            $to = implode(",",$user_emails);
                            $cc = $cc_user;
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
                        \DB::statement("UPDATE job_openings SET open_to_all = $open_to_all where id = $job_id");
                    }
                }
                $i++;
            }
        }
    }
}