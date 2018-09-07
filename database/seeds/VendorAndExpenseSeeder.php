<?php

use Illuminate\Database\Seeder;
use App\Permission;

class VendorAndExpenseSeeder extends Seeder
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
                'name' => 'vendor-list',
                'display_name' => 'Display Vendor Listing',
                'description' => 'See Only Listing of Vendor'

            ],
            [
                'name' => 'vendor-create',
                'display_name' => 'Create Vendor',
                'description' => 'Create New Vendor'

            ],
            [
                'name' => 'vendor-edit',
                'display_name' => 'Edit Vendor',
                'description' => 'Edit Vendor'

            ],
            [
                'name' => 'vendor-delete',
                'display_name' => 'Delete Vendor',
                'description' => 'Delete Vendor'

            ],
			[
                'name' => 'expense-list',
                'display_name' => 'Display Expense Listing',
                'description' => 'See Only Listing Of Expense'

            ],
            [
                'name' => 'expense-create',
                'display_name' => 'Create Expense',
                'description' => 'Create New Expense for Vendor'

            ],
            [
                'name' => 'expense-edit',
                'display_name' => 'Edit Expense',
                'description' => 'Edit Expense'

            ],
            [
                'name' => 'expense-delete',
                'display_name' => 'Delete Expense',
                'description' => 'Delete Expense'

            ]
        ];

        foreach ($permission as $key => $value) {

            Permission::create($value);

        }
    }
}
