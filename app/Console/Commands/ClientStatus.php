<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\ClientBasicinfo;
use App\JobOpen;
use App\Date;
use DB;
use App\JobAssociateCandidates;

class ClientStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'client:status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command To Get Status Of Clients.';

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
        //echo "Testing";exit;

        $active_clients = array();

        $job_data = \DB::select(\DB::raw('SELECT id,job_id,client_id,created_at FROM job_openings WHERE created_at IN (SELECT MAX(created_at) FROM job_openings GROUP BY client_id)'));

        //print_r($job_data);exit;

        $client = ClientBasicinfo::query();
        $client = $client->select('client_basicinfo.id');
        $client_res = $client->get();

        //print_r($client_res);exit;

        $j = 0;
        foreach ($client_res as $key => $value) {
            $clientids[$j] = $value->id;
            $j++;
        }

        //print_r($clientids);exit;

        /*$i = 0;
        foreach ($client_res as $key => $value) {
            $client_id = $value->id;
            $job_res = JobOpen::getClientJobDetails($client_id);

            $job_data = JobOpen::getClientJobIdByDESCDate($job_res);

            $job_associated_data = JobAssociateCandidates::getClientJobAssociatedIdByDESCDate($job_res);
            //print_r($job_associated_data);exit;*/
        
        if(isset($job_data) && $job_data != ''){
            $i = 0;
             foreach($job_data as $key1=>$value1){
                $client_id=$value1->client_id;

                //echo $client_id;exit;

                $job_created_at = $value1->created_at; // job added date
                //echo $job_created_at;exit;

                // $job_associated_created_at = $job_associated_data->created_at; // job candidate associate date

                // if client status is 2(i.e for leaders) and status is 3 (i.e for forbid) ignore that clients
                $client_status_query = ClientBasicinfo::query();
                $client_status_query = $client_status_query->where('id',$client_id);
                $client_res = $client_status_query->first();
                $client_status = $client_res->status;

                //echo $client_status;exit;

                if($client_status==2 or $client_status==3 or $client_status==4)
                     continue;

                $date1=date('Y-m-d',strtotime("-30 days"));
                //echo $date1;exit;

                if(isset($job_created_at)){
                   if(($job_created_at /*|| $job_associated_created_at*/) < $date1){

                        //DB::statement("UPDATE client_basicinfo SET `status`='0' WHERE `id`='$client_id'");

                        // set passive date for passive clients
                        $today_date = date('Y-m-d');
                        DB::statement("UPDATE client_basicinfo SET `status`='0',`passive_date` = '$today_date' WHERE `id`='$client_id'");

                        echo " status - 0 :".$client_id;
                   }
                   else{
                        DB::statement("UPDATE client_basicinfo SET `status`='1' WHERE `id`='$client_id'");
                       $active_clients[] = $client_id;
                        echo " status - 1 :".$client_id;
                   }
                }
                else{
                    //DB::statement("UPDATE client_basicinfo SET `status`='0' WHERE `id`='$client_id'");

                    // set passive date for passive clients
                    $today_date = date('Y-m-d');
                    DB::statement("UPDATE client_basicinfo SET `status`='0',`passive_date` = '$today_date' WHERE `id`='$client_id'");

                    echo " status - 0 :".$client_id;
                }
            //} 
                if (array_search($value1->client_id,$clientids)) {
                    unset($clientids[array_search($value1->client_id,$clientids)]);
                }
                $i++;
            }
        }

        /*foreach ($clientids as $key => $value) {

            // if client status is 2(i.e for leaders), status is 3 (i.e for forbid) and status is 4 (i.e for left) ignore that clients
            $client_status_query = ClientBasicinfo::query();
            $client_status_query = $client_status_query->where('id',$value);
            $client_res = $client_status_query->first();
            $client_status = $client_res->status;

            if($client_status==2 or $client_status==3 or $client_status==4)
                continue;

            //DB::statement("UPDATE client_basicinfo SET `status`='0' WHERE `id`='$value'");

            // set passive date for passive clients
            $today_date = date('Y-m-d');
            DB::statement("UPDATE client_basicinfo SET `status`='0',`passive_date` = '$today_date' WHERE `id`='$value'");

            echo " status - 0 :".$value;
        }*/

        foreach ($clientids as $key => $value) {

            // if client status is 2(i.e for leaders), status is 3 (i.e for forbid) and status is 4 (i.e for left) ignore that clients

            $client_status_query = ClientBasicinfo::query();
            $client_status_query = $client_status_query->where('id',$value);
            $client_res = $client_status_query->first();
            $client_status = $client_res->status;

            if($client_status==2 or $client_status==3 or $client_status==4)
                continue;

            // Set Passive date for Passive Clients

            // Get Created_at data of client
            $client_created_at_date = $client_res->created_at;

            // Get one month before date from current date
            $before_one_month_date = date('Y-m-d',strtotime("-30 days"));

            if($client_created_at_date < $before_one_month_date){

                $today_date = date('Y-m-d');
                DB::statement("UPDATE client_basicinfo SET `status`='0',`passive_date` = '$today_date' WHERE `id`='$value'");
                echo " status - 0 :".$value;
            }
            else{
                
                DB::statement("UPDATE client_basicinfo SET `status`='1' WHERE `id`='$value'");
                echo " status - 1 :".$value;
            }
        }

        // get all jobs which are created before 1 month
        $date1=date('Y-m-d 00:00:00',strtotime("-30 days"));
        $jo_query = JobOpen::query();
        $jo_query = $jo_query->where('created_at','<',"$date1");
        $job_res = $jo_query->get();

        $all_jobs = array();
        $i = 0;
        foreach ($job_res as $k=>$v){
            $all_jobs[$i]['job_id'] = $v->id;
            $all_jobs[$i]['client_id'] = $v->client_id;
            $i++;
        }
        //print_r($all_jobs);exit;

        // in that jobs find in which jobs no cvs are associated within 1 month and get their client ids
        $j = 0;
        foreach ($all_jobs as $k=>$v){
            $j_id[$j] = $v['job_id'];
            $c_id[$j] = $v['client_id'];

            $jo_query1 = JobAssociateCandidates::query();
            $jo_query1 = $jo_query1->where('job_associate_candidates.job_id',$j_id[$j]);
            $jo_query1 = $jo_query1->where('job_associate_candidates.created_at','>=',"$date1");
            $job_res = $jo_query1->first();
            
            if(isset($job_res->job_id))
            {
               // get client id from job id
               $jo_query2 = JobOpen::query();
               $jo_query2 = $jo_query2->where('id',$job_res->job_id);
               $client_res2 = $jo_query2->first();
               $active_clients[]= $client_res2->client_id;

                // if client status is 2(i.e for leaders), status is 3 (i.e for forbid) and status is 4 (i.e for left) ignore that clients
                $client_status_query = ClientBasicinfo::query();
                $client_status_query = $client_status_query->where('id',$client_res2->client_id);
                $client_res = $client_status_query->first();
                $client_status = $client_res['status'];

                if($client_status==2 or $client_status==3 or $client_status==4)
                    continue;

                DB::statement("UPDATE client_basicinfo SET `status`='1' WHERE `id`='$client_res2->client_id'");
            }
            else{

                // if client status is 2(i.e for leaders), status is 3 (i.e for forbid) and status is 4 (i.e for left) ignore that clients
                $client_status_query = ClientBasicinfo::query();
                $client_status_query = $client_status_query->where('id',$c_id[$j]);
                $client_res = $client_status_query->first();
                $client_status = $client_res['status'];

                if($client_status==2 or $client_status==3 or $client_status==4)
                    continue;

                if(!in_array($c_id[$j],$active_clients))

                    //DB::statement("UPDATE client_basicinfo SET `status`='0' WHERE `id`='$c_id[$j]'");

                    // set passive date for passive clients
                    $today_date = date('Y-m-d');
                    DB::statement("UPDATE client_basicinfo SET `status`='0',`passive_date` = '$today_date' WHERE `id`='$c_id[$j]'");
            }
            $j++;
        }
    }
}
