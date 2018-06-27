<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Input;

class Bills extends Model
{
    public $timestamps = false;
    public $table = "bills";

    // create the validation rules ------------------------
    public static $rules
        = array(
            'company_name'=>'required',
            'candidate_name'=>'required',
            'candidate_contact_number'=>'required',
            'designation_offered'=>'required',
            'date_of_joining'=>'required',
            'job_location'=>'required',
            'fixed_salary'=>'required',
           // 'percentage_charged'=>'required',
            'source'=>'required',
            'client_name'=>'required',
            'client_contact_number'=>'required',
            'client_email_id'=>'required',
            'address_of_communication'=>'required',
        );

    public static $customMessages = array(
        'company_name.required' => 'Company Name is required field',
        'candidate_name.required' => 'Candidate Name is required field',
        'candidate_contact_number.required' => 'Candidate Contact Number is required field',
        'designation_offered.required' => 'Designation offered is required field',
        'date_of_joining.required' => 'Date of Joining is required field',
        'job_location.required' => 'Job Location is required field',
        'fixed_salary.required' => 'Fixed Salary is required field',
        //'percentage_charged.required' => 'Percentage Charged is required field',
        'source.required' => 'Source is required field',
        'client_name.required' => 'Client Name is required field',
        'client_contact_number.required' => 'Client Contact Number is required field',
        'client_email_id.required' => 'Client Email ID is required field',
        'address_of_communication.required' => 'Address of Communication is required field',
    );

    public static function getBillsByIds(array $ids){
        $date_class = new Date();

        $bills_query = Bills::query();
        $bills_query = $bills_query->join('users','users.id','bills.uploaded_by');
        $bills_query = $bills_query->select('bills.*','users.name as name');
        $bills_query = $bills_query->whereIn('bills.id',$ids);

        $bills_res = $bills_query->get();

        $bills = array();
        $i = 0 ;
        foreach ($bills_res as $key=>$value){
            $bills[$i]['id'] = $value->id;
            $bills[$i]['company_name'] = $value->company_name;
            $bills[$i]['candidate_name'] = $value->candidate_name;
            $bills[$i]['candidate_contact_number'] = $value->candidate_contact_number;
            $bills[$i]['designation_offered'] = $value->designation_offered;
            $bills[$i]['date_of_joining'] = $date_class->changeYMDtoDMY($value->date_of_joining);
            $bills[$i]['job_location'] = $value->job_location;
            $bills[$i]['fixed_salary'] = $value->fixed_salary;
            $bills[$i]['percentage_charged'] = $value->percentage_charged;
            $bills[$i]['source'] = $value->source;
            $bills[$i]['client_name'] = $value->client_name;
            $bills[$i]['client_contact_number'] = $value->client_contact_number;
            $bills[$i]['client_email_id'] = $value->client_email_id;
            $bills[$i]['address_of_communication'] = $value->address_of_communication;
            $bills[$i]['user_name'] = $value->name;
            $bills[$i]['status'] = $value->status;

            // get employee efforts
            $efforts = Bills::getEmployeeEffortsById($value->id);
            $efforts_str = '';
            foreach ($efforts as $k=>$v){
                if($efforts_str==''){
                    $efforts_str = $k .'('.$v . '%)';
                }
                else{
                    $efforts_str .= ', '. $k .'('.$v . '%)';
                }
            }
            $bills[$i]['efforts'] = $efforts_str;
            $i++;
        }

        return $bills;
    }

