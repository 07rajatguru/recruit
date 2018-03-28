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
}

