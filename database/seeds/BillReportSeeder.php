<?php

use Illuminate\Database\Seeder;
use App\Permission;

class BillReportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //

        $permission = [

            [
                'name' => 'billselection-list',
                'display_name' => 'Display Bill Selection Listing',
                'description' => 'See Only Listing of Bill Forecasting and Recovery'

            ],
			[
                'name' => 'billrecovery-list',
                'display_name' => 'Display Bill Recovery Listing',
                'description' => 'See Only Listing Of Bill Recovery'

            ],
            [
                'name' => 'billuserwise-list',
                'display_name' => 'Display Bill Userwise Listing',
                'description' => 'See Listing Of Bill(Userwise)'

            ]
        ];

        foreach ($permission as $key => $value) {

            Permission::create($value);

        }
    }
}