    public static function getAllBills($status=0,$all=0,$user_id=0){
        $date_class = new Date();

        $cancel_bill = 1;

        $cancel = array($cancel_bill);

        $bills_query = Bills::query();
        $bills_query = $bills_query->join('job_openings','job_openings.id','=','bills.job_id');
       // $bills_query = $bills_query->join('bills_efforts','bills_efforts.bill_id','=','bills.id');
        $bills_query = $bills_query->join('client_basicinfo','client_basicinfo.id','=','job_openings.client_id');
        $bills_query = $bills_query->join('candidate_basicinfo','candidate_basicinfo.id','=','bills.candidate_id');
        $bills_query = $bills_query->join('users','users.id','bills.uploaded_by');
        $bills_query = $bills_query->select('bills.*','users.name as name','job_openings.posting_title','client_basicinfo.display_name','job_openings.city','candidate_basicinfo.full_name'
        ,'candidate_basicinfo.lname');

        if($all==0){
            //$bills_query = $bills_query->where(function($bills_query) use ($user_id){
              //  $bills_query = $bills_query->where('client_basicinfo.account_manager_id',$user_id);
                //$bills_query = $bills_query->orwhere('bills_efforts.employee_name',$user_id);
                $bills_query = $bills_query->where('uploaded_by',$user_id);
            //});
        }

        $bills_query = $bills_query->where('bills.status',$status);
        $bills_query = $bills_query->whereNotIn('cancel_bill',$cancel);

        $bills_res = $bills_query->get();

        $bills = array();
        $i = 0 ;
        foreach ($bills_res as $key=>$value){
            $bills[$i]['id'] = $value->id;
            $bills[$i]['company_name'] = $value->company_name;
            $bills[$i]['candidate_name'] = $value->candidate_name;
            $bills[$i]['candidate_contact_number'] = $value->candidate_contact_number;
            $bills[$i]['designation_offered'] = $value->designation_offered;
            $bills[$i]['date_of_joining'] = $date_class->changeYMDtoDMY($value->date_of_joining);
            $bills[$i]['job_location'] = $value->job_location;
            $bills[$i]['fixed_salary'] = $value->fixed_salary;
            $bills[$i]['percentage_charged'] = $value->percentage_charged;
            $bills[$i]['source'] = $value->source;
            $bills[$i]['client_name'] = $value->client_name;
            $bills[$i]['client_contact_number'] = $value->client_contact_number;
            $bills[$i]['client_email_id'] = $value->client_email_id;
            $bills[$i]['address_of_communication'] = $value->address_of_communication;
            $bills[$i]['user_name'] = $value->name;
            $bills[$i]['status'] = $value->status;
            $bills[$i]['uploaded_by'] = $value->uploaded_by;
            $bills[$i]['posting_title'] = $value->posting_title;
            $bills[$i]['display_name'] = $value->display_name;
            $bills[$i]['city'] = $value->city;
            $bills[$i]['cname'] = $value->full_name;
            $bills[$i]['cancel_bill'] = $value->cancel_bill;

            // get employee efforts
            $efforts = Bills::getEmployeeEffortsNameById($value->id);
            $efforts_str = '';
            foreach ($efforts as $k=>$v){
                if($efforts_str==''){
                    $efforts_str = $k .'('.(int)$v . '%)';
                }
                else{
                    $efforts_str .= ', '. $k .'('.(int)$v . '%)';
                }
            }
            $bills[$i]['efforts'] = $efforts_str;
            $i++;
        }

        return $bills;
    }

    public static function getCancelBills($status=0,$all=0,$user_id=0){
        $date_class = new Date();

        $cancel_bill = 1;

        $cancel = array($cancel_bill);

        $bills_query = Bills::query();
        $bills_query = $bills_query->join('job_openings','job_openings.id','=','bills.job_id');
        $bills_query = $bills_query->join('client_basicinfo','client_basicinfo.id','=','job_openings.client_id');
        $bills_query = $bills_query->join('candidate_basicinfo','candidate_basicinfo.id','=','bills.candidate_id');
        $bills_query = $bills_query->join('users','users.id','bills.uploaded_by');
        $bills_query = $bills_query->select('bills.*','users.name as name','job_openings.posting_title','client_basicinfo.display_name','job_openings.city','candidate_basicinfo.full_name'
        ,'candidate_basicinfo.lname');

        if($all==0){
            $bills_query = $bills_query->where('uploaded_by',$user_id);
        }

        $bills_query = $bills_query->where('bills.status',$status);
        $bills_query = $bills_query->whereIn('cancel_bill',$cancel);

        $bills_res = $bills_query->get();

        $bills = array();
        $i = 0 ;
        foreach ($bills_res as $key=>$value){
            $bills[$i]['id'] = $value->id;
            $bills[$i]['company_name'] = $value->company_name;
            $bills[$i]['candidate_name'] = $value->candidate_name;
            $bills[$i]['candidate_contact_number'] = $value->candidate_contact_number;
            $bills[$i]['designation_offered'] = $value->designation_offered;
            $bills[$i]['date_of_joining'] = $date_class->changeYMDtoDMY($value->date_of_joining);
            $bills[$i]['job_location'] = $value->job_location;
            $bills[$i]['fixed_salary'] = $value->fixed_salary;
            $bills[$i]['percentage_charged'] = $value->percentage_charged;
            $bills[$i]['source'] = $value->source;
            $bills[$i]['client_name'] = $value->client_name;
            $bills[$i]['client_contact_number'] = $value->client_contact_number;
            $bills[$i]['client_email_id'] = $value->client_email_id;
            $bills[$i]['address_of_communication'] = $value->address_of_communication;
            $bills[$i]['user_name'] = $value->name;
            $bills[$i]['status'] = $value->status;
            $bills[$i]['uploaded_by'] = $value->uploaded_by;
            $bills[$i]['posting_title'] = $value->posting_title;
            $bills[$i]['display_name'] = $value->display_name;
            $bills[$i]['city'] = $value->city;
            $bills[$i]['cname'] = $value->full_name;
            $bills[$i]['cancel_bill'] = $value->cancel_bill;

            // get employee efforts
            $efforts = Bills::getEmployeeEffortsNameById($value->id);
            $efforts_str = '';
            foreach ($efforts as $k=>$v){
                if($efforts_str==''){
                    $efforts_str = $k .'('.(int)$v . '%)';
                }
                else{
                    $efforts_str .= ', '. $k .'('.(int)$v . '%)';
                }
            }
            $bills[$i]['efforts'] = $efforts_str;
            $i++;
        }

        return $bills;
    }

