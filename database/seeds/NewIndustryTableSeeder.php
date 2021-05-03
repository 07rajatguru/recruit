<?php

use Illuminate\Database\Seeder;

class NewIndustryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement("SET FOREIGN_KEY_CHECKS=0;");
        DB::table("industry")->truncate();

        $data = array(
            array('id' => 1, 'name' => 'Advertising / Media'),
            array('id' => 2, 'name' => 'Beverage / Alcohol'),
            array('id' => 3, 'name' => 'Manufacturing'),
            array('id' => 4, 'name' => 'Ceramic'),
            array('id' => 5, 'name' => 'Automation'),
            array('id' => 6, 'name' => 'BFSI'),
            array('id' => 7, 'name' => 'BPO/KPO'),
            array('id' => 8, 'name' => 'Building Material'),
            array('id' => 9, 'name' => 'CA Firm'),
            array('id' => 10, 'name' => 'Chemicals / Petrochemicals'),
            array('id' => 11, 'name' => 'Textile / Clothing / Fashion'),
            array('id' => 12, 'name' => 'Construction'),
            array('id' => 13, 'name' => 'Digital Marketing'),
            array('id' => 14, 'name' => 'Distributor / Supplier'),
            array('id' => 15, 'name' => 'Ecommerce'),
            array('id' => 16, 'name' => 'Education / Training'),
            array('id' => 17, 'name' => 'Automobile / Electric Vehicle'),
            array('id' => 18, 'name' => 'Electronics'),
            array('id' => 19, 'name' => 'Engineering'),
            array('id' => 20, 'name' => 'EPC / Infrastructure'),
            array('id' => 21, 'name' => 'Escalators / Elevators'),
            array('id' => 22, 'name' => 'Event Management'),
            array('id' => 23, 'name' => 'Export / Import'),
            array('id' => 24, 'name' => 'FMCG - Consumer Durable'),
            array('id' => 25, 'name' => 'FMCG - Food'),
            array('id' => 26, 'name' => 'FMCG - Personal Care'),
            array('id' => 27, 'name' => 'Freight Forwarding'),
            array('id' => 28, 'name' => 'HVAC - Heat Ventilation Air Conditioning'),
            array('id' => 29, 'name' => 'Hospitality'),
            array('id' => 30, 'name' => 'HR Consulting'),
            array('id' => 31, 'name' => 'IOT - Internet of Things'),
            array('id' => 32, 'name' => 'IT - Hardware'),
            array('id' => 33, 'name' => 'IT - Software'),
            array('id' => 34, 'name' => 'Hospital / Laboratory'),
            array('id' => 35, 'name' => 'Logistics'),
            array('id' => 36, 'name' => 'Metal'),
            array('id' => 37, 'name' => 'Oil & Gas'),
            array('id' => 38, 'name' => 'Packaging / Printing'),
            array('id' => 39, 'name' => 'Pharmaceuticals'),
            array('id' => 40, 'name' => 'Real Estate'),
            array('id' => 41, 'name' => 'Retail'),
            array('id' => 42, 'name' => 'Service'),
            array('id' => 43, 'name' => 'Shipping / Marine'),
            array('id' => 44, 'name' => 'Solar'),
            array('id' => 45, 'name' => 'Steel'),
            array('id' => 46, 'name' => 'Telecom'),
            array('id' => 47, 'name' => 'Travel'),
            array('id' => 48, 'name' => 'Water / Wastewater'),
            array('id' => 49, 'name' => 'Other'),
        );

        DB::table("industry")->insert($data);
    }
}