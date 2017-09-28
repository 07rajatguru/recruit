<?php

use Illuminate\Database\Seeder;

class IndustryTableSeeder extends Seeder
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
            array('id' => 1, 'name' => 'Any'),
            array('id' => 2, 'name' => 'Accounting / Finance'),
            array('id' => 3, 'name' => 'Advertising / PR / MR / Events'),
            array('id' => 4, 'name' => 'Agriculture / Dairy'),
            array('id' => 5, 'name' => 'Animation'),
            array('id' => 6, 'name' => 'Architecture / Interior Design'),
            array('id' => 7, 'name' => 'Auto / Auto Ancillary'),
            array('id' => 8, 'name' => 'Aviation / Aerospace Firm'),
            array('id' => 9, 'name' => 'Banking / Financial Services / Broking'),
            array('id' => 10, 'name' => 'BPO / ITES'),
            array('id' => 11, 'name' => 'Brewery / Distillery'),
            array('id' => 12, 'name' => 'Chemicals / PetroChemical / Plastic / Rubber'),
            array('id' => 13, 'name' => 'Construction / Engineering / Cement / Metals'),
            array('id' => 14, 'name' => 'Consumer Durables'),
            array('id' => 15, 'name' => 'Courier / Transportation / Freight'),
            array('id' => 16, 'name' => 'Ceramics /Sanitary ware'),
            array('id' => 17, 'name' => 'Defence / Government'),
            array('id' => 18, 'name' => 'Education / Teaching / Training'),
            array('id' => 19, 'name' => 'Electricals / Switchgears'),
            array('id' => 20, 'name' => 'Export / Import'),
            array('id' => 21, 'name' => 'Facility Management'),
            array('id' => 22, 'name' => 'Fertilizers / Pesticides'),
            array('id' => 23, 'name' => 'FMCG / Foods / Beverage'),
            array('id' => 24, 'name' => 'Food Processing'),
            array('id' => 25, 'name' => 'Fresher / Trainee'),
            array('id' => 26, 'name' => 'Gems & Jewellery'),
            array('id' => 27, 'name' => 'Glass'),
            array('id' => 28, 'name' => 'Heat Ventilation Air Conditioning'),
            array('id' => 29, 'name' => 'Hotels / Restaurants / Airlines / Travel'),
            array('id' => 30, 'name' => 'Industrial Products / Heavy Machinery'),
            array('id' => 31, 'name' => 'Insurance'),
            array('id' => 32, 'name' => 'IT-Software / Software Services'),
            array('id' => 33, 'name' => 'IT-Hardware & Networking'),
            array('id' => 34, 'name' => 'Telecom / ISP'),
            array('id' => 35, 'name' => 'KPO / Research /Analytics'),
            array('id' => 36, 'name' => 'Legal'),
            array('id' => 37, 'name' => 'Media / Dotcom / Entertainment'),
            array('id' => 38, 'name' => 'Internet / Ecommerce'),
            array('id' => 39, 'name' => 'Medical / Healthcare / Hospital'),
            array('id' => 40, 'name' => 'Mining'),
            array('id' => 41, 'name' => 'NGO / Social Services'),
            array('id' => 42, 'name' => 'Office Equipment / Automation'),
            array('id' => 43, 'name' => 'Oil and Gas / Power / Infrastructure / Energy'),
            array('id' => 44, 'name' => 'Paper'),
            array('id' => 45, 'name' => 'Pharma / Biotech / Clinical Research'),
            array('id' => 46, 'name' => 'Printing / Packaging'),
            array('id' => 47, 'name' => 'Publishing'),
            array('id' => 48, 'name' => 'Real Estate / Property'),
            array('id' => 49, 'name' => 'Recruitment'),
            array('id' => 50, 'name' => 'Retail'),
            array('id' => 51, 'name' => 'Security / Law Enforcement'),
            array('id' => 52, 'name' => 'Semiconductors / Electronics'),
            array('id' => 53, 'name' => 'Shipping / Marine'),
            array('id' => 54, 'name' => 'Steel'),
            array('id' => 55, 'name' => 'Strategy / Management Consulting Firms'),
            array('id' => 56, 'name' => 'Textiles / Garments / Accessories'),
            array('id' => 57, 'name' => 'Tyres'),
            array('id' => 58, 'name' => 'Water Treatment / Waste Management'),
            array('id' => 59, 'name' => 'Wellness/Fitness/Sports'),
            array('id' => 60, 'name' => 'Other'),
        );

        DB::table("industry")->insert($data);
    }
}
