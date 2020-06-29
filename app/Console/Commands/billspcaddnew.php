<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Bills;
use App\ClientBasicinfo;

class billspcaddnew extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bills:pcaddnew';

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
        echo "Here";exit;
        
        $forecasting = Bills::getAllBills(0,1);
        $recovery = Bills::getAllBills(1,1);
        $today_date = date('Y-m-d');

        $bills = array_merge($forecasting,$recovery);

        if (isset($bills) && sizeof($bills)>0) {

            foreach ($bills as $key => $value) {

                $bill_id = $value['id'];
                $client_id = $value['client_id'];
                $bill_percentage_charged = $value['percentage_charged'];

                $client_data = ClientBasicinfo::getClientDetailsById($client_id);
                $percentage_charged = $client_data['percentage_charged'];

                if($bill_percentage_charged == '0.00' || $bill_percentage_charged == '') {

                    if (isset($percentage_charged) && $percentage_charged != '') {

                        \DB::statement("UPDATE bills SET percentage_charged = '$percentage_charged' where id=$bill_id");
                        \DB::statement("UPDATE bills SET per_chared_date = '$today_date' where id=$bill_id");
                    }
                    else {
                        \DB::statement("UPDATE bills SET percentage_charged = '8.33' where id=$bill_id");
                        
                        \DB::statement("UPDATE bills SET per_chared_date = '$today_date' where id=$bill_id");
                    }
                }
            }
        }
    }
}
