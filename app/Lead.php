<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{

	public $table = "lead_management";



	public static $rules = array(
        'company_name' => 'required',
        'hr_name' => 'required',
        'mail' => 'required',
        'mobile' => 'required',
    );

    public function messages()
    {
        return [
            'company_name.required' => 'Company Name is required field',
            'hr_name.required' => 'Hr/Coodinator Name is required field',
            'mail.required' => 'Opemail is required field',
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

