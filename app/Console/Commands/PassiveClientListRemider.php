<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\ClientBasicinfo;
use App\JobOpen;
use App\JobAssociateCandidates;
use App\User;
use App\Events\NotificationMail;

class PassiveClientListRemider extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'passive:clientreminder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command for email of expected passive client in next week.';

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
        $users = User::getAllUsersEmails($recruitment);
    
        foreach ($users as $k1 => $v1) {
            $passiveClients = array();

            // Get All Client id
            $client = ClientBasicinfo::query();
            $client = $client->select('client_basicinfo.id');
            $client = $client->where('client_basicinfo.account_manager_id',$k1);
            $client = $client->where('client_basicinfo.status','=','1');
            $client_res = $client->get();

            $clientids = array();
            foreach ($client_res as $key => $value) {
                $clientids[$value->id] = $value->id;
            }

            if (isset($clientids) && sizeof($clientids) > 0) {
                foreach ($clientids as $key => $value) {

                    // if client status is 2(i.e for leaders),status is 3 (i.e for forbid) and status is 4 (i.e for left) ignore that clients
                    $client_status_query = ClientBasicinfo::query();
                    $client_status_query = $client_status_query->where('id',$value);
                    $client_res = $client_status_query->first();
                    // $client_status = $client_res->status;

                    // if($client_status==2 or $client_status==3 or $client_status==4)
                    //     continue;

                    // Get Created_at data of client
                    $client_created_at_date = $client_res->created_at;

                    // Get 21 days before date from current date
                    $before_21_days_date = date('Y-m-d',strtotime("-21 days"));
                    if($client_created_at_date < $before_21_days_date){
                        // get client id for passive clients
                        $passiveClients[$value] = $value;
                    }
                }
            }

            // $job_data = \DB::select(\DB::raw('SELECT id,job_id,client_id,created_at FROM job_openings WHERE (created_at IN (SELECT MAX(created_at) FROM job_openings GROUP BY client_id) AND hiring_manager_id = '.$k1.')'));

            $job_data = \DB::select(\DB::raw('SELECT job_openings.id,job_openings.job_id,job_openings.client_id,job_openings.created_at FROM job_openings JOIN client_basicinfo ON job_openings.client_id=client_basicinfo.id WHERE (job_openings.created_at IN (SELECT MAX(job_openings.created_at) FROM job_openings GROUP BY client_id) AND hiring_manager_id = '.$k1.' AND client_basicinfo.status = "1")'));

            if(isset($job_data) && $job_data != ''){
                $i = 0;
                foreach($job_data as $key1=>$value1){
                    $client_id=$value1->client_id;

                    $job_created_at = $value1->created_at; // job added date

                    // if client status is 2(i.e for leaders) and status is 3 (i.e for forbid) ignore that clients
                    // $client_status_query = ClientBasicinfo::query();
                    // $client_status_query = $client_status_query->where('id',$client_id);
                    // $client_res = $client_status_query->first();
                    // $client_status = $client_res->status;

                    // if($client_status==2 or $client_status==3 or $client_status==4)
                    //     continue;

                    $date1=date('Y-m-d',strtotime("-21 days"));
                    if(isset($job_created_at)){
                       if(($job_created_at) < $date1){
                            // get client id for passive clients
                            $passiveClients[$client_id] = $client_id;
                       }
                       else {
                            if (array_search($client_id,$passiveClients)) {
                                unset($passiveClients[array_search($client_id,$passiveClients)]);
                            }
                       }
                    }
                    else{
                        // get client id for passive clients
                        $passiveClients[$client_id] = $client_id;
                    }
                    $i++;
                }
            }

            // get all jobs which are created before 21 days
            $date1=date('Y-m-d 00:00:00',strtotime("-21 days"));
            $jo_query = JobOpen::query();
            $jo_query = $jo_query->join('client_basicinfo','client_basicinfo.id','=','job_openings.client_id');
            $jo_query = $jo_query->where('job_openings.created_at','<',"$date1");
            $jo_query = $jo_query->where('job_openings.hiring_manager_id',$k1);
            $jo_query = $jo_query->where('client_basicinfo.status','=','1');
            $jo_query = $jo_query->select('job_openings.id as id','job_openings.client_id as client_id');
            $job_res = $jo_query->get();

            $all_jobs = array();
            $i = 0;
            foreach ($job_res as $k=>$v){
                $all_jobs[$i]['job_id'] = $v->id;
                $all_jobs[$i]['client_id'] = $v->client_id;
                $i++;
            }

            // in that jobs find in which jobs no cvs are associated within 21 days and get their client ids
            $j = 0;
            foreach ($all_jobs as $k=>$v){
                $j_id[$j] = $v['job_id'];
                $c_id[$j] = $v['client_id'];

                $jo_query1 = JobAssociateCandidates::query();
                $jo_query1 = $jo_query1->where('job_associate_candidates.job_id',$j_id[$j]);
                $jo_query1 = $jo_query1->where('job_associate_candidates.created_at','>=',"$date1");
                $job_res1 = $jo_query1->first();
                
                if(isset($job_res1->job_id)) {
                   // get client id from job id
                   $jo_query2 = JobOpen::query();
                   $jo_query2 = $jo_query2->where('id',$job_res1->job_id);
                   $client_res2 = $jo_query2->first();

                    // if client status is 2(i.e for leaders), status is 3 (i.e for forbid) and status is 4 (i.e for left) ignore that clients
                    // $client_status_query = ClientBasicinfo::query();
                    // $client_status_query = $client_status_query->where('id',$client_res2->client_id);
                    // $client_res = $client_status_query->first();
                    // $client_status = $client_res['status'];

                    // if($client_status==2 or $client_status==3 or $client_status==4)
                    //     continue;

                    if (array_search($client_res2->client_id,$passiveClients)) {
                        unset($passiveClients[array_search($client_res2->client_id,$passiveClients)]);
                    }
                }
                // else{

                //     // if client status is 2(i.e for leaders), status is 3 (i.e for forbid) and status is 4 (i.e for left) ignore that clients
                //     $client_status_query = ClientBasicinfo::query();
                //     $client_status_query = $client_status_query->where('id',$c_id[$j]);
                //     $client_res = $client_status_query->first();
                //     $client_status = $client_res['status'];

                //     if($client_status==2 or $client_status==3 or $client_status==4)
                //         continue;

                //     if(!in_array($c_id[$j],$passiveClients))
                //         // get client id for passive clients
                //         $passiveClients[$client_res->client_id] = $client_res->client_id;
                // }
                $j++;
            }

            if (isset($passiveClients) && sizeof($passiveClients) > 0) {
                $superadminuserid = getenv('SUPERADMINUSERID');
                $super_admin_email = User::getUserEmailById($superadminuserid);

                $user_name = User::getUserNameById($k1);

                $to_array = array();
                $to_array[] = $v1;

                $cc_array = array();
                $cc_array[] = $super_admin_email;
                $cc_array[] = 'saloni@trajinfotech.com';

                $module = "Expected Passive Client";
                $subject = 'List of expected passive client in next week - '. $user_name;
                $message = "";
                $to_array = array_filter($to_array);
                $to = implode(",",$to_array);
                $cc_array = array_filter($cc_array);
                $cc = implode(",",$cc_array);
                $module_id = implode(",", $passiveClients);
                $sender_name = $superadminuserid;

                event(new NotificationMail($module,$sender_name,$to,$subject,$message,$module_id,$cc));
            }
        }
    }
}
