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
        $typeArray['IT'] = 'IT';
        $typeArray['HR Advisory'] = 'HR Advisory';	
       
        return $typeArray;
    }

    public static function getLeadStatus(){

        $statusArray = array('' => 'Select Lead Status');
        $statusArray['Active'] = 'Active';
        $statusArray['In Progress'] = 'In Progress';
        $statusArray['In Active'] = 'In Active';
        $statusArray['Cancel']='Cancel';
       
        return $statusArray;
    }

    public static function getAllLeads($all=0,$user_id){

        $cancel_lead = 0;
        $query = Lead::query();
        $query = $query->leftjoin('users','users.id','=','lead_management.referredby');
        $query = $query->select('lead_management.*', 'users.name as referredby');
        $query = $query->where('cancel_lead',$cancel_lead);
        $query = $query->orderBy('lead_management.id','desc');

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
            $response[$i]['website'] = $value->website;
            $response[$i]['source'] = $value->source;
            $response[$i]['Designation'] = $value->designation;
            $response[$i]['referredby'] = $value->referredby;
            $response[$i]['convert_client'] = $value->convert_client;
            $response[$i]['lead_status'] = $value->lead_status;
            $i++;
        }

        return $response;
    }

    public static function getCancelLeads($all=0,$user_id){

        $cancel_lead = 1;
        $query = Lead::query();
        $query = $query->leftjoin('users','users.id','=','lead_management.referredby');
        $query = $query->select('lead_management.*', 'users.name as referredby');
        $query = $query->where('cancel_lead',$cancel_lead);
        $query = $query->orderBy('lead_management.id','desc');

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
            $response[$i]['website'] = $value->website;
            $response[$i]['source'] = $value->source;
            $response[$i]['Designation'] = $value->designation;
            $response[$i]['referredby'] = $value->referredby;
            $response[$i]['convert_client'] = $value->convert_client;
            $response[$i]['lead_status'] = $value->lead_status;
            $i++;
        }

        return $response;
    }

    public static function getConvertedClient($all=0,$user_id){

        $convert_client = '1';
        $query = Lead::query();
        $query = $query->select('lead_management.*');
        $query = $query->where('convert_client',$convert_client);

        if($all==0){
            $query = $query->where('account_manager_id',$user_id);
        }

        $response = $query->get();

        //print_r($response);exit;
        return $response;
    }

    public static function getDailyReportLeadCount($user_id){

        $from_date = date("Y-m-d 00:00:00");
        $to_date = date("Y-m-d 23:59:59");

        $query = Lead::query();
        $query = $query->select('lead_management.*','lead_management.created_at');
        $query = $query->where('created_at','>=',"$from_date");
        $query = $query->where('created_at','<=',"$to_date");
        $query = $query->where('lead_management.account_manager_id','=',$user_id);

        $lead_cnt = $query->count();

        return $lead_cnt;
    }

    public static function getDailyReportLeadCountIndex($users_id,$date){

        $query = Lead::query();
        $query = $query->select('lead_management.*','lead_management.created_at');
        $query = $query->where('account_manager_id',$users_id);
        $query = $query->where('lead_management.account_manager_id','=',$users_id);
        $query = $query->where(\DB::raw('date(created_at)'),$date);

        $lead_cnt = $query->count();

        return $lead_cnt;
    }

    public static function getWeeklyReportLeadCount($user_id){

        $date = date('Y-m-d',strtotime('Monday this week'));

        $query = Lead::query();
        $query = $query->select('lead_management.*','lead_management.created_at');
        $query = $query->where('created_at','>=',date('Y-m-d',strtotime('Monday this week')));
        $query = $query->where('created_at','<=',date('Y-m-d',strtotime("$date +6days")));
        $query = $query->where('lead_management.account_manager_id','=',$user_id);

        $lead_cnt = $query->count();

        return $lead_cnt;   
    }

    public static function getWeeklyReportLeadCountIndex($users_id,$from_date,$to_date){

        $query = Lead::query();
        $query = $query->select('lead_management.*','lead_management.created_at');
        $query = $query->where(\DB::raw('date(created_at)'),'>=',$from_date);
        $query = $query->where(\DB::raw('date(created_at)'),'<=',$to_date);
        $query = $query->where('lead_management.account_manager_id','=',$users_id);

        $lead_cnt = $query->count();

        return $lead_cnt;   
    }
}

