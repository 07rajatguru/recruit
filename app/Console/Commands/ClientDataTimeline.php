<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\ClientBasicinfo;
use App\ClientTimeline;

class ClientDataTimeline extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'client:timeline';

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
        $client_res = ClientBasicinfo::getAllClientDetails();

        foreach ($client_res as $key => $value){

            $client_timeline = new ClientTimeline();
            $client_timeline->user_id = $value->account_manager_id;
            $client_timeline->client_id = $value->id;
            $client_timeline->created_at = $value->created_at;
            $client_timeline->updated_at = $value->created_at;
            $client_timeline->save();
        }
    }
}
