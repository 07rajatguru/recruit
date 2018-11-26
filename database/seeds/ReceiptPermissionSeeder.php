<?php

use Illuminate\Database\Seeder;
use App\Permission;

class ReceiptPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permission = [

            [
                'name' => 'receipt-talent',
                'display_name' => 'Receipt Talent',
                'description' => 'Receipt Talent'
            ],
            [
                'name' => 'receipt-temp',
                'display_name' => 'Receipt Temp',
                'description' => 'Receipt Temp'
            ],
            [
                'name' => 'receipt-others',
                'display_name' => 'Receipt Others',
                'description' => 'Receipt Others'
            ]
        ];

        foreach ($permission as $key => $value) {

            Permission::create($value);

        }
    }
}
