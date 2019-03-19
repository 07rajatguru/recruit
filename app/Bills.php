<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Input;
use App\Utils;

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
        $bills_query = $bills_query->join('job_openings','job_openings.id','=','bills.job_id');
        $bills_query = $bills_query->join('client_basicinfo','client_basicinfo.id','=','job_openings.client_id');
        $bills_query = $bills_query->join('candidate_basicinfo','candidate_basicinfo.id','=','bills.candidate_id');
        $bills_query = $bills_query->select('bills.*','users.name as name','candidate_basicinfo.full_name as c_name','candidate_basicinfo.phone as candidate_other_no','client_basicinfo.other_number as client_other_no');
        $bills_query = $bills_query->whereIn('bills.id',$ids);

        $bills_res = $bills_query->get();

        $bills = array();
        $i = 0 ;
        foreach ($bills_res as $key=>$value){
            $bills[$i]['id'] = $value->id;
            $bills[$i]['company_name'] = $value->company_name;
            $bills[$i]['candidate_name'] = $value->c_name;
            $bills[$i]['candidate_contact_number'] = $value->candidate_contact_number;
            $bills[$i]['designation_offered'] = $value->designation_offered;
            $bills[$i]['date_of_joining'] = $date_class->changeYMDtoDMY($value->date_of_joining);
            $bills[$i]['job_location'] = $value->job_location;
            $bills[$i]['fixed_salary'] = Utils::IND_money_format($value->fixed_salary);
            $bills[$i]['percentage_charged'] = $value->percentage_charged;
            $bills[$i]['source'] = $value->source;
            $bills[$i]['client_name'] = $value->client_name;
            $bills[$i]['client_contact_number'] = $value->client_contact_number;
            $bills[$i]['client_email_id'] = $value->client_email_id;
            $bills[$i]['address_of_communication'] = $value->address_of_communication;
            $bills[$i]['user_name'] = $value->name;
            $bills[$i]['status'] = $value->status;

            // get employee efforts
            $efforts = Bills::getEmployeeEffortsNameById($value->id);
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
            $bills[$i]['candidate_other_no'] = '/'. $value->candidate_other_no;
            $bills[$i]['client_other_no'] = '/'. $value->client_other_no;
            $i++;
        }

        return $bills;
    }

    public static function getAllBills($status=0,$all=0,$user_id=0,$limit=0,$offset=0,$search=0,$order=0,$type='asc'){
        $date_class = new Date();

        $cancel_bill = 1;

        $cancel = array($cancel_bill);

        $bills_query = Bills::query();
        $bills_query = $bills_query->join('job_openings','job_openings.id','=','bills.job_id');
       // $bills_query = $bills_query->join('bills_efforts','bills_efforts.bill_id','=','bills.id');
        $bills_query = $bills_query->join('client_basicinfo','client_basicinfo.id','=','job_openings.client_id');
        $bills_query = $bills_query->join('candidate_basicinfo','candidate_basicinfo.id','=','bills.candidate_id');
        $bills_query = $bills_query->join('users','users.id','bills.uploaded_by');
        $bills_query = $bills_query->select('bills.*','users.name as name','job_openings.posting_title','client_basicinfo.display_name','job_openings.city','candidate_basicinfo.full_name','candidate_basicinfo.lname','client_basicinfo.id as client_id');

        if($all==0){
            //$bills_query = $bills_query->where(function($bills_query) use ($user_id){
              //  $bills_query = $bills_query->where('client_basicinfo.account_manager_id',$user_id);
                //$bills_query = $bills_query->orwhere('bills_efforts.employee_name',$user_id);
                $bills_query = $bills_query->where('uploaded_by',$user_id);
            //});
        }
        if (isset($limit) && $limit > 0) {
            $bills_query = $bills_query->limit($limit);
        }
        if (isset($offset) && $offset > 0) {
            $bills_query = $bills_query->offset($offset);
        }
        if (isset($order) && $order !='') {
            $bills_query = $bills_query->orderBy($order,$type);
        }
        $bills_query = $bills_query->where(function($bills_query) use ($search){
            $bills_query = $bills_query->where('users.name','like',"%$search%");
            $bills_query = $bills_query->orwhere('bills.company_name','like',"%$search%");
            $bills_query = $bills_query->orwhere('candidate_basicinfo.full_name','like',"%$search%");
            $bills_query = $bills_query->orwhere('bills.date_of_joining','like',"%$search%");
            $bills_query = $bills_query->orwhere('bills.fixed_salary','like',"%$search%");
            $bills_query = $bills_query->orwhere('candidate_basicinfo.mobile','like',"%$search%");
            $bills_query = $bills_query->orwhere('bills.client_name','like',"%$search%");
        });

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
            $bills[$i]['date_of_joining_ts'] = strtotime($value->date_of_joining);
            $bills[$i]['job_location'] = $value->job_location;
            $bills[$i]['fixed_salary'] = Utils::IND_money_format(round($value->fixed_salary));
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
            $bills[$i]['job_confirmation'] = $value->joining_confirmation_mail;
            $url = 'uploads/bills/'.$value->id.'/'.$value->id.'_invoice.xls';
            if (!file_exists($url) && !is_dir($url)) {
                $bills[$i]['invoice_url'] = NULL;
            }
            else{
                $bills[$i]['invoice_url'] = $url;
            }

            // get lead employee efforts
            $lead_efforts = BillsLeadEfforts::getLeadEmployeeEffortsNameById($value->id);
            $lead_efforts_str = '';
            foreach ($lead_efforts as $k=>$v){
                if($lead_efforts_str==''){
                    $lead_efforts_str = $k .'('.(int)$v . '%)';
                }
                else{
                    $lead_efforts_str .= ', '. $k .'('.(int)$v . '%)';
                }
            }
            $bills[$i]['lead_efforts'] = $lead_efforts_str;
            $bills[$i]['client_id'] = $value->client_id;
            $i++;
        }
        return $bills;
    }

    public static function getAllBillsCount($status=0,$all=0,$user_id=0,$search=0){
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
        $bills_query = $bills_query->where(function($bills_query) use ($search){
            $bills_query = $bills_query->where('users.name','like',"%$search%");
            $bills_query = $bills_query->orwhere('bills.company_name','like',"%$search%");
            $bills_query = $bills_query->orwhere('candidate_basicinfo.full_name','like',"%$search%");
            $bills_query = $bills_query->orwhere('bills.date_of_joining','like',"%$search%");
            $bills_query = $bills_query->orwhere('bills.fixed_salary','like',"%$search%");
            $bills_query = $bills_query->orwhere('candidate_basicinfo.mobile','like',"%$search%");
            $bills_query = $bills_query->orwhere('bills.client_name','like',"%$search%");
        });
        $bills_query = $bills_query->where('bills.status',$status);
        $bills_query = $bills_query->whereNotIn('cancel_bill',$cancel);
        $bills_count = $bills_query->count();

        return $bills_count;
    }

    public static function getCancelBills($status=0,$all=0,$user_id=0,$limit=0,$offset=0,$search=0,$order=0,$type='asc'){
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

        if (isset($limit) && $limit > 0) {
            $bills_query = $bills_query->limit($limit);
        }
        if (isset($offset) && $offset > 0) {
            $bills_query = $bills_query->offset($offset);
        }
        if (isset($order) && $order !='') {
            $bills_query = $bills_query->orderBy($order,$type);
        }
        $bills_query = $bills_query->where(function($bills_query) use ($search){
            $bills_query = $bills_query->where('users.name','like',"%$search%");
            $bills_query = $bills_query->orwhere('bills.company_name','like',"%$search%");
            $bills_query = $bills_query->orwhere('candidate_basicinfo.full_name','like',"%$search%");
            $bills_query = $bills_query->orwhere('bills.date_of_joining','like',"%$search%");
            $bills_query = $bills_query->orwhere('bills.fixed_salary','like',"%$search%");
            $bills_query = $bills_query->orwhere('candidate_basicinfo.mobile','like',"%$search%");
            $bills_query = $bills_query->orwhere('bills.client_name','like',"%$search%");
        });

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
            $bills[$i]['date_of_joining_ts'] = strtotime($value->date_of_joining);
            $bills[$i]['job_location'] = $value->job_location;
            $bills[$i]['fixed_salary'] = Utils::IND_money_format(round($value->fixed_salary));
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
            $bills[$i]['job_confirmation'] = $value->joining_confirmation_mail;
            $url = 'uploads/bills/'.$value->id.'/'.$value->id.'_invoice.xls';
            if (!file_exists($url) && !is_dir($url)) {
                $bills[$i]['invoice_url'] = NULL;
            }
            else{
                $bills[$i]['invoice_url'] = $url;
            }

            // get lead employee efforts
            $lead_efforts = BillsLeadEfforts::getLeadEmployeeEffortsNameById($value->id);
            $lead_efforts_str = '';
            foreach ($lead_efforts as $k=>$v){
                if($lead_efforts_str==''){
                    $lead_efforts_str = $k .'('.(int)$v . '%)';
                }
                else{
                    $lead_efforts_str .= ', '. $k .'('.(int)$v . '%)';
                }
            }
            $bills[$i]['lead_efforts'] = $lead_efforts_str;
            $i++;
        }

        return $bills;
    }

    public static function getAllCancelBillsCount($status=0,$all=0,$user_id=0,$search=0){
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
        $bills_query = $bills_query->where(function($bills_query) use ($search){
            $bills_query = $bills_query->where('users.name','like',"%$search%");
            $bills_query = $bills_query->orwhere('bills.company_name','like',"%$search%");
            $bills_query = $bills_query->orwhere('candidate_basicinfo.full_name','like',"%$search%");
            $bills_query = $bills_query->orwhere('bills.date_of_joining','like',"%$search%");
            $bills_query = $bills_query->orwhere('bills.fixed_salary','like',"%$search%");
            $bills_query = $bills_query->orwhere('candidate_basicinfo.mobile','like',"%$search%");
            $bills_query = $bills_query->orwhere('bills.client_name','like',"%$search%");
        });
        $bills_query = $bills_query->where('bills.status',$status);
        $bills_query = $bills_query->whereIn('cancel_bill',$cancel);
        $bills_count = $bills_query->count();

        return $bills_count;
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
            $billsdetails['fixed_salary'] = Utils::IND_money_format(round($bills->salary));
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

        // Lead Employees name
        $lead_efforts = BillsLeadEfforts::getLeadEmployeeEffortsNameById($id);
        if (isset($lead_efforts) && sizeof($lead_efforts) > 0) {
            foreach ($lead_efforts as $key => $value) {
                $lead_name = $key;
                $lead_percentage = $value;
            }
        }
        else{
            $lead_name = '';
            $lead_percentage = '';
        }

        $viewVariable = array();
        $viewVariable['billsdetails'] = $billsdetails;
        $viewVariable['employee_name'] = $employee_name;
        $viewVariable['employee_percentage'] = $employee_percentage;
        $viewVariable['lead_name'] = $lead_name;
        $viewVariable['lead_percentage'] = $lead_percentage;
        //print_r($viewVariable);exit;

        return $viewVariable;
    }

    public static function getRecoveryReport(){
        $date_class = new Date();

        $cancel = 1;
        $cancel_bill = array($cancel);

        $recovery_query = Bills::query();
        $recovery_query = $recovery_query->join('job_openings','job_openings.id','=','bills.job_id');
        $recovery_query = $recovery_query->join('client_basicinfo','client_basicinfo.id','=','job_openings.client_id');
        $recovery_query = $recovery_query->join('candidate_basicinfo','candidate_basicinfo.id','=','bills.candidate_id');
        $recovery_query = $recovery_query->where('bills.status','=',1);
        $recovery_query = $recovery_query->whereNotIn('bills.cancel_bill',$cancel_bill);
        $recovery_query = $recovery_query->select('bills.*','candidate_basicinfo.full_name as fname','client_basicinfo.display_name as cname','job_openings.posting_title as position');
        $recovery_res = $recovery_query->get();

        $recovery = array();
        $i = 0;
        foreach ($recovery_res as $key => $value) {
            $fixed_salary = $value->fixed_salary;
            $percentage_charged = (float)$value->percentage_charged;

            if($percentage_charged==0)
            {
                $billing = '0';
                $expected_payment = '0';
            }
            else
            {
            $billing = ((float)$fixed_salary * (float)$percentage_charged) / 100;

            $expected_payment = (((float)$billing * 90) / 100) + (((float)$billing * 18) / 100);
            }


            $billing = ((float)$fixed_salary * (float)$percentage_charged) / 100;

            $expected_payment = (((float)$billing * 90) / 100) + (((float)$billing * 18) / 100);

            $recovery[$i]['candidate_name'] = $value->fname;
            $recovery[$i]['company_name'] = $value->company_name;
            $recovery[$i]['position'] = $value->designation_offered;
            $recovery[$i]['salary_offered'] = Utils::IND_money_format(round($value->fixed_salary));
            $recovery[$i]['billing'] = Utils::IND_money_format(round($billing));
            $recovery[$i]['expected_payment'] = Utils::IND_money_format(round($expected_payment));
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

        $cancel = 1;
        $cancel_bill = array($cancel);

        $select = Input::get('select');

        if($select=='')
        {
            $select='2';
        }

        $selection_query = Bills::query();
        $selection_query = $selection_query->join('job_openings','job_openings.id','=','bills.job_id');
        $selection_query = $selection_query->join('client_basicinfo','client_basicinfo.id','=','job_openings.client_id');
        $selection_query = $selection_query->join('candidate_basicinfo','candidate_basicinfo.id','=','bills.candidate_id');
        $selection_query = $selection_query->select('bills.*','candidate_basicinfo.full_name as fname','client_basicinfo.display_name as cname','job_openings.posting_title as position');
        $selection_query = $selection_query->whereNotIn('bills.cancel_bill',$cancel_bill);

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

        $cancel = 1;
        $cancel_bill = array($cancel);

        $select =Input::get('select');

        $userwise_query = Bills::query();
        $userwise_query = $userwise_query->join('job_openings','job_openings.id','=','bills.job_id');
        $userwise_query = $userwise_query->join('bills_efforts','bills_efforts.bill_id','=','bills.id');
        $userwise_query = $userwise_query->join('client_basicinfo','client_basicinfo.id','=','job_openings.client_id');
        $userwise_query = $userwise_query->join('candidate_basicinfo','candidate_basicinfo.id','=','bills.candidate_id');
        $userwise_query = $userwise_query->select('bills.*','candidate_basicinfo.full_name as fname','client_basicinfo.display_name as cname','job_openings.posting_title as position');
        $userwise_query = $userwise_query->where('bills_efforts.employee_name',$user_id);
        $userwise_query = $userwise_query->whereNotIn('bills.cancel_bill',$cancel_bill);

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

    public static function getRecoveryReportdata(){
        $date_class = new Date();

        $cancel = 1;
        $cancel_bill = array($cancel);

        $recovery_query = Bills::query();
        $recovery_query = $recovery_query->join('job_openings','job_openings.id','=','bills.job_id');
        $recovery_query = $recovery_query->join('client_basicinfo','client_basicinfo.id','=','job_openings.client_id');
        $recovery_query = $recovery_query->join('candidate_basicinfo','candidate_basicinfo.id','=','bills.candidate_id');
        $recovery_query = $recovery_query->where('bills.status','=',1);
        $recovery_query = $recovery_query->whereNotIn('bills.cancel_bill',$cancel_bill);
        $recovery_query = $recovery_query->select('bills.*','candidate_basicinfo.full_name as fname','client_basicinfo.display_name as cname','job_openings.posting_title as position');
        $recovery_res = $recovery_query->get();

        $recovery = array();
        $i = 0;
        foreach ($recovery_res as $key => $value) {
            $fixed_salary = $value->fixed_salary;
            $percentage_charged = (float)$value->percentage_charged;

            if($percentage_charged==0)
            {
                $billing = '0';
                $expected_payment = '0';
            }
            else
            {
            $billing = ((float)$fixed_salary * (float)$percentage_charged) / 100;

            $expected_payment = (((float)$billing * 90) / 100) + (((float)$billing * 18) / 100);
            }

            $efforts = Bills::getEmployeeEffortsNameById($value->id);
            $efforts_str = '';
            foreach ($efforts as $key1 => $value1) {
                if($efforts_str == ''){
                    $efforts_str = $key1 . '(' . (int)$value1 . '%)';
                }
                else{
                    $efforts_str .= ',' . $key1 . '(' . (int)$value1 . '%)';
                }
            }

            $data[] = array(
            $recovery[$i]['candidate_name'] = $value->fname,
            $recovery[$i]['company_name'] = $value->company_name,
            $recovery[$i]['position'] = $value->designation_offered,
            $recovery[$i]['salary_offered'] = Utils::IND_money_format(round($value->fixed_salary)),
            $recovery[$i]['billing'] = Utils::IND_money_format(round($billing)),
            $recovery[$i]['expected_payment'] = Utils::IND_money_format(round($expected_payment)),
            $recovery[$i]['joining_date'] = $date_class->changeYMDtoDMY($value->date_of_joining),
            $recovery[$i]['efforts'] = $efforts_str,
            $recovery[$i]['contact_person'] = $value->client_name,
            );
            $i++;
        }

        return $data;
    }

    public static function getSelectionReportdata($m1,$m2,$month,$year){
        $date_class = new Date();

        $cancel = 1;
        $cancel_bill = array($cancel);

        $select = Input::get('select');

        $selection_query = Bills::query();
        $selection_query = $selection_query->join('job_openings','job_openings.id','=','bills.job_id');
        $selection_query = $selection_query->join('client_basicinfo','client_basicinfo.id','=','job_openings.client_id');
        $selection_query = $selection_query->join('candidate_basicinfo','candidate_basicinfo.id','=','bills.candidate_id');
        $selection_query = $selection_query->select('bills.*','candidate_basicinfo.full_name as fname','client_basicinfo.display_name as cname','job_openings.posting_title as position');
        $selection_query = $selection_query->whereNotIn('bills.cancel_bill',$cancel_bill);

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

        $selection = array();
        $i = 0;
        if(sizeof($selection_res)>0){
        foreach ($selection_res as $key => $value) {

            $fixed_salary = $value->fixed_salary;
            $percentage_charged = (float)$value->percentage_charged;

            if($percentage_charged==0)
            {
                $billing = '0';
                $gst = '0';
                $invoice = '0';
                $payment = '0';
            }
            else
            {
                $billing = ((float)$fixed_salary * (float)$percentage_charged) / 100;
                $gst = ((float)$billing * 18 ) / 100;
                $invoice = (float)$billing+(float)$gst;
                $payment = (((float)$billing * 90) / 100) + (((float)$billing * 18) / 100);
            }

            $data[] = array(
            $selection[$i]['candidate_name'] = $value->fname,
            $selection[$i]['company_name'] = $value->company_name,
            $selection[$i]['position'] = $value->position,
            $selection[$i]['fixed_salary'] = Utils::IND_money_format(round($value->fixed_salary)),
            $selection[$i]['billing'] = Utils::IND_money_format(round($billing)),
            $selection[$i]['gst'] = Utils::IND_money_format(round($gst)),
            $selection[$i]['invoice'] = Utils::IND_money_format(round($invoice)),
            $selection[$i]['payment'] = Utils::IND_money_format(round($payment)),
            $selection[$i]['joining_date'] = $date_class->changeYMDtoDMY($value->date_of_joining),
            $selection[$i]['contact_person'] = $value->client_name,
            $selection[$i]['location'] = $value->job_location,
            );
            $i++;
        }
        }

        return $data;
    }

    public static function getUserwiseReportdata($user_id,$m1,$m2,$month,$year){
        $date_class = new Date();

        $cancel = 1;
        $cancel_bill = array($cancel);

        $select =Input::get('select');

        $userwise_query = Bills::query();
        $userwise_query = $userwise_query->join('job_openings','job_openings.id','=','bills.job_id');
        $userwise_query = $userwise_query->join('bills_efforts','bills_efforts.bill_id','=','bills.id');
        $userwise_query = $userwise_query->join('client_basicinfo','client_basicinfo.id','=','job_openings.client_id');
        $userwise_query = $userwise_query->join('candidate_basicinfo','candidate_basicinfo.id','=','bills.candidate_id');
        $userwise_query = $userwise_query->select('bills.*','candidate_basicinfo.full_name as fname','client_basicinfo.display_name as cname','job_openings.posting_title as position');
        $userwise_query = $userwise_query->where('bills_efforts.employee_name',$user_id);
        $userwise_query = $userwise_query->whereNotIn('bills.cancel_bill',$cancel_bill);

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

        $userwise = array();
            $i = 0;
            foreach ($userwise_res as $key => $value) {
                $fixed_salary = $value->fixed_salary;
                $percentage_charged = (float)$value->percentage_charged;

                if($percentage_charged<=0)
                {
                    $billing='0';
                }
                else
                {
                    $billing = ((float)$fixed_salary * (float)$percentage_charged) / 100;
                }
                
                $efforts = Bills::getEmployeeEffortsNameById($value->id);
                $efforts_str = '';
                foreach ($efforts as $key1 => $value1) {
                    if($efforts_str == ''){
                        $efforts_str = $key1 . '(' . (int)$value1 . '%)';
                    }
                    else{
                        $efforts_str .= ',' . $key1 . '(' . (int)$value1 . '%)';
                    }
                }
                $data[] = array(
                $userwise[$i]['candidate_name'] = $value->fname,
                $userwise[$i]['company_name'] = $value->company_name,
                $userwise[$i]['position'] = $value->position,
                $userwise[$i]['fixed_salary'] = Utils::IND_money_format(round($value->fixed_salary)),
                $userwise[$i]['billing'] = Utils::IND_money_format(round($billing)),
                $userwise[$i]['joining_date'] = $date_class->changeYMDtoDMY($value->date_of_joining),
                $userwise[$i]['efforts'] = $efforts_str,
                );
                $i++;
            }

        //print_r($userwise_res);exit;

        return $data;
    }
    
    public static function getJoinConfirmationMail($id){

        $join_mail = Bills::query();
        $join_mail = $join_mail->join('candidate_basicinfo','candidate_basicinfo.id','=','bills.candidate_id');
        $join_mail = $join_mail->join('job_openings','job_openings.id','=','bills.job_id');
        $join_mail = $join_mail->join('client_basicinfo','client_basicinfo.id','=','job_openings.client_id');
        $join_mail = $join_mail->leftjoin('client_address','client_address.client_id','=','client_basicinfo.id');
        $join_mail = $join_mail->select('bills.*','candidate_basicinfo.full_name as candidate_name','client_basicinfo.coordinator_prefix as coordinator_prefix','client_basicinfo.gst_no as gst_no','client_address.*');
        $join_mail = $join_mail->where('bills.id',$id);
        $join_mail_res = $join_mail->first();

        $join_confirmation_mail = array();
        if (isset($join_mail_res) && sizeof($join_mail_res)>0) {
            $salary = $join_mail_res->fixed_salary;
            $pc = $join_mail_res->percentage_charged;

            $fees = ($salary * $pc)/100;
            $gst = ($fees * 18)/100;
            $billing_amount = $fees + $gst;

            $sgst = ($fees * 9)/100;
            $cgst = ($fees * 9)/100;

            $join_confirmation_mail['client_name'] = $join_mail_res->coordinator_prefix. " " .$join_mail_res->client_name;
            $join_confirmation_mail['company_name'] = $join_mail_res->company_name;
            $join_confirmation_mail['candidate_name'] = $join_mail_res->candidate_name;
            $join_confirmation_mail['designation_offered'] = $join_mail_res->designation_offered;
            $join_confirmation_mail['joining_date'] = $join_mail_res->date_of_joining;
            $join_confirmation_mail['job_location'] = $join_mail_res->job_location;
            $join_confirmation_mail['fixed_salary'] = $join_mail_res->fixed_salary;
            $join_confirmation_mail['percentage_charged'] = $join_mail_res->percentage_charged;
            $join_confirmation_mail['fees'] = $fees;
            $join_confirmation_mail['gst'] = $gst;
            $join_confirmation_mail['billing_amount'] = $billing_amount;
            $join_confirmation_mail['client_email_id'] = $join_mail_res->client_email_id;
            $join_confirmation_mail['candidate_id'] = $join_mail_res->candidate_id;
            $join_confirmation_mail['sgst'] = $sgst;
            $join_confirmation_mail['cgst'] = $cgst;
            $join_confirmation_mail['amount_in_words'] = Utils::number_in_words($billing_amount);
            $join_confirmation_mail['gst_no'] = $join_mail_res->gst_no;
            $join_confirmation_mail['gst_check'] = substr($join_mail_res->gst_no,0,2);

            $billing_address ='';
            if($join_mail_res->billing_street1!=''){
                $billing_address .= $join_mail_res->billing_street1;
            }
            if($join_mail_res->billing_street2!=''){
                if($billing_address=='')
                    $billing_address .= $join_mail_res->billing_street2;
                else
                    $billing_address .= ", ".$join_mail_res->billing_street2;
            }
            if($join_mail_res->billing_city!=''){
                if($billing_address=='')
                    $billing_address .= $join_mail_res->billing_city;
                else
                    $billing_address .= ", ".$join_mail_res->billing_city;
            }
            if($join_mail_res->billing_state!=''){
                if($billing_address=='')
                    $billing_address .= $join_mail_res->billing_state;
                else
                    $billing_address .= ", ".$join_mail_res->billing_state;
            }
            if($join_mail_res->billing_country!=''){
                if($billing_address=='')
                    $billing_address .= $join_mail_res->billing_country;
                else
                    $billing_address .= ", ".$join_mail_res->billing_country;
            }
            if($join_mail_res->billing_code!=''){
                if($billing_address=='')
                    $billing_address .= $join_mail_res->billing_code;
                else
                    $billing_address .= ", ".$join_mail_res->billing_code;
            }
            $join_confirmation_mail['billing_address'] = $billing_address;

            $shipping_address ='';
            if($join_mail_res->shipping_street1!=''){
                $shipping_address .= $join_mail_res->shipping_street1;
            }
            if($join_mail_res->shipping_street2!=''){
                if($shipping_address=='')
                    $shipping_address .= $join_mail_res->shipping_street2;
                else
                    $shipping_address .= ", ".$join_mail_res->shipping_street2;
            }
            if($join_mail_res->shipping_city!=''){
                if($shipping_address=='')
                    $shipping_address .= $join_mail_res->shipping_city;
                else
                    $shipping_address .= ", ".$join_mail_res->shipping_city;
            }
            if($join_mail_res->shipping_state!=''){
                if($shipping_address=='')
                    $shipping_address .= $join_mail_res->shipping_state;
                else
                    $shipping_address .= ", ".$join_mail_res->shipping_state;
            }
            if($join_mail_res->shipping_country!=''){
                if($shipping_address=='')
                    $shipping_address .= $join_mail_res->shipping_country;
                else
                    $shipping_address .= ", ".$join_mail_res->shipping_country;
            }
            if($join_mail_res->shipping_code!=''){
                if($shipping_address=='')
                    $shipping_address .= $join_mail_res->shipping_code;
                else
                    $shipping_address .= ", ".$join_mail_res->shipping_code;
            }
            $join_confirmation_mail['shipping_address'] = $shipping_address;
        }

        return $join_confirmation_mail;
    }

    public static function getCandidateOwnerEmail($bill_id){

        $query = Bills::query();
        $query = $query->join('candidate_otherinfo','candidate_otherinfo.candidate_id','=','bills.candidate_id');
        $query = $query->join('users','users.id','=','candidate_otherinfo.owner_id');
        $query = $query->where('bills.id','=',$bill_id);
        $query = $query->select('users.email as candidateowneremail');
        $res = $query->first();

        return $res;
    }

    public static function getClientOwnerEmail($bill_id){

        $query = Bills::query();
        $query = $query->join('job_openings','job_openings.id','=','bills.job_id');
        $query = $query->join('client_basicinfo','client_basicinfo.id','=','job_openings.client_id');
        $query = $query->join('users','users.id','=','client_basicinfo.account_manager_id');
        $query = $query->where('bills.id','=',$bill_id);
        $query = $query->select('users.email as clientowneremail');
        $response = $query->first();

        return $response;
    }

    public static function getCandidatesalaryByJobidCandidateid($job_id,$candidate_id){

        $salary_data = Bills::query();
        $salary_data = $salary_data->where('job_id',$job_id);
        $salary_data = $salary_data->where('candidate_id',$candidate_id);
        $salary_data = $salary_data->select('fixed_salary');
        $salary_res = $salary_data->first();

        $salary = '';
        if (isset($salary_res) && $salary_res != '') {
            $salary = $salary_res->fixed_salary;
        }
        return $salary;
    }

    public static function getPersonwiseReportData($user_id=NULL,$current_year,$next_year){
        
        $personwise_query = Bills::query();
        $personwise_query = $personwise_query->join('candidate_basicinfo','candidate_basicinfo.id','=','bills.candidate_id');
        $personwise_query = $personwise_query->join('job_openings','job_openings.id','=','bills.job_id');
        $personwise_query = $personwise_query->join('client_basicinfo','client_basicinfo.id','=','job_openings.client_id');
        $personwise_query = $personwise_query->select('bills.*','candidate_basicinfo.full_name as candidate_name','client_basicinfo.coordinator_prefix as coordinator_prefix');
        if ($user_id != NULL) {
            $personwise_query = $personwise_query->join('bills_efforts','bills_efforts.bill_id','=','bills.id');
            $personwise_query = $personwise_query->where('bills_efforts.employee_name',$user_id);
        }
        $personwise_query = $personwise_query->where('bills.status','=','1');
        $personwise_query = $personwise_query->where('bills.cancel_bill','=','0');
        $personwise_query = $personwise_query->where('bills.date_of_joining','>=',$current_year);
        $personwise_query = $personwise_query->where('bills.date_of_joining','<=',$next_year);
        $personwise_res = $personwise_query->get();

        $person_data = array();
        $j = 0;
        if (isset($personwise_res) && sizeof($personwise_res)>0) {
            foreach ($personwise_res as $key => $value) {
                $salary = $value->fixed_salary;
                $pc = $value->percentage_charged;

                $fees = ($salary * $pc)/100;
                $gst = ($fees * 18)/100;
                $billing_amount = $fees + $gst;
                $payment = (($fees * 90)/100)+ (($fees * 18)/100);

                $person_data[$j]['candidate_name'] = $value->candidate_name;
                $person_data[$j]['company_name'] = $value->company_name;
                $person_data[$j]['position'] = $value->designation_offered;
                $person_data[$j]['salary_offered'] = $value->fixed_salary;
                $person_data[$j]['billing'] = $fees;
                $person_data[$j]['joining_date'] = date('d-m-Y', strtotime($value->date_of_joining));

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
                    // Person wise billing amount
                    $user_name = User::getUserNameById($user_id);
                    if ($user_name == $k) {
                        $efforts_person = $v;
                        $person_billing = ($fees * $efforts_person) / 100;
                    }
                }
                if (isset($person_billing) && $person_billing != '') {
                    $person_data[$j]['person_billing'] = $person_billing;
                }
                else {
                    $person_data[$j]['person_billing'] = 0;   
                }
                $person_data[$j]['efforts'] = $efforts_str;
                $person_data[$j]['client_name'] = $value->coordinator_prefix. " " .$value->client_name;
                $person_data[$j]['location'] = $value->job_location;
                $person_data[$j]['gst'] = $gst;
                $person_data[$j]['invoice_raised'] = $billing_amount;
                $person_data[$j]['payment'] = $payment;
                $j++;
            }
        }

        return $person_data;
    }

    public static function getClientwiseReportData($client_name,$current_year,$next_year){

        $clientwise_query = Bills::query();
        $clientwise_query = $clientwise_query->join('candidate_basicinfo','candidate_basicinfo.id','=','bills.candidate_id');
        $clientwise_query = $clientwise_query->join('job_openings','job_openings.id','=','bills.job_id');
        $clientwise_query = $clientwise_query->join('client_basicinfo','client_basicinfo.id','=','job_openings.client_id');
        $clientwise_query = $clientwise_query->join('users','users.id','=','client_basicinfo.account_manager_id');
        $clientwise_query = $clientwise_query->select('bills.*','candidate_basicinfo.full_name as candidate_name','users.name as owner_name','client_basicinfo.coordinator_name as coordinator_name','client_basicinfo.coordinator_prefix as coordinator_prefix');
        $clientwise_query = $clientwise_query->where('bills.company_name','like',"%$client_name%");
        $clientwise_query = $clientwise_query->where('bills.status','=','1');
        $clientwise_query = $clientwise_query->where('bills.cancel_bill','=','0');
        $clientwise_query = $clientwise_query->where('bills.date_of_joining','>=',$current_year);
        $clientwise_query = $clientwise_query->where('bills.date_of_joining','<=',$next_year);
        $clientwise_res = $clientwise_query->get();

        $client_data = array();
        $i = 0;
        foreach ($clientwise_res as $key => $value) {
            $salary = $value->fixed_salary;
            $pc = $value->percentage_charged;
            $fees = ($salary * $pc)/100;
            $gst = ($fees * 18)/100;
            $billing_amount = $fees + $gst;

            $client_data[$i]['candidate_name'] = $value->candidate_name;
            $client_data[$i]['owner_name'] = $value->owner_name;
            $client_data[$i]['position'] = $value->designation_offered;
            $client_data[$i]['salary_offered'] = $value->fixed_salary;
            $client_data[$i]['billing'] = $fees;
            $client_data[$i]['gst'] = $gst;
            $client_data[$i]['invoice'] = $billing_amount;
            $client_data[$i]['joining_date'] = date('d-m-Y', strtotime($value->date_of_joining));

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
            $client_data[$i]['efforts'] = $efforts_str;
            $client_data[$i]['coordinator_name'] = $value->coordinator_prefix. " " .$value->coordinator_name;
            $i++;
        }

        return $client_data;
    }
}