    public static function getShowBill($id){
        
        $bills = Bills::leftjoin('candidate_basicinfo','candidate_basicinfo.id','=','bills.candidate_id')
                    ->leftjoin('bills_efforts', 'bills_efforts.bill_id', '=', 'bills.id')
                    ->leftjoin('bills_doc', 'bills_doc.bill_id', '=', 'bills.id')
                    //->join('users','users.id','=','bills_efforts.employee_name')
                    ->select('bills.id as id', 'bills.company_name as company_name', 'bills.candidate_contact_number as number', 'bills.designation_offered as offered', 'bills.date_of_joining as date', 'bills.job_location as location', 'bills.fixed_salary as salary', 'bills.percentage_charged as percentage', 'bills.remarks as remarks', 'bills.source as source', 'bills.client_name as client_name', 'bills.client_contact_number as contact_number', 'bills.client_email_id as email_id', 'bills.address_of_communication as address', 'candidate_basicinfo.full_name as candidate_name', 'bills_efforts.employee_percentage as employee_percentage', 'bills_doc.file as file')
                    ->where('bills.id', $id)
                    ->first();


        $billsdetails = array();
        $employeename = array();
        $employeepercentage = array();
    
         if(isset($bills) && sizeof($bills) > 0){
            $billsdetails['id'] = $bills->id;
            $billsdetails['company_name'] = $bills->company_name;
            $billsdetails['candidate_name'] = $bills->candidate_name;
            $billsdetails['candidate_contact_number'] = $bills->number;
            $billsdetails['designation_offered'] = $bills->offered;
            $billsdetails['date_of_joining'] = $bills->date;
            $billsdetails['job_location'] = $bills->location;
            $billsdetails['fixed_salary'] = $bills->salary;
            $billsdetails['percentage_charged'] = $bills->percentage;
            $billsdetails['description'] = $bills->remarks;
            $billsdetails['source'] = $bills->source;
            $billsdetails['client_name'] =$bills->client_name;
            $billsdetails['client_contact_number'] = $bills->contact_number;
            $billsdetails['client_email_id'] = $bills->email_id;
            $billsdetails['address_of_communication'] = $bills->address;
            $billsdetails['fileurl'] = $bills->file;
            //$billsdetails['employee_name'] = $bills->ename;
            //$billsdetails['employee_percentage'] = $bills->employee_percentage;

        $efforts = Bills::getEmployeeEffortsNameById($id);

        // set employee name and percentage
        $i = 0;
        if (isset($efforts) && sizeof($efforts) > 0) {
            foreach ($efforts as $k => $v) {
                $employee_name[$i] = $k;
                $employee_percentage[$i] = $v;
                $i++;
            }
        }
         }
          
          $i = 0;
            $billsdetails['files'] = array();
            $billsFiles = BillsDoc::select('bills_doc.*')
                ->where('bills_doc.bill_id',$id)
                ->get();
            $utils = new Utils();
            if(isset($billsFiles) && sizeof($billsFiles) > 0){
                foreach ($billsFiles as $billfile) {
                    $billsdetails['files'][$i]['id'] = $billfile->id;
                    $billsdetails['files'][$i]['fileName'] = $billfile->file;
                    $billsdetails['files'][$i]['url'] = "../../".$billfile->file;
                    $billsdetails['files'][$i]['name'] = $billfile->name ;
                    $billsdetails['files'][$i]['size'] = $utils->formatSizeUnits($billfile->size);

                    $i++;

                }
            }

        $viewVariable = array();
        $viewVariable['billsdetails'] = $billsdetails;
        $viewVariable['employee_name'] = $employee_name;
        $viewVariable['employee_percentage'] = $employee_percentage;
        //print_r($viewVariable);exit;

        return $viewVariable;
    }

