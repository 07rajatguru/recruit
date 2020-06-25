<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\ClientBasicinfo;

class AddClientRemarks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'client:remarks';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $client_array = ClientBasicinfo::getClientArray();
        //print_r($client_array);exit;

        if(isset($client_array) && sizeof($client_array) >0) {

            foreach ($client_array as $key => $value) {

                // Update in Client Basicinfo Table
                $latest_remarks = ClientBasicinfo::getLatestRemarks($key);

                if(isset($latest_remarks) && $latest_remarks != '') {

                    $client_info = ClientBasicinfo::find($key);
                    $client_info->latest_remarks = $latest_remarks;
                    $client_info->save();
                }
            }
        }
    }
}
