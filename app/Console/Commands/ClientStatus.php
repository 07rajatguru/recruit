<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\ClientBasicinfo;
use App\JobOpen;
use App\Date;
use DB;


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
        //

        $job_open=\DB::table('client_basicinfo')
        ->leftjoin('job_openings','client_basicinfo.id','=','job_openings.client_id')
        ->leftjoin('job_associate_candidates','job_associate_candidates.job_id','=','job_openings.id')
        ->select('job_openings.*','client_basicinfo.id as Client_Id','job_openings.created_at as created')
        ->get();

        /*$client_query = ClientBasicinfo::query();
        $client_query = $client_query->leftJoin('job_openings','job_openings.client_id','=','client_basicinfo.id');
        $client_query = $client_query->leftJoin('job_associate_candidates','job_associate_candidates.job_id','=','job_openings.id');
        $client_query = $client_query->select('job_openings.*','client_basicinfo.id as Client_Id','job_associate_candidates.created_at as created');
        $job_open = $client_query->get();*/

        $job=array();

        if(isset($job_open))
        {
            foreach($job_open as $key=>$value)
            {
                $client_id=$value->Client_Id;

                $created_at = $value->created_at; // job added date
                $created = $value->created; // job candidate associate date

                $date1=date('Y-m-d',strtotime("-30 days"));

                if(isset($created_at) && isset($created)  )
                {
                   
                   if($created < $date1)
                   {
                        DB::statement("UPDATE  client_basicinfo SET `status`='0' WHERE `id`='$client_id'");

                        //echo "Inacitve";
                   }
                   else
                   {
                        DB::statement("UPDATE  client_basicinfo SET `status`='1' WHERE `id`='$client_id'");

                       // echo "Active";
                   }
                }
                else{

                    DB::statement("UPDATE  client_basicinfo SET `status`='0' WHERE `id`='$client_id'");

                   // echo "Inacitve";
                }
            }
        }

    }
}