    public static function getRecoveryReport(){
        $date_class = new Date();

        $recovery_query = Bills::query();
        $recovery_query = $recovery_query->join('job_openings','job_openings.id','=','bills.job_id');
        $recovery_query = $recovery_query->join('client_basicinfo','client_basicinfo.id','=','job_openings.client_id');
        $recovery_query = $recovery_query->join('candidate_basicinfo','candidate_basicinfo.id','=','bills.candidate_id');
        $recovery_query = $recovery_query->where('bills.status','=',1);
        $recovery_query = $recovery_query->select('bills.*','candidate_basicinfo.full_name as fname','client_basicinfo.display_name as cname','job_openings.posting_title as position');
        $recovery_res = $recovery_query->get();

        $recovery = array();
        $i = 0;
        foreach ($recovery_res as $key => $value) {
            $fixed_salary = $value->fixed_salary;
            $percentage_charged = $value->percentage_charged;
            $billing = ($fixed_salary * $percentage_charged) / 100;

            $expected_payment = (($billing * 90) / 100) + (($billing * 18) / 100);

            $recovery[$i]['candidate_name'] = $value->fname;
            $recovery[$i]['company_name'] = $value->company_name;
            $recovery[$i]['position'] = $value->designation_offered;
            $recovery[$i]['salary_offered'] = $value->fixed_salary;
            $recovery[$i]['billing'] = $billing;
            $recovery[$i]['expected_payment'] = $expected_payment;
            $recovery[$i]['joining_date'] = $date_class->changeYMDtoDMY($value->date_of_joining);
            $recovery[$i]['contact_person'] = $value->client_name;

            $efforts = Bills::getEmployeeEffortsNameById($value->id);
            $efforts_str = '';
            foreach ($efforts as $key => $value) {
                if($efforts_str == ''){
                    $efforts_str = $key . '(' . (int)$value . '%)';
                }
                else{
                    $efforts_str .= ',' . $key . '(' . (int)$value . '%)';
                }
            }
            $recovery[$i]['efforts'] = $efforts_str;

            $i++;
        }

        return $recovery;
    }

