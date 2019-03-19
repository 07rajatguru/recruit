<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Bills;
use App\ClientBasicinfo;

class BillsPCAdd extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bills:pcadd';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Script for add previous Bills Percentage Charged';

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
        $forecasting = Bills::getAllBills(0,1);
        $recovery = Bills::getAllBills(1,1);

        $bills = array_merge($forecasting,$recovery);

        if (isset($bills) && sizeof($bills)>0) {
            foreach ($bills as $key => $value) {
                $bill_id = $value['id'];
                $client_id = $value['client_id'];

                $client_data = ClientBasicinfo::getClientDetailsById($client_id);

                $percentage_charged = $client_data['percentage_charged'];
                if (isset($percentage_charged) && $percentage_charged != '') {
                    \DB::statement("UPDATE bills SET percentage_charged = '$percentage_charged' where id=$bill_id");
                }
                else {
                    \DB::statement("UPDATE bills SET percentage_charged = '0' where id=$bill_id");
                }
            }
        }
    }
}
