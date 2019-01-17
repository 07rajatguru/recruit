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

        $job_data = \DB::select(\DB::raw('SELECT id,job_id,client_id,created_at FROM job_openings WHERE created_at IN (SELECT MAX(created_at) FROM job_openings GROUP BY client_id)'));

        //print_r($job_created_at);exit;

        $client = ClientBasicinfo::query();
        $client = $client->select('client_basicinfo.id');
        $client_res = $client->get();

        $j = 0;
        foreach ($client_res as $key => $value) {
            $clientids[$j] = $value->id;
            $j++;
        }

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

                $job_created_at = $value1->created_at; // job added date
                // $job_associated_created_at = $job_associated_data->created_at; // job candidate associate date

                $date1=date('Y-m-d',strtotime("-30 days"));
                if(isset($job_created_at)){
                   if(($job_created_at /*|| $job_associated_created_at*/) < $date1){
                        DB::statement("UPDATE client_basicinfo SET `status`='0' WHERE `id`='$client_id'");
                        echo " status - 0 :".$client_id;
                   }
                   else{
                        DB::statement("UPDATE client_basicinfo SET `status`='1' WHERE `id`='$client_id'");
                        echo " status - 1 :".$client_id;
                   }
                }
                else{
                    DB::statement("UPDATE client_basicinfo SET `status`='0' WHERE `id`='$client_id'");
                    echo " status - 0 :".$client_id;
                }
            //} 
                if (array_search($value1->client_id,$clientids)) {
                    unset($clientids[array_search($value1->client_id,$clientids)]);
                }
                $i++;
            }
        }
        else{
            DB::statement("UPDATE client_basicinfo SET `status`='0' WHERE `id`='$client_id'");
            echo " status - 0 :".$client_id;
        }
        /*$i++;
        }*/
        foreach ($clientids as $key => $value) {
            DB::statement("UPDATE client_basicinfo SET `status`='0' WHERE `id`='$value'");
            echo " status - 0 :".$value;
        }
    }
}