    public static function getSelectionReport($m1,$m2,$month,$year){
        $date_class = new Date();

        $select = Input::get('select');

        $selection_query = Bills::query();
        $selection_query = $selection_query->join('job_openings','job_openings.id','=','bills.job_id');
        $selection_query = $selection_query->join('client_basicinfo','client_basicinfo.id','=','job_openings.client_id');
        $selection_query = $selection_query->join('candidate_basicinfo','candidate_basicinfo.id','=','bills.candidate_id');
        $selection_query = $selection_query->select('bills.*','candidate_basicinfo.full_name as fname','client_basicinfo.display_name as cname','job_openings.posting_title as position');

        //$selection_query = $selection_query->where(function($selection_query) use ($month,$year){
        if ($select == 0) {   
            $selection_query = $selection_query->where('date_of_joining','>=', $month);
            $selection_query = $selection_query->where('date_of_joining','<=', $year);
        }

        if ($select == 1) {   
            $selection_query = $selection_query->where(\DB::raw('MONTH(date_of_joining)'),'=', $month);
            $selection_query = $selection_query->where(\DB::raw('year(date_of_joining)'),'=', $year);
        }

        if ($select == 2) {   
            $selection_query = $selection_query->where(\DB::raw('MONTH(date_of_joining)'),'>=', $m1);
            $selection_query = $selection_query->where(\DB::raw('MONTH(date_of_joining)'),'<=', $m2);
            $selection_query = $selection_query->where(\DB::raw('year(date_of_joining)'),'=', $year);
        }

        if ($select == 3) {   
            //$selection_query = $selection_query->where(\DB::raw('MONTH(date_of_joining)'),'=', $month);
            $selection_query = $selection_query->where(\DB::raw('year(date_of_joining)'),'=', $year);
        }
        //});

        $selection_res = $selection_query->get();

        /*$selection = array();
        $i = 0;
        foreach ($selection_res as $key => $value) {

            $fixed_salary = $value->fixed_salary;
            $percentage_charged = $value->percentage_charged;
            $billing = ($fixed_salary * $percentage_charged) / 100;
            $gst = ($billing * 18 ) / 100;
            $invoice = $billing+$gst;
            $payment = (($billing * 90) / 100) + (($billing * 18) / 100);

            $selection[$i]['candidate_name'] = $value->fname;
            $selection[$i]['company_name'] = $value->company_name;
            $selection[$i]['position'] = $value->position;
            $selection[$i]['fixed_salary'] = $value->fixed_salary;
            $selection[$i]['billing'] = $billing;
            $selection[$i]['gst'] = $gst;
            $selection[$i]['invoice'] = $invoice;
            $selection[$i]['payment'] = $payment;
            $selection[$i]['joining_date'] = $date_class->changeYMDtoDMY($value->date_of_joining);
            $selection[$i]['contact_person'] = $value->client_name;
            $selection[$i]['location'] = $value->job_location;
            $i++;
        }*/

        return $selection_res;
    }

    public static function getUserwiseReport($user_id,$m1,$m2,$month,$year){
        $date_class = new Date();

        $select =Input::get('select');

        $userwise_query = Bills::query();
        $userwise_query = $userwise_query->join('job_openings','job_openings.id','=','bills.job_id');
        $userwise_query = $userwise_query->join('bills_efforts','bills_efforts.bill_id','=','bills.id');
        $userwise_query = $userwise_query->join('client_basicinfo','client_basicinfo.id','=','job_openings.client_id');
        $userwise_query = $userwise_query->join('candidate_basicinfo','candidate_basicinfo.id','=','bills.candidate_id');
        $userwise_query = $userwise_query->select('bills.*','candidate_basicinfo.full_name as fname','client_basicinfo.display_name as cname','job_openings.posting_title as position');
        $userwise_query = $userwise_query->where('bills_efforts.employee_name',$user_id);

        if ($select == 0) {
            $userwise_query = $userwise_query->where('date_of_joining','>=', $month);
            $userwise_query = $userwise_query->where('date_of_joining','<=', $year);
        }

        else if ($select == 1) {
            $userwise_query = $userwise_query->where(\DB::raw('MONTH(date_of_joining)'),'=', $month);
            $userwise_query = $userwise_query->where(\DB::raw('year(date_of_joining)'),'=', $year);
        }

        else if ($select == 2) {
            $userwise_query = $userwise_query->where(\DB::raw('MONTH(date_of_joining)'),'>=', $m1);
            $userwise_query = $userwise_query->where(\DB::raw('MONTH(date_of_joining)'),'<=', $m2);
            $userwise_query = $userwise_query->where(\DB::raw('year(date_of_joining)'),'=', $year);
        }
        $userwise_res = $userwise_query->get();

        //print_r($userwise_res);exit;

        return $userwise_res;
    }

    public static function getEmployeeEffortsById($id){

        $efforts_query = BillsEffort::query();
        $efforts_query = $efforts_query->join('users','users.id','=','bills_efforts.employee_name');
        $efforts_query = $efforts_query->where('bill_id',$id);
        $res = $efforts_query->get();

        $employees = array();
        $i = 0 ;
        foreach ($res as $key=>$value){
            $employees[$value->id] = $value->employee_percentage;
            $i++;
        }

        return $employees;

    }

    public static function getEmployeeEffortsNameById($id){

        $efforts_query = BillsEffort::query();
        $efforts_query = $efforts_query->join('users','users.id','=','bills_efforts.employee_name');
        $efforts_query = $efforts_query->where('bill_id',$id);
        $res = $efforts_query->get();

        $employees = array();
        $i = 0 ;
        foreach ($res as $key=>$value){
            $employees[$value->name] = $value->employee_percentage;
            $i++;
        }

        return $employees;

    }
}
