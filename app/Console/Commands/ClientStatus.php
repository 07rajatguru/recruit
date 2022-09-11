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
        $active_clients = array();
        $passive_clients = array();

        // Get one month before date from current date
        $date1 = date('Y-m-d 00:00:00',strtotime("-30 days"));
        // Checked All Active Clients Jobs Created Date before one month and passive that clients
        $job_data = JobOpen::getJobsByClientStatus($date1);
        if(isset($job_data) && $job_data != '') {
            foreach($job_data as $key1 => $value1) {
                $client_id = $value1['client_id'];
                $job_created_at = $value1['created_at'];
                $client_status = $value1['client_status'];

                $jo_query1 = JobAssociateCandidates::query();
                $jo_query1 = $jo_query1->where('job_associate_candidates.job_id',$value1['job_id']);
                $jo_query1 = $jo_query1->where('job_associate_candidates.created_at','>=',"$date1");
                $job_res = $jo_query1->first();
                if(isset($job_res->job_id)) {
                } else {
                    $today_date = date('Y-m-d');
                    DB::statement("UPDATE `client_basicinfo` SET `status`='0',`passive_date` = '$today_date' WHERE `id`='$client_id'");
                    $passive_clients[] = $client_id;
                    echo " status - 0 :".$client_id;
                }
                
            }
        }
        
        // Checked All Active Clients Created Date
        $status_array = array(1);

        $client = ClientBasicinfo::query();
        $client = $client->select('client_basicinfo.id');
        $client = $client->whereIn('client_basicinfo.status',$status_array);
        $client = $client->where('client_basicinfo.delete_client','=',0);
        $client_res = $client->get();
        
        $j = 0;
        foreach ($client_res as $key => $value) {
            $clientids[$j] = $value->id;
            $j++;
        }

        if (isset($clientids) && sizeof($clientids) > 0) {
            foreach ($clientids as $key => $value) {

                $client_status_query = ClientBasicinfo::query();
                $client_status_query = $client_status_query->where('id',$value);
                $client_status_query = $client_status_query->where('client_basicinfo.created_at', '<' ,$date1);
                $client_res1 = $client_status_query->first();
                if (isset($client_res1) && $client_res1 != '') {
                    // Check whether job added on this client in between one month
                    $query = JobOpen::query();
                    $query = $query->select('job_openings.id','job_openings.client_id',\DB::raw("MAX(job_openings.created_at) as created_at"));
                    $query = $query->where('job_openings.client_id',$value);
                    $query = $query->where('job_openings.created_at', '>' ,$date1);
                    $query = $query->groupBy('job_openings.client_id');
                    $job_response = $query->get();
                    if (isset($job_response) && sizeof($job_response) > 0) {
                    } else {
                        $today_date = date('Y-m-d');
                        DB::statement("UPDATE `client_basicinfo` SET `status`='0',`passive_date` = '$today_date' WHERE `id`='$value'");
                        $passive_clients[] = $value;
                        echo " status - 0 :".$value;
                    }
                    
                }
            }
        }

        // Get all jobs which are created before 1 month
        $date1 = date('Y-m-d 00:00:00',strtotime("-30 days"));
        $jo_query = JobOpen::query();
        $jo_query = $jo_query->where('created_at','<',"$date1");
        $job_res = $jo_query->get();

        $all_jobs = array();
        $i = 0;
        foreach ($job_res as $k=>$v) {
            $all_jobs[$i]['job_id'] = $v->id;
            $all_jobs[$i]['client_id'] = $v->client_id;
            $i++;
        }

        // In all jobs find which jobs no cvs are associated within 1 month and get their client ids
        $j = 0;
        foreach ($all_jobs as $k=>$v) {

            $j_id[$j] = $v['job_id'];
            $c_id[$j] = $v['client_id'];

            $jo_query1 = JobAssociateCandidates::query();
            $jo_query1 = $jo_query1->where('job_associate_candidates.job_id',$j_id[$j]);
            $jo_query1 = $jo_query1->where('job_associate_candidates.created_at','>=',"$date1");
            $job_res = $jo_query1->first();
            
            if(isset($job_res->job_id)) {

                // Get client id from job id
                $jo_query2 = JobOpen::query();
                $jo_query2 = $jo_query2->where('id',$job_res->job_id);
                $client_res2 = $jo_query2->first();
                $active_clients[]= $client_res2->client_id;

                $client_status_query = ClientBasicinfo::query();
                $client_status_query = $client_status_query->where('id',$client_res2->client_id);
                $client_res = $client_status_query->first();
                $client_status = $client_res['status'];

                // If client status is 2,3,4 then ignore that clients
                if($client_status==2 or $client_status==3 or $client_status==4)
                    continue;

                DB::statement("UPDATE `client_basicinfo` SET `status`='1',`passive_date` = NULL WHERE `id`='$client_res2->client_id'");
            }
            else {

                $client_status_query = ClientBasicinfo::query();
                $client_status_query = $client_status_query->where('id',$c_id[$j]);
                $client_res = $client_status_query->first();
                $client_status = $client_res['status'];

                // If client status is 2,3,4 then ignore that clients
                if($client_status==2 or $client_status==3 or $client_status==4)
                    continue;

                if(!in_array($c_id[$j],$active_clients)) {

                    // Set passive date for passive clients
                    if ($client_status == 0) {
                        continue;
                    }
                    else {
                        
                        $today_date = date('Y-m-d');
                        DB::statement("UPDATE `client_basicinfo` SET `status`='0',`passive_date` = '$today_date' WHERE `id`='$c_id[$j]'");
                    }
                }
                else {

                    DB::statement("UPDATE `client_basicinfo` SET `status`='1',`passive_date` = NULL WHERE `id`='$c_id[$j]'");
                    echo " status - 1 :".$c_id[$j];
                }
            }
            $j++;
        }


        // Old Script
        // $active_clients = array();

        // //$job_data = \DB::select(\DB::raw('SELECT `id`,`job_id`,`client_id`,`created_at` FROM `job_openings` WHERE `created_at` IN (SELECT MAX(`created_at`) FROM `job_openings` GROUP BY `client_id`)'));

        // // Checked All Active Passive Clients Jobs Created Date
        // $job_data = JobOpen::getJobsByClientStatus();

        // if(isset($job_data) && $job_data != '') {
            
        //     $i = 0;
        //     foreach($job_data as $key1 => $value1) {

        //         $client_id = $value1['client_id'];
        //         $job_created_at = $value1['created_at'];
        //         $client_status = $value1['client_status'];

        //         $date1 = date('Y-m-d',strtotime("-30 days"));

        //         if(($job_created_at) < $date1) {

        //             if ($client_status == 0) {
        //                     continue;
        //             }
        //             else {
                        
        //                 $today_date = date('Y-m-d');
        //                 DB::statement("UPDATE `client_basicinfo` SET `status`='0',`passive_date` = '$today_date' WHERE `id`='$client_id'");

        //                 echo " status - 0 :".$client_id;
        //             }
        //         }
        //         else {
                    
        //             DB::statement("UPDATE `client_basicinfo` SET `status`='1',`passive_date` = NULL WHERE `id`='$client_id'");
        //             $active_clients[] = $client_id;

        //             echo " status - 1 :".$client_id;
        //         }
        //         $i++;
        //     }
        // }
        
        // // Checked All Active Passive Clients Created Date
        // $status_array = array(0,1);

        // $client = ClientBasicinfo::query();
        // $client = $client->select('client_basicinfo.id');
        // $client = $client->whereIn('client_basicinfo.status',$status_array);
        // $client = $client->where('client_basicinfo.delete_client','=',0);
        // $client_res = $client->get();
        
        // $j = 0;
        // foreach ($client_res as $key => $value) {
        //     $clientids[$j] = $value->id;
        //     $j++;
        // }

        // foreach ($clientids as $key => $value) {

        //     $client_status_query = ClientBasicinfo::query();
        //     $client_status_query = $client_status_query->where('id',$value);
        //     $client_res = $client_status_query->first();
        //     $client_status = $client_res->status;

        //     // Set Passive date for Passive Clients

        //     // Get Created_at data of client
        //     $client_created_at_date = $client_res->created_at;

        //     // Get one month before date from current date
        //     $before_one_month_date = date('Y-m-d',strtotime("-30 days"));

        //     if($client_created_at_date < $before_one_month_date) {

        //         if(!in_array($value,$active_clients)) {
        //             if ($client_status == 0) {
        //                 continue;
        //             }
        //             else {

        //                 $today_date = date('Y-m-d');
        //                 DB::statement("UPDATE `client_basicinfo` SET `status`='0',`passive_date` = '$today_date' WHERE `id`='$value'");

        //                 echo " status - 0 :".$value;
        //             }
        //         }
        //     }
        //     else {
                
        //         DB::statement("UPDATE `client_basicinfo` SET `status`='1',`passive_date` = NULL WHERE `id`='$value'");

        //         echo " status - 1 :".$value;
        //     }
        // }

        // // Get all jobs which are created before 1 month
        // $date1 = date('Y-m-d 00:00:00',strtotime("-30 days"));
        // $jo_query = JobOpen::query();
        // $jo_query = $jo_query->where('created_at','<',"$date1");
        // $job_res = $jo_query->get();

        // $all_jobs = array();
        // $i = 0;
        // foreach ($job_res as $k=>$v) {
        //     $all_jobs[$i]['job_id'] = $v->id;
        //     $all_jobs[$i]['client_id'] = $v->client_id;
        //     $i++;
        // }

        // // In all jobs find which jobs no cvs are associated within 1 month and get their client ids
        // $j = 0;
        // foreach ($all_jobs as $k=>$v) {

        //     $j_id[$j] = $v['job_id'];
        //     $c_id[$j] = $v['client_id'];

        //     $jo_query1 = JobAssociateCandidates::query();
        //     $jo_query1 = $jo_query1->where('job_associate_candidates.job_id',$j_id[$j]);
        //     $jo_query1 = $jo_query1->where('job_associate_candidates.created_at','>=',"$date1");
        //     $job_res = $jo_query1->first();
            
        //     if(isset($job_res->job_id)) {

        //         // Get client id from job id
        //         $jo_query2 = JobOpen::query();
        //         $jo_query2 = $jo_query2->where('id',$job_res->job_id);
        //         $client_res2 = $jo_query2->first();
        //         $active_clients[]= $client_res2->client_id;

        //         $client_status_query = ClientBasicinfo::query();
        //         $client_status_query = $client_status_query->where('id',$client_res2->client_id);
        //         $client_res = $client_status_query->first();
        //         $client_status = $client_res['status'];

        //         // If client status is 2,3,4 then ignore that clients
        //         if($client_status==2 or $client_status==3 or $client_status==4)
        //             continue;

        //         DB::statement("UPDATE `client_basicinfo` SET `status`='1',`passive_date` = NULL WHERE `id`='$client_res2->client_id'");
        //     }
        //     else {

        //         $client_status_query = ClientBasicinfo::query();
        //         $client_status_query = $client_status_query->where('id',$c_id[$j]);
        //         $client_res = $client_status_query->first();
        //         $client_status = $client_res['status'];

        //         // If client status is 2,3,4 then ignore that clients
        //         if($client_status==2 or $client_status==3 or $client_status==4)
        //             continue;

        //         if(!in_array($c_id[$j],$active_clients)) {

        //             // Set passive date for passive clients
        //             if ($client_status == 0) {
        //                 continue;
        //             }
        //             else {
                        
        //                 $today_date = date('Y-m-d');
        //                 DB::statement("UPDATE `client_basicinfo` SET `status`='0',`passive_date` = '$today_date' WHERE `id`='$c_id[$j]'");
        //             }
        //         }
        //         else {

        //             DB::statement("UPDATE `client_basicinfo` SET `status`='1',`passive_date` = NULL WHERE `id`='$c_id[$j]'");
        //             echo " status - 1 :".$c_id[$j];
        //         }
        //     }
        //     $j++;
        // }
    }
}