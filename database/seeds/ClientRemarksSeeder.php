<?php

use Illuminate\Database\Seeder;
use App\ClientRemarks;

class ClientRemarksSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $remarks = [

            [
                'remarks' => 'Shifted to HRBP role',
            ],
            [
                'remarks' => 'Ongoing positions',
            ],
            [
                'remarks' => 'Ongoing positions and interviews',
            ],
            [
                'remarks' => 'Critical in terms of deliveries',
            ],
            [
                'remarks' => 'No positions at the moment',
            ],
            [
                'remarks' => 'Unable to connect',
            ],
            [
                'remarks' => 'Unable to connect over phone and email sent',
            ],
            [
                'remarks' => 'Did not follow up in last 2 weeks',
            ],
            [
                'remarks' => 'Willing to transfer',
            ],
            [
                'remarks' => 'Lack of follow up and to be transferred after one week of review',
            ],
            [
                'remarks' => 'No response after follow up',
            ],
            [
                'remarks' => 'Know your client and update in a week',
            ],
            [
                'remarks' => 'Do not handle positions directly',
            ],
            [
            	'remarks' => 'Hold on interaction until further communication',
            ]
        ];

        foreach ($remarks as $key => $value) {
            ClientRemarks::create($value);
        }
    }
}
