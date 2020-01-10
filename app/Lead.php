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
            'coordinator_name.required' => 'Contact Point is required field',
            'mail.required' => 'Email is required field',
            'mobile.required' => 'Mobile Number is required field',

        ];
    }

    public static function getLeadService(){

        $typeArray = array('' => 'Select Lead Service');
        $typeArray['Recruitment'] = 'Recruitment';
        //$typeArray['Temp'] = 'Temp';
        $typeArray['Contract Staffing'] = 'Contract Staffing';
        $typeArray['Payroll'] = 'Payroll';
        //$typeArray['Compliance']='Compliance';
        //$typeArray['IT'] = 'IT';
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

    public static function getAllLeads($all=0,$user_id,$user_role_id,$limit=0,$offset=0,$search=NULL,$order=NULL,$type='desc'){

        $superadmin_role_id = env('SUPERADMIN');
        $strategy_role_id = env('STRATEGY');

        $cancel_lead = 0;
        $query = Lead::query();
        $query = $query->leftjoin('users','users.id','=','lead_management.referredby');
        $query = $query->select('lead_management.*', 'users.name as referredby');
        $query = $query->where('cancel_lead',$cancel_lead);
        if (isset($order) && $order != '') {
            $query = $query->orderBy($order,$type);
        }
        if (isset($limit) && $limit > 0) {
            $query = $query->limit($limit);
        }

        if (isset($offset) && $offset > 0) {
            $query = $query->offset($offset);
        }

        if($all==0){
            $query = $query->where('account_manager_id',$user_id);
            if (isset($search) && $search != '') {
                $query = $query->where(function($query) use ($search){
                    $query = $query->where('lead_management.name','like',"%$search%");
                    $query = $query->orwhere('lead_management.coordinator_name','like',"%$search%");
                    $query = $query->orwhere('lead_management.mail','like',"%$search%");
                    $query = $query->orwhere('lead_management.mobile','like',"%$search%");
                    $query = $query->orwhere('lead_management.city','like',"%$search%");
                    $query = $query->orwhere('users.name','like',"%$search%");
                    $query = $query->orwhere('lead_management.website','like',"%$search%");
                    $query = $query->orwhere('lead_management.source','like',"%$search%");
                    $query = $query->orwhere('lead_management.designation','like',"%$search%");
                });
            }
        }
        else{
            if (isset($search) && $search != '') {
                $query = $query->where(function($query) use ($search){
                    $query = $query->where('lead_management.name','like',"%$search%");
                    $query = $query->orwhere('lead_management.coordinator_name','like',"%$search%");
                    $query = $query->orwhere('lead_management.mail','like',"%$search%");
                    $query = $query->orwhere('lead_management.mobile','like',"%$search%");
                    $query = $query->orwhere('lead_management.city','like',"%$search%");
                    $query = $query->orwhere('users.name','like',"%$search%");
                    $query = $query->orwhere('lead_management.website','like',"%$search%");
                    $query = $query->orwhere('lead_management.source','like',"%$search%");
                    $query = $query->orwhere('lead_management.designation','like',"%$search%");
                });
            }
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

            $response[$i]['access'] = false;
            if($user_id==$value->account_manager_id){
                $response[$i]['access'] = true;
            }
            /*else if ($superadmin_role_id || $strategy_role_id) {
                $response[$i]['access'] = true;
            }*/
            else if($user_role_id == $superadmin_role_id || $user_role_id == $strategy_role_id)
            {           
                $response[$i]['access'] = true;
            }
            $i++;
        }

        return $response;
    }

    public static function getAllLeadsCount($all=0,$user_id,$search=NULL){

        $superadmin_role_id = env('SUPERADMIN');
        $strategy_role_id = env('STRATEGY');

        $cancel_lead = 0;
        $query = Lead::query();
        $query = $query->leftjoin('users','users.id','=','lead_management.referredby');
        $query = $query->select('lead_management.*', 'users.name as referredby');
        $query = $query->where('cancel_lead',$cancel_lead);

        if($all==0){
            $query = $query->where('account_manager_id',$user_id);
            if (isset($search) && $search != '') {
                $query = $query->where(function($query) use ($search){
                    $query = $query->where('lead_management.name','like',"%$search%");
                    $query = $query->orwhere('lead_management.coordinator_name','like',"%$search%");
                    $query = $query->orwhere('lead_management.mail','like',"%$search%");
                    $query = $query->orwhere('lead_management.mobile','like',"%$search%");
                    $query = $query->orwhere('lead_management.city','like',"%$search%");
                    $query = $query->orwhere('users.name','like',"%$search%");
                    $query = $query->orwhere('lead_management.website','like',"%$search%");
                    $query = $query->orwhere('lead_management.source','like',"%$search%");
                    $query = $query->orwhere('lead_management.designation','like',"%$search%");
                });
            }
        }
        else{
            if (isset($search) && $search != '') {
                $query = $query->where(function($query) use ($search){
                    $query = $query->where('lead_management.name','like',"%$search%");
                    $query = $query->orwhere('lead_management.coordinator_name','like',"%$search%");
                    $query = $query->orwhere('lead_management.mail','like',"%$search%");
                    $query = $query->orwhere('lead_management.mobile','like',"%$search%");
                    $query = $query->orwhere('lead_management.city','like',"%$search%");
                    $query = $query->orwhere('users.name','like',"%$search%");
                    $query = $query->orwhere('lead_management.website','like',"%$search%");
                    $query = $query->orwhere('lead_management.source','like',"%$search%");
                    $query = $query->orwhere('lead_management.designation','like',"%$search%");
                });
            }
        }

        $response = $query->count();

        return $response;
    }

    public static function getCancelLeads($all=0,$user_id,$user_role_id,$limit=0,$offset=0,$search=NULL,$order=NULL,$type='desc'){

        $superadmin_role_id = env('SUPERADMIN');
        $strategy_role_id = env('STRATEGY');
        
        $cancel_lead = 1;
        $query = Lead::query();
        $query = $query->leftjoin('users','users.id','=','lead_management.referredby');
        $query = $query->select('lead_management.*', 'users.name as referredby');
        $query = $query->where('cancel_lead',$cancel_lead);

        if($all==0){
            $query = $query->where('account_manager_id',$user_id);
            if (isset($search) && $search != '') {
                $query = $query->where(function($query) use ($search){
                    $query = $query->where('lead_management.name','like',"%$search%");
                    $query = $query->orwhere('lead_management.coordinator_name','like',"%$search%");
                    $query = $query->orwhere('lead_management.mail','like',"%$search%");
                    $query = $query->orwhere('lead_management.mobile','like',"%$search%");
                    $query = $query->orwhere('lead_management.city','like',"%$search%");
                    $query = $query->orwhere('users.name','like',"%$search%");
                    $query = $query->orwhere('lead_management.website','like',"%$search%");
                    $query = $query->orwhere('lead_management.source','like',"%$search%");
                    $query = $query->orwhere('lead_management.designation','like',"%$search%");
                });
            }
        }
        else{
            if (isset($search) && $search != '') {
                $query = $query->where(function($query) use ($search){
                    $query = $query->where('lead_management.name','like',"%$search%");
                    $query = $query->orwhere('lead_management.coordinator_name','like',"%$search%");
                    $query = $query->orwhere('lead_management.mail','like',"%$search%");
                    $query = $query->orwhere('lead_management.mobile','like',"%$search%");
                    $query = $query->orwhere('lead_management.city','like',"%$search%");
                    $query = $query->orwhere('users.name','like',"%$search%");
                    $query = $query->orwhere('lead_management.website','like',"%$search%");
                    $query = $query->orwhere('lead_management.source','like',"%$search%");
                    $query = $query->orwhere('lead_management.designation','like',"%$search%");
                });
            }
        }

        if (isset($order) && $order != '') {
            $query = $query->orderBy($order,$type);
        }
        if (isset($limit) && $limit > 0) {
            $query = $query->limit($limit);
        }
        if (isset($offset) && $offset > 0) {
            $query = $query->offset($offset);
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
            $response[$i]['access'] = false;
            if($user_id==$value->account_manager_id){
                $response[$i]['access'] = true;
            }
            /*else if ($superadmin_role_id || $strategy_role_id) {
                $response[$i]['access'] = true;
            }*/
            else if($user_role_id == $superadmin_role_id || $user_role_id == $strategy_role_id)
            {           
                $response[$i]['access'] = true;
            }
            $i++;
        }

        return $response;
    }

    public static function getCancelLeadsCount($all=0,$user_id,$search=NULL){

        $superadmin_role_id = env('SUPERADMIN');
        $strategy_role_id = env('STRATEGY');

        $cancel_lead = 1;
        $query = Lead::query();
        $query = $query->leftjoin('users','users.id','=','lead_management.referredby');
        $query = $query->select('lead_management.*', 'users.name as referredby');
        $query = $query->where('cancel_lead',$cancel_lead);

        if($all==0){
            $query = $query->where('account_manager_id',$user_id);
            if (isset($search) && $search != '') {
                $query = $query->where(function($query) use ($search){
                    $query = $query->where('lead_management.name','like',"%$search%");
                    $query = $query->orwhere('lead_management.coordinator_name','like',"%$search%");
                    $query = $query->orwhere('lead_management.mail','like',"%$search%");
                    $query = $query->orwhere('lead_management.mobile','like',"%$search%");
                    $query = $query->orwhere('lead_management.city','like',"%$search%");
                    $query = $query->orwhere('users.name','like',"%$search%");
                    $query = $query->orwhere('lead_management.website','like',"%$search%");
                    $query = $query->orwhere('lead_management.source','like',"%$search%");
                    $query = $query->orwhere('lead_management.designation','like',"%$search%");
                });
            }
        }
        else{
            if (isset($search) && $search != '') {
                $query = $query->where(function($query) use ($search){
                    $query = $query->where('lead_management.name','like',"%$search%");
                    $query = $query->orwhere('lead_management.coordinator_name','like',"%$search%");
                    $query = $query->orwhere('lead_management.mail','like',"%$search%");
                    $query = $query->orwhere('lead_management.mobile','like',"%$search%");
                    $query = $query->orwhere('lead_management.city','like',"%$search%");
                    $query = $query->orwhere('users.name','like',"%$search%");
                    $query = $query->orwhere('lead_management.website','like',"%$search%");
                    $query = $query->orwhere('lead_management.source','like',"%$search%");
                    $query = $query->orwhere('lead_management.designation','like',"%$search%");
                });
            }
        }
        $response = $query->count();

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

    public static function getDailyReportLeads($user_id,$date=NULL){

        $from_date = date("Y-m-d 00:00:00");
        $to_date = date("Y-m-d 23:59:59");

        $query = Lead::query();
        $query = $query->select('lead_management.*');
        $query = $query->where('lead_management.account_manager_id','=',$user_id);
        $query = $query->where('lead_management.cancel_lead','=','0');
        $query = $query->where('lead_management.convert_client','=','0');

        if ($date == NULL) {
            $query = $query->where('created_at','>=',"$from_date");
            $query = $query->where('created_at','<=',"$to_date");
        }

        if ($date != '') {
            $query = $query->where(\DB::raw('date(created_at)'),$date);
        }

        $query = $query->groupBy('lead_management.id');
        $query_response = $query->get();

        $response['leads_data'] = array();
        $i = 0;

        foreach ($query_response as $key1 => $value1) {

            $response['leads_data'][$i]['company_name'] = $value1->name;
            $response['leads_data'][$i]['contact_point'] = $value1->coordinator_name;
            $response['leads_data'][$i]['designation'] = $value1->designation;
            $response['leads_data'][$i]['email'] = $value1->mail;
            $response['leads_data'][$i]['mobile'] = $value1->mobile;

            $location ='';
            if($value1->city!=''){
                $location .= $value1->city;
            }
            if($value1->state!=''){
                if($location=='')
                    $location .= $value1->state;
                else
                    $location .= ", ".$value1->state;
            }
            if($value1->country!=''){
                if($location=='')
                    $location .= $value1->country;
                else
                    $location .= ", ".$value1->country;
            }

            $response['leads_data'][$i]['city'] = $value1->city;
            $response['leads_data'][$i]['location'] = $location;
            $response['leads_data'][$i]['website'] = $value1->website;
            $response['leads_data'][$i]['service'] = $value1->service;
            $response['leads_data'][$i]['lead_status'] = $value1->lead_status;
            $response['leads_data'][$i]['source'] = $value1->source;
            $i++;
        }
        
        return $response;   
    }
    public static function getDailyReportLeadCount($user_id,$date=NULL){

        $from_date = date("Y-m-d 00:00:00");
        $to_date = date("Y-m-d 23:59:59");

        $query = Lead::query();
        $query = $query->select('lead_management.*');
        $query = $query->where('lead_management.account_manager_id','=',$user_id);
        $query = $query->where('lead_management.cancel_lead','=','0');
        $query = $query->where('lead_management.convert_client','=','0');

        if ($date == NULL) {
            $query = $query->where('created_at','>=',"$from_date");
            $query = $query->where('created_at','<=',"$to_date");
        }

        if ($date != '') {
            $query = $query->where(\DB::raw('date(created_at)'),$date);
        }

        $query = $query->groupBy('lead_management.id');
        $lead_cnt = $query->count();

        return $lead_cnt;
    }

    public static function getWeeklyReportLeads($user_id,$from_date=NULL,$to_date=NULL){

        $date = date('Y-m-d',strtotime('Monday this week'));

        $query = Lead::query();
        $query = $query->select(\DB::raw("COUNT(lead_management.id) as count"),'lead_management.*');
        $query = $query->where('lead_management.account_manager_id','=',$user_id);
        $query = $query->where('lead_management.cancel_lead','=','0');
        $query = $query->where('lead_management.convert_client','=','0');

        if ($from_date == NULL && $to_date == NULL) {
            $query = $query->where('created_at','>=',date('Y-m-d',strtotime('Monday this week')));
            $query = $query->where('created_at','<=',date('Y-m-d',strtotime("$date +6days")));
        }

        if ($from_date != '' && $to_date != '') {
            $query = $query->where(\DB::raw('date(created_at)'),'>=',$from_date);
            $query = $query->where(\DB::raw('date(created_at)'),'<=',$to_date);
        }

        $query = $query->groupBy(\DB::raw('Date(lead_management.created_at)'));
        $query_response = $query->get();

        $response['leads_data'] = array();
        $i = 0;
      
        foreach ($query_response as $key => $value) {
       
            // Get Weekwise lead count
            $datearry = explode(' ', $value->created_at);
            $response['leads_data'][$i]['lead_date'] = $datearry[0];
            $response['leads_data'][$i]['lead_count'] = $value->count;

            // Get all data

            $response['leads_data'][$i]['company_name'] = $value->name;
            $response['leads_data'][$i]['contact_point'] = $value->coordinator_name;
            $response['leads_data'][$i]['designation'] = $value->designation;
            $response['leads_data'][$i]['email'] = $value->mail;
            $response['leads_data'][$i]['mobile'] = $value->mobile;

            $location ='';
            if($value->city!=''){
                $location .= $value->city;
            }
            if($value->state!=''){
                if($location=='')
                    $location .= $value->state;
                else
                    $location .= ", ".$value->state;
            }
            if($value->country!=''){
                if($location=='')
                    $location .= $value->country;
                else
                    $location .= ", ".$value->country;
            }

            $response['leads_data'][$i]['city'] = $value->city;
            $response['leads_data'][$i]['location'] = $location;
            $response['leads_data'][$i]['website'] = $value->website;
            $response['leads_data'][$i]['service'] = $value->service;
            $response['leads_data'][$i]['lead_status'] = $value->lead_status;
            $response['leads_data'][$i]['source'] = $value->source;
            $i++;
        }
        return $response;  
    }

    public static function getWeeklyReportLeadCount($user_id,$from_date=NULL,$to_date=NULL){

        $date = date('Y-m-d',strtotime('Monday this week'));

        $query = Lead::query();
        $query = $query->select('lead_management.*');
        $query = $query->where('lead_management.account_manager_id','=',$user_id);
        $query = $query->where('lead_management.cancel_lead','=','0');
        $query = $query->where('lead_management.convert_client','=','0');
        

        if ($from_date == NULL && $to_date == NULL) {
            $query = $query->where('created_at','>=',date('Y-m-d',strtotime('Monday this week')));
            $query = $query->where('created_at','<=',date('Y-m-d',strtotime("$date +6days")));
        }

        if ($from_date != '' && $to_date != '') {
            $query = $query->where(\DB::raw('date(created_at)'),'>=',$from_date);
            $query = $query->where(\DB::raw('date(created_at)'),'<=',$to_date);
        }

        $lead_cnt = $query->count();

        return $lead_cnt;   
    }

    public static function getUserWiseMonthlyReportLeadCount($users,$month,$year){

        $u_keys = array_keys($users);

        $query = Lead::query();
        $query = $query->select(\DB::raw("COUNT(lead_management.id) as count"),'lead_management.account_manager_id');
        $query = $query->whereIn('lead_management.account_manager_id',$u_keys);

        if ($month != '' && $year != '') {
            $query = $query->where(\DB::raw('month(created_at)'),'=',$month);
            $query = $query->where(\DB::raw('year(created_at)'),'=',$year);
            $query = $query->where('lead_management.cancel_lead','=','0');
            $query = $query->where('lead_management.convert_client','=','0');
        }

        $query = $query->groupBy('lead_management.account_manager_id');

        $lead_res = $query->get();

        $lead_count = array();

        if($lead_res->count()>0){

            foreach ($lead_res  as $k=>$v){
                $lead_count[$v->account_manager_id] = $v->count;
            }
        }
        return $lead_count;
    }

    public static function getUserWiseMonthlyReportLeads($users,$month,$year){

        $u_keys = array_keys($users);

        $query = Lead::query();
        $query = $query->select('lead_management.*');
        $query = $query->whereIn('lead_management.account_manager_id',$u_keys);
        $query = $query->where('lead_management.cancel_lead','=','0');
        $query = $query->where('lead_management.convert_client','=','0');

        if ($month != '' && $year != '') {
            $query = $query->where(\DB::raw('month(created_at)'),'=',$month);
            $query = $query->where(\DB::raw('year(created_at)'),'=',$year);
        }

        //$query = $query->groupBy('lead_management.account_manager_id');

        $response = $query->get();

        $lead_res = array();
        $i=0;

        if($response->count()>0)
        {
            foreach ($response as $key=>$value)
            {
                $lead_res[$value->account_manager_id][$i]['company_name'] = $value->name;
                $lead_res[$value->account_manager_id][$i]['contact_point'] = $value->coordinator_name;
                $lead_res[$value->account_manager_id][$i]['designation'] = $value->designation;
                $lead_res[$value->account_manager_id][$i]['email'] = $value->mail;
                $lead_res[$value->account_manager_id][$i]['mobile'] = $value->mobile;

                $location ='';
                if($value->city!=''){
                    $location .= $value->city;
                }
                if($value->state!=''){
                    if($location=='')
                        $location .= $value->state;
                    else
                        $location .= ", ".$value->state;
                }
                if($value->country!=''){
                    if($location=='')
                        $location .= $value->country;
                    else
                        $location .= ", ".$value->country;
                }

                $lead_res[$value->account_manager_id][$i]['city'] = $value->city;
                $lead_res[$value->account_manager_id][$i]['location'] = $location;
                $lead_res[$value->account_manager_id][$i]['website'] = $value->website;
                $lead_res[$value->account_manager_id][$i]['service'] = $value->service;
                $lead_res[$value->account_manager_id][$i]['lead_status'] = $value->lead_status;
                $lead_res[$value->account_manager_id][$i]['source'] = $value->source;
                $i++;
            }
        }
        return $lead_res;
    }

    public static function getMonthlyReportLeadCount($user_id,$month=NULL,$year=NULL){

        $query = Lead::query();
        $query = $query->select('lead_management.*','lead_management.created_at');
        $query = $query->where('lead_management.account_manager_id','=',$user_id);

        if ($month != '' && $year != '') {
            $query = $query->where(\DB::raw('month(created_at)'),'=',$month);
            $query = $query->where(\DB::raw('year(created_at)'),'=',$year);
        }

        $lead_cnt = $query->count();

        return $lead_cnt;
    }

    public static function getLeadDetailsById($lead_id){
        
        $query = Lead::query();
        $query = $query->join('users','users.id','=','lead_management.referredby');
        $query = $query->select('lead_management.*','users.name as referredby');
        $query = $query->where('lead_management.id','=',$lead_id);
        $res = $query->first();

        $lead = array();
        if (isset($res) && $res != '') {
            $lead['id'] = $res->id;
            $lead['name'] = $res->name;
            $lead['mail'] = $res->mail;
            $lead['s_email'] = $res->s_email;
            $lead['mobile'] = $res->mobile;
            $lead['other_number'] = $res->other_number;
            $lead['display_name'] = $res->display_name;
            $lead['service'] = $res->service;
            $lead['status'] = $res->status;
            $lead['remarks'] = $res->remarks;
            $lead['coordinator_name'] = $res->coordinator_name;
            $lead['website'] = $res->website;
            $lead['source'] = $res->source;
            $lead['designation'] = $res->designation;
            $lead['referredby'] = $res->referredby;

            $location ='';
            if($res->city!=''){
                $location .= $res->city;
            }
            if($res->state!=''){
                if($location=='')
                    $location .= $res->state;
                else
                    $location .= ", ".$res->state;
            }
            if($res->country!=''){
                if($location=='')
                    $location .= $res->country;
                else
                    $location .= ", ".$res->country;
            }
            $lead['location'] = $location;
            $lead['lead_status'] = $res->lead_status;
        }

        return $lead;
    }
}

