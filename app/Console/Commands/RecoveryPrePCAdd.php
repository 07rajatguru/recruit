<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Bills;

class RecoveryPrePCAdd extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'recovery:pcadd';

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
        $recovery = Bills::getRecoveryBillData();

        if (isset($recovery) && sizeof($recovery)>0) {
            foreach ($recovery as $key => $value) {
                $bill_id = $value['id'];

                $percentage_charged = '8.33';
                \DB::statement("UPDATE bills SET percentage_charged = '$percentage_charged' where id=$bill_id");
            }
        }
    }
}
