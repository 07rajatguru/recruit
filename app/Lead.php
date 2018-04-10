<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{

	public $table = "lead_management";



	public static $rules = array(
        'name' => 'required',
        'coordinator_name' => 'required',
        'mail' => 'required',
        'mobile' => 'required',
    );

    public function messages()
    {
        return [
            'name.required' => 'Company Name is required field',
            'coordinator_name.required' => 'Hr/Coodinator Name is required field',
            'mail.required' => 'Email is required field',
            'mobile.required' => 'Mobile Number is required field',

        ];
    }

    public static function getLeadService(){

        $typeArray = array('' => 'Select Lead Service');
        $typeArray['Recruitment'] = 'Recruitment';
        $typeArray['Temp'] = 'Temp';
        $typeArray['Payroll'] = 'Payroll';
        $typeArray['Compliance']='Compliance';	
       
        return $typeArray;
    }

    public static function getAllLeads($all=0,$user_id){

        $query = Lead::query();

        if($all==0){
            $query = $query->where('account_manager_id',$user_id);
        }

        $response = $query->get();

        $i = 0;
        foreach ($response as $key=>$value){
            $response[$i]['id'] = $value->id;
            $response[$i]['name'] = $value->name;
            $response[$i]['coordinator_name'] = $value->coordinator_name;
            $response[$i]['mail'] = $value->mail;
            $response[$i]['mobile'] = $value->mobile;
            $response[$i]['s_email'] = $value->s_email;
            $response[$i]['other_number'] = $value->other_number;
            $response[$i]['service'] = $value->service;
            $response[$i]['city'] = $value->city;
            $response[$i]['state'] = $value->state;
            $response[$i]['country'] = $value->country;
            $i++;
        }

        return $response;
    }
}

