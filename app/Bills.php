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
        'source.required' => 'Source is required field',
        'client_name.required' => 'Client Name is required field',
        'client_contact_number.required' => 'Client Contact Number is required field',
        'client_email_id.required' => 'Client Email ID is required field',
        'address_of_communication.required' => 'Address of Communication is required field'
    );

    public $upload_type = array('Unedited Resume'=>'Unedited Resume',
        'Offer Letter' => 'Offer Letter',
        'Others' => 'Others');

    public static function getBillsByIds(array $ids) {

        $date_class = new Date();

        $bills_query = Bills::query();
        $bills_query = $bills_query->join('users','users.id','bills.uploaded_by');
        $bills_query = $bills_query->join('job_openings','job_openings.id','=','bills.job_id');
        $bills_query = $bills_query->join('client_basicinfo','client_basicinfo.id','=','job_openings.client_id');
        $bills_query = $bills_query->join('candidate_basicinfo','candidate_basicinfo.id','=','bills.candidate_id');
        $bills_query = $bills_query->select('bills.*','users.name as name','candidate_basicinfo.full_name as c_name','candidate_basicinfo.phone as candidate_other_no','client_basicinfo.other_number as client_other_no','job_openings.remote_working as remote_working');
        $bills_query = $bills_query->whereIn('bills.id',$ids);

        $bills_res = $bills_query->get();

        $bills = array();
        $i = 0 ;
        foreach ($bills_res as $key=>$value) {
            
            $bills[$i]['id'] = $value->id;
            $bills[$i]['company_name'] = $value->company_name;
            $bills[$i]['candidate_name'] = $value->c_name;
            $bills[$i]['candidate_contact_number'] = $value->candidate_contact_number;
            $bills[$i]['designation_offered'] = $value->designation_offered;
            $bills[$i]['date_of_joining'] = $date_class->changeYMDtoDMY($value->date_of_joining);

            if($value->remote_working == '1') {

                $bills[$i]['job_location'] = "Remote";
            }
            else {

                $bills[$i]['job_location'] = $value->job_location;
            }

            $salary = str_replace(",", "", $value->fixed_salary);
            $salary = (int)$salary;

            $bills[$i]['fixed_salary'] = Utils::IND_money_format(round($salary));

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
            $bills[$i]['candidate_other_no'] = $value->candidate_other_no;
            $bills[$i]['client_other_no'] = $value->client_other_no;

            // get Lead employee efforts
            $lead_efforts = BillsLeadEfforts::getLeadEmployeeEffortsNameById($value->id);
            $lead_efforts_str = '';
            foreach ($lead_efforts as $k1=>$v1) {

                if($lead_efforts_str=='') {
                    $lead_efforts_str = $k1 .'('.(int)$v1 . '%)';
                }
                else{
                    $lead_efforts_str .= ', '. $k1 .'('.(int)$v1 . '%)';
                }
            }
            $bills[$i]['lead_efforts'] = $lead_efforts_str;
            $i++;
        }

        return $bills;
    }

    public static function getAllBills($status=0,$all=0,$user_id=0,$limit=0,$offset=0,$search=0,$order=0,$type='asc',$current_year=NULL,$next_year=NULL) {

        $date_class = new Date();
        $cancel_bill = 1;

        $cancel = array($cancel_bill);

        $bills_query = Bills::query();
        $bills_query = $bills_query->join('job_openings','job_openings.id','=','bills.job_id');
        $bills_query = $bills_query->leftjoin('bills_efforts','bills_efforts.bill_id','=','bills.id');
        $bills_query = $bills_query->leftjoin('client_basicinfo','client_basicinfo.id','=','job_openings.client_id');
        $bills_query = $bills_query->join('candidate_basicinfo','candidate_basicinfo.id','=','bills.candidate_id');
        $bills_query = $bills_query->join('users','users.id','bills.uploaded_by');
        $bills_query = $bills_query->select('bills.*','users.name as name','job_openings.posting_title','client_basicinfo.display_name','job_openings.city','candidate_basicinfo.full_name','candidate_basicinfo.lname','client_basicinfo.id as client_id','job_openings.remote_working as remote_working','client_basicinfo.account_manager_id');

        if($all==0) {
            $bills_query = $bills_query->where(function($bills_query) use ($user_id) {

                $bills_query = $bills_query->where('bills_efforts.employee_name',$user_id);
                $bills_query = $bills_query->orwhere('client_basicinfo.account_manager_id',$user_id);
            });
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

        if (isset($search) && $search != '') {

            $bills_query = $bills_query->where(function($bills_query) use ($search){

                $date_search = false;
                    $date_array = explode("-",$search);
                    if(isset($date_array) && sizeof($date_array)>0){
                        $stamp = strtotime($search);
                        if (is_numeric($stamp)){
                            $month = date( 'm', $stamp );
                            $day   = date( 'd', $stamp );
                            $year  = date( 'Y', $stamp );

                        if(checkdate($month, $day, $year)){
                            $date_search = true;
                        }
                    }
                }

                $bills_query = $bills_query->where('users.name','like',"%$search%");
                $bills_query = $bills_query->orwhere('client_basicinfo.display_name','like',"%$search%");
                $bills_query = $bills_query->orwhere('candidate_basicinfo.full_name','like',"%$search%");
                $bills_query = $bills_query->orwhere('bills.fixed_salary','like',"%$search%");
                $bills_query = $bills_query->orwhere('candidate_basicinfo.mobile','like',"%$search%");
                $bills_query = $bills_query->orwhere('bills.client_name','like',"%$search%");
                $bills_query = $bills_query->orwhere('job_openings.posting_title','like',"%$search%");
                $bills_query = $bills_query->orwhere('job_openings.city','like',"%$search%");
                
                if(($search == 'Remote') || ($search == 'remote')) {

                    $bills_query = $bills_query->orwhere('job_openings.remote_working','=',"1");
                }

                if($date_search){
                    $dateClass = new Date();
                    $search_string = $dateClass->changeDMYtoYMD($search);
                    $from_date = date("Y-m-d 00:00:00",strtotime($search_string));
                    $to_date = date("Y-m-d 23:59:59",strtotime($search_string));
                    $bills_query = $bills_query->orwhere('bills.date_of_joining','>=',"$from_date");
                    $bills_query = $bills_query->Where('bills.date_of_joining','<=',"$to_date");
                }
            });
        }

         // Get data by financial year
        if (isset($current_year) && $current_year != NULL) {
            $bills_query = $bills_query->where('bills.date_of_joining','>=',$current_year);
        }
        if (isset($next_year) && $next_year != NULL) {
            $bills_query = $bills_query->where('bills.date_of_joining','<=',$next_year);
        }

        $bills_query = $bills_query->where('bills.status',$status);
        $bills_query = $bills_query->whereNotIn('cancel_bill',$cancel);

        $bills_query = $bills_query->groupBy('bills.id');

        $bills_res = $bills_query->get();

        $bills = array();
        $i = 0 ;
        foreach ($bills_res as $key => $value) {

            $bills[$i]['id'] = $value->id;
            $bills[$i]['company_name'] = $value->company_name;
            $bills[$i]['candidate_name'] = $value->candidate_name;
            $bills[$i]['candidate_contact_number'] = $value->candidate_contact_number;
            $bills[$i]['designation_offered'] = $value->designation_offered;
            $bills[$i]['date_of_joining'] = $date_class->changeYMDtoDMY($value->date_of_joining);
            $bills[$i]['date_of_joining_ts'] = strtotime($value->date_of_joining);

            if($value->remote_working == '1') {

                $bills[$i]['job_location'] = "Remote";
            }
            else {

                $bills[$i]['job_location'] = $value->job_location;
            }

            $salary = str_replace(",", "", $value->fixed_salary);
            $salary = (int)$salary;
            $bills[$i]['fixed_salary'] = round($salary);

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

             if($value->remote_working == '1') {

                $bills[$i]['city'] = "Remote";
            }
            else {

                $bills[$i]['city'] = $value->city;
            }

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

            // Generate Excel & PDF Invoice URL

            $bill_invoice = BillsDoc::getBillInvoice($value->id,'Invoice');

            if(isset($bill_invoice) && $bill_invoice != '') {

                $excel_url = $bill_invoice['file'];
                $pdf_url = str_replace(".xls", ".pdf", $bill_invoice['file']);
            }
            else {
                $excel_url = '';
                $pdf_url = '';
            }

            if (!file_exists($excel_url) && !is_dir($excel_url)) {
                $bills[$i]['excel_invoice_url'] = NULL;
            }
            else{
                $bills[$i]['excel_invoice_url'] = $excel_url;
            }

            if (!file_exists($pdf_url) && !is_dir($pdf_url)) {
                $bills[$i]['pdf_invoice_url'] = NULL;
            }
            else{
                $bills[$i]['pdf_invoice_url'] = $pdf_url;
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
            $bills[$i]['account_manager_id'] = $value->account_manager_id;

            $i++;
        }
        return $bills;
    }

    public static function getAllBillsCount($status=0,$all=0,$user_id=0,$search=0,$current_year=NULL,$next_year=NULL) {
        
        $cancel_bill = 1;
        $cancel = array($cancel_bill);

        $bills_query = Bills::query();
        $bills_query = $bills_query->join('job_openings','job_openings.id','=','bills.job_id');
        $bills_query = $bills_query->leftjoin('bills_efforts','bills_efforts.bill_id','=','bills.id');
        $bills_query = $bills_query->leftjoin('client_basicinfo','client_basicinfo.id','=','job_openings.client_id');
        $bills_query = $bills_query->join('candidate_basicinfo','candidate_basicinfo.id','=','bills.candidate_id');
        $bills_query = $bills_query->join('users','users.id','bills.uploaded_by');

        $bills_query = $bills_query->select('bills.*','users.name as name','job_openings.posting_title','client_basicinfo.display_name','job_openings.city','candidate_basicinfo.full_name'
        ,'candidate_basicinfo.lname','job_openings.remote_working as remote_working');

        if($all==0) {

            $bills_query = $bills_query->where(function($bills_query) use ($user_id) {

                $bills_query = $bills_query->where('bills_efforts.employee_name',$user_id);
                $bills_query = $bills_query->orwhere('client_basicinfo.account_manager_id',$user_id);
            });
        }

        if (isset($search) && $search != '') {

            $bills_query = $bills_query->where(function($bills_query) use ($search) {

                $date_search = false;
                    $date_array = explode("-",$search);
                    if(isset($date_array) && sizeof($date_array)>0){
                        $stamp = strtotime($search);
                        if (is_numeric($stamp)){
                            $month = date( 'm', $stamp );
                            $day   = date( 'd', $stamp );
                            $year  = date( 'Y', $stamp );

                        if(checkdate($month, $day, $year)){
                            $date_search = true;
                        }
                    }
                }

                $bills_query = $bills_query->where('users.name','like',"%$search%");
                $bills_query = $bills_query->orwhere('client_basicinfo.display_name','like',"%$search%");
                $bills_query = $bills_query->orwhere('candidate_basicinfo.full_name','like',"%$search%");
                $bills_query = $bills_query->orwhere('bills.fixed_salary','like',"%$search%");
                $bills_query = $bills_query->orwhere('candidate_basicinfo.mobile','like',"%$search%");
                $bills_query = $bills_query->orwhere('bills.client_name','like',"%$search%");
                $bills_query = $bills_query->orwhere('job_openings.posting_title','like',"%$search%");
                $bills_query = $bills_query->orwhere('job_openings.city','like',"%$search%");

                if(($search == 'Remote') || ($search == 'remote')) {

                    $bills_query = $bills_query->orwhere('job_openings.remote_working','=',"1");
                }

                if($date_search) {

                    $dateClass = new Date();
                    $search_string = $dateClass->changeDMYtoYMD($search);
                    $from_date = date("Y-m-d 00:00:00",strtotime($search_string));
                    $to_date = date("Y-m-d 23:59:59",strtotime($search_string));
                    $bills_query = $bills_query->orwhere('bills.date_of_joining','>=',"$from_date");
                    $bills_query = $bills_query->Where('bills.date_of_joining','<=',"$to_date");
                }
            });
        }

        // Get data by financial year
        if (isset($current_year) && $current_year != NULL) {
            $bills_query = $bills_query->where('bills.date_of_joining','>=',$current_year);
        }
        if (isset($next_year) && $next_year != NULL) {
            $bills_query = $bills_query->where('bills.date_of_joining','<=',$next_year);
        }

        $bills_query = $bills_query->where('bills.status',$status);
        $bills_query = $bills_query->whereNotIn('cancel_bill',$cancel);

        $bills_query = $bills_query->groupBy('bills.id');

        $bills_query = $bills_query->get();

        $bills_count = $bills_query->count();

        return $bills_count;
    }

    public static function getCancelBills($status=0,$all=0,$user_id=0,$limit=0,$offset=0,$search=0,$order=0,$type='asc',$current_year=NULL,$next_year=NULL) {

        $date_class = new Date();

        $cancel_bill = 1;

        $cancel = array($cancel_bill);

        $bills_query = Bills::query();
        $bills_query = $bills_query->join('job_openings','job_openings.id','=','bills.job_id');
        $bills_query = $bills_query->leftjoin('bills_efforts','bills_efforts.bill_id','=','bills.id');
        $bills_query = $bills_query->leftjoin('client_basicinfo','client_basicinfo.id','=','job_openings.client_id');
        $bills_query = $bills_query->join('candidate_basicinfo','candidate_basicinfo.id','=','bills.candidate_id');
        $bills_query = $bills_query->join('users','users.id','bills.uploaded_by');

        $bills_query = $bills_query->select('bills.*','users.name as name','job_openings.posting_title','client_basicinfo.display_name','job_openings.city','candidate_basicinfo.full_name'
        ,'candidate_basicinfo.lname','job_openings.remote_working as remote_working','client_basicinfo.account_manager_id');

        if($all==0) {

            $bills_query = $bills_query->where(function($bills_query) use ($user_id) {

                $bills_query = $bills_query->where('bills_efforts.employee_name',$user_id);
                $bills_query = $bills_query->orwhere('uploaded_by',$user_id);
                $bills_query = $bills_query->orwhere('client_basicinfo.account_manager_id',$user_id);
            });
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

        if (isset($search) && $search != '') {

            $bills_query = $bills_query->where(function($bills_query) use ($search) {

                $date_search = false;
                    $date_array = explode("-",$search);
                    if(isset($date_array) && sizeof($date_array)>0){
                        $stamp = strtotime($search);
                        if (is_numeric($stamp)){
                            $month = date( 'm', $stamp );
                            $day   = date( 'd', $stamp );
                            $year  = date( 'Y', $stamp );

                        if(checkdate($month, $day, $year)){
                            $date_search = true;
                        }
                    }
                }

                $bills_query = $bills_query->where('users.name','like',"%$search%");
                $bills_query = $bills_query->orwhere('client_basicinfo.display_name','like',"%$search%");
                $bills_query = $bills_query->orwhere('bills.company_name','like',"%$search%");
                $bills_query = $bills_query->orwhere('candidate_basicinfo.full_name','like',"%$search%");
                $bills_query = $bills_query->orwhere('bills.fixed_salary','like',"%$search%");
                $bills_query = $bills_query->orwhere('candidate_basicinfo.mobile','like',"%$search%");
                $bills_query = $bills_query->orwhere('bills.client_name','like',"%$search%");
                $bills_query = $bills_query->orwhere('job_openings.posting_title','like',"%$search%");
                $bills_query = $bills_query->orwhere('job_openings.city','like',"%$search%");

                if(($search == 'Remote') || ($search == 'remote')) {

                    $bills_query = $bills_query->orwhere('job_openings.remote_working','=',"1");
                }

                if($date_search) {

                    $dateClass = new Date();
                    $search_string = $dateClass->changeDMYtoYMD($search);
                    $from_date = date("Y-m-d 00:00:00",strtotime($search_string));
                    $to_date = date("Y-m-d 23:59:59",strtotime($search_string));
                    $bills_query = $bills_query->orwhere('bills.date_of_joining','>=',"$from_date");
                    $bills_query = $bills_query->Where('bills.date_of_joining','<=',"$to_date");
                }
            });
        }

        // Get data by financial year
        if (isset($current_year) && $current_year != NULL) {
            $bills_query = $bills_query->where('bills.date_of_joining','>=',$current_year);
        }
        if (isset($next_year) && $next_year != NULL) {
            $bills_query = $bills_query->where('bills.date_of_joining','<=',$next_year);
        }

        $bills_query = $bills_query->where('bills.status',$status);
        $bills_query = $bills_query->whereIn('cancel_bill',$cancel);
        $bills_query = $bills_query->groupBy('bills.id');

        $bills_res = $bills_query->get();

        $bills = array();
        $i = 0 ;
        foreach ($bills_res as $key => $value) {

            $bills[$i]['id'] = $value->id;
            $bills[$i]['company_name'] = $value->company_name;
            $bills[$i]['candidate_name'] = $value->candidate_name;
            $bills[$i]['candidate_contact_number'] = $value->candidate_contact_number;
            $bills[$i]['designation_offered'] = $value->designation_offered;
            $bills[$i]['date_of_joining'] = $date_class->changeYMDtoDMY($value->date_of_joining);
            $bills[$i]['date_of_joining_ts'] = strtotime($value->date_of_joining);

            if($value->remote_working == '1') {

                $bills[$i]['job_location'] = "Remote";
            }
            else {

                $bills[$i]['job_location'] = $value->job_location;
            }

            $salary = str_replace(",", "", $value->fixed_salary);
            $salary = (int)$salary;
            $bills[$i]['fixed_salary'] = round($salary);

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

            if($value->remote_working == '1') {

                $bills[$i]['city'] = "Remote";
            }
            else {

                $bills[$i]['city'] = $value->city;
            }

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

            // Generate Excel Invoice URL
            $bill_invoice = BillsDoc::getBillInvoice($value->id,'Invoice');

            if(isset($bill_invoice) && $bill_invoice != '') {
                $excel_url = $bill_invoice['file'];
            }
            else {
                $excel_url = '';
            }
            
            if (!file_exists($excel_url) && !is_dir($excel_url)) {
                $bills[$i]['excel_invoice_url'] = NULL;
            }
            else{
                $bills[$i]['excel_invoice_url'] = $excel_url;
            }

            // Generate PDF Invoice URL
            $pdf_url = 'uploads/bills/'.$value->id.'/'.$value->id.'_invoice.pdf';
            if (!file_exists($pdf_url) && !is_dir($pdf_url)) {
                $bills[$i]['pdf_invoice_url'] = NULL;
            }
            else{
                $bills[$i]['pdf_invoice_url'] = $pdf_url;
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
            $bills[$i]['account_manager_id'] = $value->account_manager_id;
            $i++;
        }

        return $bills;
    }

    public static function getAllCancelBillsCount($status=0,$all=0,$user_id=0,$search=0,$current_year=NULL,$next_year=NULL) {

        $cancel_bill = 1;
        $cancel = array($cancel_bill);

        $bills_query = Bills::query();
        $bills_query = $bills_query->join('job_openings','job_openings.id','=','bills.job_id');
        $bills_query = $bills_query->leftjoin('bills_efforts','bills_efforts.bill_id','=','bills.id');
        $bills_query = $bills_query->leftjoin('client_basicinfo','client_basicinfo.id','=','job_openings.client_id');
        $bills_query = $bills_query->join('candidate_basicinfo','candidate_basicinfo.id','=','bills.candidate_id');
        $bills_query = $bills_query->join('users','users.id','bills.uploaded_by');

        $bills_query = $bills_query->select('bills.*','users.name as name','job_openings.posting_title','client_basicinfo.display_name','job_openings.city','candidate_basicinfo.full_name'
        ,'candidate_basicinfo.lname','job_openings.remote_working as remote_working');

        if($all==0) {

            $bills_query = $bills_query->where(function($bills_query) use ($user_id) {

                $bills_query = $bills_query->where('bills_efforts.employee_name',$user_id);
                $bills_query = $bills_query->orwhere('uploaded_by',$user_id);
                $bills_query = $bills_query->orwhere('client_basicinfo.account_manager_id',$user_id);
            });
        }

        if (isset($search) && $search != '') {

            $bills_query = $bills_query->where(function($bills_query) use ($search) {

                $date_search = false;
                    $date_array = explode("-",$search);
                    if(isset($date_array) && sizeof($date_array)>0){
                        $stamp = strtotime($search);
                        if (is_numeric($stamp)){
                            $month = date( 'm', $stamp );
                            $day   = date( 'd', $stamp );
                            $year  = date( 'Y', $stamp );

                        if(checkdate($month, $day, $year)){
                            $date_search = true;
                        }
                    }
                }
                
                $bills_query = $bills_query->where('users.name','like',"%$search%");
                $bills_query = $bills_query->orwhere('client_basicinfo.display_name','like',"%$search%");
                $bills_query = $bills_query->orwhere('bills.company_name','like',"%$search%");
                $bills_query = $bills_query->orwhere('candidate_basicinfo.full_name','like',"%$search%");
                $bills_query = $bills_query->orwhere('bills.fixed_salary','like',"%$search%");
                $bills_query = $bills_query->orwhere('candidate_basicinfo.mobile','like',"%$search%");
                $bills_query = $bills_query->orwhere('bills.client_name','like',"%$search%");
                $bills_query = $bills_query->orwhere('job_openings.posting_title','like',"%$search%");
                $bills_query = $bills_query->orwhere('job_openings.city','like',"%$search%");

                if(($search == 'Remote') || ($search == 'remote')) {

                    $bills_query = $bills_query->orwhere('job_openings.remote_working','=',"1");
                }

                if($date_search) {

                    $dateClass = new Date();
                    $search_string = $dateClass->changeDMYtoYMD($search);
                    $from_date = date("Y-m-d 00:00:00",strtotime($search_string));
                    $to_date = date("Y-m-d 23:59:59",strtotime($search_string));
                    $bills_query = $bills_query->orwhere('bills.date_of_joining','>=',"$from_date");
                    $bills_query = $bills_query->Where('bills.date_of_joining','<=',"$to_date");
                }
            });
        }

        // Get data by financial year
        if (isset($current_year) && $current_year != NULL) {
            $bills_query = $bills_query->where('bills.date_of_joining','>=',$current_year);
        }
        if (isset($next_year) && $next_year != NULL) {
            $bills_query = $bills_query->where('bills.date_of_joining','<=',$next_year);
        }

        $bills_query = $bills_query->where('bills.status',$status);
        $bills_query = $bills_query->whereIn('cancel_bill',$cancel);
        $bills_query = $bills_query->groupBy('bills.id');
        $bills_query = $bills_query->get();
        $bills_count = $bills_query->count();

        return $bills_count;
    }

    public static function getShowBill($id) {
        
        $bills = Bills::leftjoin('candidate_basicinfo','candidate_basicinfo.id','=','bills.candidate_id')
        ->leftjoin('bills_efforts', 'bills_efforts.bill_id', '=', 'bills.id')
        ->leftjoin('bills_doc', 'bills_doc.bill_id', '=', 'bills.id')
        ->select('bills.id as id', 'bills.company_name as company_name', 'bills.candidate_contact_number as number', 'bills.designation_offered as offered', 'bills.date_of_joining as date', 'bills.job_location as location', 'bills.fixed_salary as salary', 'bills.percentage_charged as percentage', 'bills.remarks as remarks', 'bills.source as source', 'bills.client_name as client_name', 'bills.client_contact_number as contact_number', 'bills.client_email_id as email_id', 'bills.address_of_communication as address', 'candidate_basicinfo.full_name as candidate_name', 'bills_efforts.employee_percentage as employee_percentage', 'bills_doc.file as file')
        ->where('bills.id', $id)
        ->first();


        $billsdetails = array();
        $employeename = array();
        $employeepercentage = array();
    
         if(isset($bills) && $bills != '') {

            $billsdetails['id'] = $bills->id;
            $billsdetails['company_name'] = $bills->company_name;
            $billsdetails['candidate_name'] = $bills->candidate_name;
            $billsdetails['candidate_contact_number'] = $bills->number;
            $billsdetails['designation_offered'] = $bills->offered;
            $billsdetails['date_of_joining'] = date('d-m-Y',strtotime($bills->date));
            $billsdetails['job_location'] = $bills->location;

            $salary = str_replace(",", "", $bills->salary);
            $salary = (int)$salary;
            $billsdetails['fixed_salary'] = round($salary);

            $billsdetails['percentage_charged'] = $bills->percentage;
            $billsdetails['description'] = $bills->remarks;
            $billsdetails['source'] = $bills->source;
            $billsdetails['client_name'] =$bills->client_name;
            $billsdetails['client_contact_number'] = $bills->contact_number;
            $billsdetails['client_email_id'] = $bills->email_id;
            $billsdetails['address_of_communication'] = $bills->address;
            $billsdetails['fileurl'] = $bills->file;

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
            else {

                $employee_name = '';
                $employee_percentage = '';
            }
        }

        $billModel = new Bills();
        $upload_type = $billModel->upload_type;

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
                $billsdetails['files'][$i]['category'] = $billfile->category;

                if (array_search($billfile->category, $upload_type)) {
                    unset($upload_type[array_search($billfile->category, $upload_type)]);
                }

                $i++;
            }
        }

        $upload_type['Others'] = 'Others';

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
        $viewVariable['upload_type'] = $upload_type;

        return $viewVariable;
    }

    public static function getRecoveryReport() {

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

            $salary = str_replace(",", "", $value->fixed_salary);
            $salary = (int)$salary;
            $fixed_salary = round($salary);

            $percentage_charged = $value->percentage_charged;
            $percentage_charged = (float)$percentage_charged;

            if($percentage_charged == 0) {

                $billing = '0';
                $expected_payment = '0';
            }
            else {

                $billing = ($fixed_salary * $percentage_charged) / 100;
                $expected_payment = (($billing * 90) / 100) + (($billing * 18) / 100);
            }

            $billing = ($fixed_salary * $percentage_charged) / 100;
            $expected_payment = (($billing * 90) / 100) + (($billing * 18) / 100);

            $recovery[$i]['candidate_name'] = $value->fname;
            $recovery[$i]['company_name'] = $value->company_name;
            $recovery[$i]['position'] = $value->designation_offered;
            $recovery[$i]['salary_offered'] = Utils::IND_money_format(round($fixed_salary));
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

    public static function getSelectionReport($m1,$m2,$month,$year) {

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
            $selection_query = $selection_query->where(\DB::raw('year(date_of_joining)'),'=', $year);
        }
        $selection_res = $selection_query->get();

        return $selection_res;
    }

    public static function getUserwiseReport($user_id,$m1,$m2,$month,$year) {

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

        return $userwise_res;
    }

    public static function getEmployeeEffortsById($id) {

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

    public static function getEmployeeEffortsNameById($id) {

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

    public static function getRecoveryReportdata() {

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

            $salary = str_replace(",", "", $value->fixed_salary);
            $salary = (int)$salary;
            $fixed_salary = round($salary);

            $percentage_charged = (float)$value->percentage_charged;

            if($percentage_charged==0) {

                $billing = '0';
                $expected_payment = '0';
            }
            else {

                $billing = ($fixed_salary * $percentage_charged) / 100;
                $expected_payment = (($billing * 90) / 100) + (($billing * 18) / 100);
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
                $recovery[$i]['salary_offered'] = Utils::IND_money_format(round($fixed_salary)),
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

    public static function getSelectionReportdata($m1,$m2,$month,$year) {

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
            $selection_query = $selection_query->where(\DB::raw('year(date_of_joining)'),'=', $year);
        }

        $selection_res = $selection_query->get();

        $selection = array();
        $i = 0;
        
        if(sizeof($selection_res)>0) {

            foreach ($selection_res as $key => $value) {

                $salary = str_replace(",", "", $value->fixed_salary);
                $salary = (int)$salary;
                $fixed_salary = round($salary);

                $percentage_charged = (float)$value->percentage_charged;

                if($percentage_charged == 0) {

                    $billing = '0';
                    $gst = '0';
                    $invoice = '0';
                    $payment = '0';
                }
                else {

                    $billing = ($fixed_salary * $percentage_charged) / 100;
                    $gst = ($billing * 18 ) / 100;
                    $invoice = $billing+$gst;
                    $payment = (($billing * 90) / 100) + (($billing * 18) / 100);
                }

                $data[] = array(
                    $selection[$i]['candidate_name'] = $value->fname,
                    $selection[$i]['company_name'] = $value->company_name,
                    $selection[$i]['position'] = $value->position,
                    $selection[$i]['fixed_salary'] = Utils::IND_money_format(round($fixed_salary)),
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

    public static function getUserwiseReportdata($user_id,$m1,$m2,$month,$year) {

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

                $salary = str_replace(",", "", $value->fixed_salary);
                $salary = (int)$salary;
                $fixed_salary = round($salary);

                $percentage_charged = (float)$value->percentage_charged;

                if($percentage_charged<=0) {

                    $billing='0';
                }
                else {

                    $billing = ($fixed_salary * $percentage_charged) / 100;
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
                $userwise[$i]['fixed_salary'] = Utils::IND_money_format(round($fixed_salary)),
                $userwise[$i]['billing'] = Utils::IND_money_format(round($billing)),
                $userwise[$i]['joining_date'] = $date_class->changeYMDtoDMY($value->date_of_joining),
                $userwise[$i]['efforts'] = $efforts_str,
                );
                $i++;
            }
        return $data;
    }
    
    public static function getJoinConfirmationMail($id) {

        $join_mail = Bills::query();
        $join_mail = $join_mail->join('candidate_basicinfo','candidate_basicinfo.id','=','bills.candidate_id');
        $join_mail = $join_mail->join('job_openings','job_openings.id','=','bills.job_id');
        $join_mail = $join_mail->join('client_basicinfo','client_basicinfo.id','=','job_openings.client_id');
        $join_mail = $join_mail->leftjoin('client_address','client_address.client_id','=','client_basicinfo.id');
        $join_mail = $join_mail->select('bills.*','candidate_basicinfo.full_name as candidate_name','client_basicinfo.coordinator_prefix as coordinator_prefix','client_basicinfo.gst_no as gst_no','client_address.*','client_basicinfo.name as client_company_name','job_openings.remote_working as remote_working');
        $join_mail = $join_mail->where('bills.id',$id);
        $join_mail_res = $join_mail->first();

        $join_confirmation_mail = array();

        if (isset($join_mail_res) && $join_mail_res != '') {

            $salary = str_replace(",", "", $join_mail_res->fixed_salary);
            $salary = (int)$salary;

            $pc = $join_mail_res->percentage_charged;
            $pc = (float)$pc;

            $fees = round(($salary * $pc)/100);
            $gst = round(($fees * 18)/100);
            $billing_amount = round($fees + $gst);

            $sgst = round(($fees * 9)/100);
            $cgst = round(($fees * 9)/100);

            $join_confirmation_mail['client_name'] = $join_mail_res->coordinator_prefix. " " .$join_mail_res->client_name;
            $join_confirmation_mail['company_name'] = $join_mail_res->company_name;
            $join_confirmation_mail['candidate_name'] = $join_mail_res->candidate_name;
            $join_confirmation_mail['designation_offered'] = $join_mail_res->designation_offered;
            $join_confirmation_mail['joining_date'] = $join_mail_res->date_of_joining;

            if($join_mail_res->remote_working == '1') {

                $join_confirmation_mail['job_location'] = "Remote";
            }
            else {

                $join_confirmation_mail['job_location'] = $join_mail_res->job_location;
            }

            $join_confirmation_mail['fixed_salary'] = Utils::IND_money_format(round($salary));
            $join_confirmation_mail['percentage_charged'] = $join_mail_res->percentage_charged;
            $join_confirmation_mail['fees'] = Utils::IND_money_format(round($fees));
            $join_confirmation_mail['gst'] = Utils::IND_money_format(round($gst));
            $join_confirmation_mail['billing_amount'] = Utils::IND_money_format(round($billing_amount));
            $join_confirmation_mail['client_email_id'] = $join_mail_res->client_email_id;
            $join_confirmation_mail['candidate_id'] = $join_mail_res->candidate_id;
            $join_confirmation_mail['sgst'] = $sgst;
            $join_confirmation_mail['cgst'] = $cgst;
            $join_confirmation_mail['amount_in_words'] = Utils::number_in_words(round($billing_amount));
            $join_confirmation_mail['gst_no'] = $join_mail_res->gst_no;
            $join_confirmation_mail['gst_check'] = substr($join_mail_res->gst_no,0,2);
            $join_confirmation_mail['client_company_name'] = $join_mail_res->client_company_name;

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

    public static function getCandidatesalaryByJobidCandidateid($job_id,$candidate_id) {

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

    public static function getPersonwiseReportData($user_id=NULL,$current_year,$next_year) {
        
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
        $personwise_query = $personwise_query->orderBy('bills.date_of_joining','ASC');
        $personwise_res = $personwise_query->get();

        $person_data = array();
        $j = 0;

        if (isset($personwise_res) && sizeof($personwise_res)>0) {

            $total_salary_offered = 0;
            $total_billing = 0;
            $total_monthwise_billing = 0;
            $total_monthwise_gst = 0;
            $total_monthwise_invoice_raised = 0;
            $total_monthwise_payment = 0;

            foreach ($personwise_res as $key => $value) {

                //$salary = $value->fixed_salary;
                $salary = str_replace(",", "", $value->fixed_salary);
                $salary = (int)$salary;

                $pc = $value->percentage_charged;
                $pc = (float)$pc;

                $fees = round(($salary * $pc)/100);
                $gst = round(($fees * 18)/100);
                $billing_amount = round($fees + $gst);
                $payment = round((($fees * 90)/100)+ (($fees * 18)/100));

                $person_data[$j]['candidate_name'] = $value->candidate_name;
                $person_data[$j]['company_name'] = $value->company_name;
                $person_data[$j]['position'] = $value->designation_offered;
                $person_data[$j]['salary_offered'] = Utils::IND_money_format(round($salary));
                $person_data[$j]['billing'] = Utils::IND_money_format(round($fees));
                $person_data[$j]['joining_date'] = date('d-m-Y', strtotime($value->date_of_joining));

                // get employee efforts
                $efforts = Bills::getEmployeeEffortsNameById($value->id);
                $efforts_str = '';
                $person_billing = 0;
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
                    $person_data[$j]['person_billing'] = Utils::IND_money_format(round($person_billing));

                    // Set for Eligibility Report
                    $person_data[$j]['person_billing_new'] = round($person_billing);
                }
                else {
                    $person_data[$j]['person_billing'] = 0;

                    // Set for Eligibility Report
                    $person_data[$j]['person_billing_new'] = 0;
                }

                $person_data[$j]['efforts'] = $efforts_str;
                $person_data[$j]['client_name'] = $value->coordinator_prefix. " " .$value->client_name;
                $person_data[$j]['location'] = $value->job_location;
                $person_data[$j]['gst'] = Utils::IND_money_format(round($gst));
                $person_data[$j]['invoice_raised'] = Utils::IND_money_format(round($billing_amount));
                $person_data[$j]['payment'] = Utils::IND_money_format(round($payment));

                $total_salary_offered = $total_salary_offered + $salary;
                $total_billing = $total_billing + $person_billing;

                $person_data[$j]['total_salary_offered'] = Utils::IND_money_format(round($total_salary_offered));
                $person_data[$j]['total_billing'] = Utils::IND_money_format(round($total_billing));

                // Specially for Montwise Report

                // 1. For Billing
                $billing = str_replace(",", "", $person_data[$j]['billing']);
                $billing = (int)$billing;

                $total_monthwise_billing = $total_monthwise_billing + $billing;
                $person_data[$j]['total_monthwise_billing'] = Utils::IND_money_format(round($total_monthwise_billing));

                // 2. For GST

                $gst = str_replace(",", "", $person_data[$j]['gst']);
                $gst = (int)$gst;

                $total_monthwise_gst = $total_monthwise_gst + $gst;
                $person_data[$j]['total_monthwise_gst'] = Utils::IND_money_format(round($total_monthwise_gst));

                // 3. For Invoice Raised

                $invoice_raised = str_replace(",", "", $person_data[$j]['invoice_raised']);
                $invoice_raised = (int)$invoice_raised;

                $total_monthwise_invoice_raised = $total_monthwise_invoice_raised + $invoice_raised;
                $person_data[$j]['total_monthwise_invoice_raised'] = Utils::IND_money_format(round($total_monthwise_invoice_raised));

                // 4. For Payment

                $payment = str_replace(",", "", $person_data[$j]['payment']);
                $payment = (int)$payment;

                $total_monthwise_payment = $total_monthwise_payment + $payment;
                $person_data[$j]['total_monthwise_payment'] = Utils::IND_money_format(round($total_monthwise_payment));

                $j++;
            }
        }
        return $person_data;
    }

    public static function getClientwiseReportData($client_id,$current_year,$next_year,$users=array()) {

        $clientwise_query = Bills::query();
        $clientwise_query = $clientwise_query->join('candidate_basicinfo','candidate_basicinfo.id','=','bills.candidate_id');
        $clientwise_query = $clientwise_query->join('job_openings','job_openings.id','=','bills.job_id');
        $clientwise_query = $clientwise_query->join('client_basicinfo','client_basicinfo.id','=','job_openings.client_id');
        $clientwise_query = $clientwise_query->join('users','users.id','=','client_basicinfo.account_manager_id');
        $clientwise_query = $clientwise_query->select('bills.*','candidate_basicinfo.full_name as candidate_name','users.name as owner_name','client_basicinfo.coordinator_name as coordinator_name','client_basicinfo.coordinator_prefix as coordinator_prefix');
        //$clientwise_query = $clientwise_query->where('bills.company_name','like',"%$client_name%");
        if (isset($users) && sizeof($users) > 0) {
            $clientwise_query = $clientwise_query->whereIn('users.id', $users);
        }
        $clientwise_query = $clientwise_query->where('client_basicinfo.id','=',$client_id);
        $clientwise_query = $clientwise_query->where('bills.status','=','1');
        $clientwise_query = $clientwise_query->where('bills.cancel_bill','=','0');
        $clientwise_query = $clientwise_query->where('bills.date_of_joining','>=',$current_year);
        $clientwise_query = $clientwise_query->where('bills.date_of_joining','<=',$next_year);
        $clientwise_res = $clientwise_query->get();

        $client_data = array();
        $i = 0;
        
        foreach ($clientwise_res as $key => $value) {

            $salary = str_replace(",", "", $value->fixed_salary);
            $salary = (int)$salary;

            $pc = $value->percentage_charged;
            $pc = (float)$pc;

            $fees = round(($salary * $pc)/100);
            $gst = round(($fees * 18)/100);
            $billing_amount = round($fees + $gst);

            $client_data[$i]['candidate_name'] = $value->candidate_name;
            $client_data[$i]['owner_name'] = $value->owner_name;
            $client_data[$i]['position'] = $value->designation_offered;
            $client_data[$i]['salary_offered'] = Utils::IND_money_format(round($salary));
            $client_data[$i]['billing'] = Utils::IND_money_format(round($fees));
            $client_data[$i]['gst'] = Utils::IND_money_format(round($gst));
            $client_data[$i]['invoice'] = Utils::IND_money_format(round($billing_amount));
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

    // Get Recovery bills data where PC value 0
    public static function getRecoveryBillData() {

        $query = Bills::query();
        $query = $query->select('bills.*');
        $query = $query->where('bills.status','=','1');
        $query = $query->where('bills.percentage_charged','=','0');
        // $query = $query->limit(1);
        $res = $query->get();

        return $res;
    }

    public static function getProductivityReportOfferAcceptanceRatio($user_id=0,$from_date=NULL,$to_date=NULL) {

        $query = Bills::query();
        $query = $query->leftjoin('candidate_otherinfo','candidate_otherinfo.candidate_id','=','bills.candidate_id');
        $query = $query->leftjoin('bills_date','bills_date.bills_id','=','bills.id');
        $query = $query->select(\DB::raw("COUNT(bills.candidate_id) as count"));

        if(isset($user_id) && $user_id > 0) {
            $query = $query->where('candidate_otherinfo.owner_id','=',$user_id);
        }

        $query = $query->where('bills_date.forecasting_date','>=',$from_date);

        $to_date = date("Y-m-d 23:59:59",strtotime($to_date));
        $query = $query->where('bills_date.forecasting_date','<=',$to_date);

        $query = $query->groupBy(\DB::raw('Date(bills_date.forecasting_date)'));
        $query_response = $query->get();

        $cnt= 0;
        foreach ($query_response as $key => $value) {

            $cnt += $value->count;
        }
        return $cnt;  
    }

    public static function getProductivityReportJoiningRatio($user_id=0,$from_date=NULL,$to_date=NULL) {

        $query = Bills::query();
        $query = $query->leftjoin('candidate_otherinfo','candidate_otherinfo.candidate_id','=','bills.candidate_id');
        $query = $query->leftjoin('bills_date','bills_date.bills_id','=','bills.id');
        $query = $query->select(\DB::raw("COUNT(bills.candidate_id) as count"));

        if(isset($user_id) && $user_id > 0) {
            $query = $query->where('candidate_otherinfo.owner_id','=',$user_id);
        }

        $query = $query->where('bills_date.recovery_date','>=',$from_date);

        $to_date = date("Y-m-d 23:59:59",strtotime($to_date));
        $query = $query->where('bills_date.recovery_date','<=',$to_date);

        $query = $query->groupBy(\DB::raw('Date(bills_date.recovery_date)'));
        $query_response = $query->get();

        $cnt= 0;
        foreach ($query_response as $key => $value) {

            $cnt += $value->count;
        }
        return $cnt;  
    }

    public static function getProductivityReportJoiningSuccessRatio($user_id=0,$from_date=NULL,$to_date=NULL) {

        $query = Bills::query();
        $query = $query->leftjoin('candidate_otherinfo','candidate_otherinfo.candidate_id','=','bills.candidate_id');
        $query = $query->leftjoin('bills_date','bills_date.bills_id','=','bills.id');
        $query = $query->select(\DB::raw("COUNT(bills.candidate_id) as count"));

        if(isset($user_id) && $user_id > 0) {
            $query = $query->where('candidate_otherinfo.owner_id','=',$user_id);
        }

        $query = $query->where('bills_date.joining_success_date','>=',$from_date);

        $to_date = date("Y-m-d 23:59:59",strtotime($to_date));
        $query = $query->where('bills_date.joining_success_date','<=',$to_date);
        
        $query = $query->groupBy(\DB::raw('Date(bills_date.joining_success_date)'));
        $query_response = $query->get();

        $cnt= 0;
        foreach ($query_response as $key => $value) {

            $cnt += $value->count;
        }
        return $cnt;
    }

    public static function getMonthwiseReportData($user_id=NULL,$current_year,$next_year,$type) {
        
        $superadmin_user_id = getenv('SUPERADMINUSERID');
        $saloni_user_id = getenv('SALONIUSERID');
        $manager_user_id = getenv('MANAGERUSERID');
        $hr_advisory_user_id = getenv('STRATEGYUSERID');

        $personwise_query = Bills::query();
        $personwise_query = $personwise_query->join('candidate_basicinfo','candidate_basicinfo.id','=','bills.candidate_id');
        $personwise_query = $personwise_query->join('job_openings','job_openings.id','=','bills.job_id');
        $personwise_query = $personwise_query->join('client_basicinfo','client_basicinfo.id','=','job_openings.client_id');
        $personwise_query = $personwise_query->select('bills.*','candidate_basicinfo.full_name as candidate_name','client_basicinfo.coordinator_prefix as coordinator_prefix');

        if ($user_id != NULL) {

            $personwise_query = $personwise_query->join('bills_efforts','bills_efforts.bill_id','=','bills.id');
            $personwise_query = $personwise_query->join('users','users.id','=','bills_efforts.employee_name');

            if($user_id == $superadmin_user_id || $user_id == $saloni_user_id) {

                if($type == 'adler') {
                }
                else if($type == 'recruitment') {
                    $personwise_query = $personwise_query->where('users.type','=','1');
                }
                else if($type == 'hr-advisory') {
                    $personwise_query = $personwise_query->where('users.type','=','2');
                }
            }
            else if($user_id == $manager_user_id) {
                $personwise_query = $personwise_query->where('users.type','=','1');
            }
            else if($user_id == $hr_advisory_user_id) {
                $personwise_query = $personwise_query->where('users.type','=','2');
            }
            $personwise_query = $personwise_query->groupBy('candidate_basicinfo.id');
        }
        
        $personwise_query = $personwise_query->where('bills.cancel_bill','=','0');
        $personwise_query = $personwise_query->where('bills.date_of_joining','>=',$current_year);
        $personwise_query = $personwise_query->where('bills.date_of_joining','<=',$next_year);
        $personwise_query = $personwise_query->orderBy('bills.date_of_joining','ASC');
        $personwise_res = $personwise_query->get();

        $person_data = array();
        $j = 0;

        if (isset($personwise_res) && sizeof($personwise_res)>0) {

            $total_salary_offered = 0;
            $total_billing = 0;
            $total_monthwise_billing = 0;
            $total_monthwise_gst = 0;
            $total_monthwise_invoice_raised = 0;
            $total_monthwise_payment = 0;

            foreach ($personwise_res as $key => $value) {

                //$salary = $value->fixed_salary;
                $salary = str_replace(",", "", $value->fixed_salary);
                $salary = (int)$salary;

                $pc = $value->percentage_charged;
                $pc = (float)$pc;

                $fees = round(($salary * $pc)/100);
                $gst = round(($fees * 18)/100);
                $billing_amount = round($fees + $gst);
                $payment = round((($fees * 90)/100)+ (($fees * 18)/100));

                $person_data[$j]['candidate_name'] = $value->candidate_name;
                $person_data[$j]['company_name'] = $value->company_name;
                $person_data[$j]['position'] = $value->designation_offered;
                $person_data[$j]['salary_offered'] = Utils::IND_money_format(round($salary));
                $person_data[$j]['billing'] = Utils::IND_money_format(round($fees));
                $person_data[$j]['joining_date'] = date('d-m-Y', strtotime($value->date_of_joining));

                // get employee efforts
                $efforts = Bills::getEmployeeEffortsNameById($value->id);
                $efforts_str = '';
                $person_billing = 0;
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
                    $person_data[$j]['person_billing'] = Utils::IND_money_format(round($person_billing));

                    // Set for Eligibility Report
                    $person_data[$j]['person_billing_new'] = round($person_billing);
                }
                else {
                    $person_data[$j]['person_billing'] = 0;

                    // Set for Eligibility Report
                    $person_data[$j]['person_billing_new'] = 0;
                }

                $person_data[$j]['efforts'] = $efforts_str;
                $person_data[$j]['client_name'] = $value->coordinator_prefix. " " .$value->client_name;
                $person_data[$j]['location'] = $value->job_location;
                $person_data[$j]['gst'] = Utils::IND_money_format(round($gst));
                $person_data[$j]['invoice_raised'] = Utils::IND_money_format(round($billing_amount));
                $person_data[$j]['payment'] = Utils::IND_money_format(round($payment));

                $total_salary_offered = $total_salary_offered + $salary;
                $total_billing = $total_billing + $person_billing;

                $person_data[$j]['total_salary_offered'] = Utils::IND_money_format(round($total_salary_offered));
                $person_data[$j]['total_billing'] = Utils::IND_money_format(round($total_billing));

                // Specially for Montwise Report

                // 1. For Billing
                $billing = str_replace(",", "", $person_data[$j]['billing']);
                $billing = (int)$billing;

                $total_monthwise_billing = $total_monthwise_billing + $billing;
                $person_data[$j]['total_monthwise_billing'] = Utils::IND_money_format(round($total_monthwise_billing));

                // 2. For GST

                $gst = str_replace(",", "", $person_data[$j]['gst']);
                $gst = (int)$gst;

                $total_monthwise_gst = $total_monthwise_gst + $gst;
                $person_data[$j]['total_monthwise_gst'] = Utils::IND_money_format(round($total_monthwise_gst));

                // 3. For Invoice Raised

                $invoice_raised = str_replace(",", "", $person_data[$j]['invoice_raised']);
                $invoice_raised = (int)$invoice_raised;

                $total_monthwise_invoice_raised = $total_monthwise_invoice_raised + $invoice_raised;
                $person_data[$j]['total_monthwise_invoice_raised'] = Utils::IND_money_format(round($total_monthwise_invoice_raised));

                // 4. For Payment

                $payment = str_replace(",", "", $person_data[$j]['payment']);
                $payment = (int)$payment;

                $total_monthwise_payment = $total_monthwise_payment + $payment;
                $person_data[$j]['total_monthwise_payment'] = Utils::IND_money_format(round($total_monthwise_payment));

                $person_data[$j]['status'] = $value->status;

                $j++;
            }
        }
        return $person_data;
    }

    public static function getConfirmationWiseRecovery($all=0,$user_id,$limit=0,$offset=0,$search=0,$order=0,$type='asc',$current_year=NULL,$next_year=NULL,$confirmation,$cancel_bill) {

        $bills_query = Bills::query();
        $bills_query = $bills_query->join('job_openings','job_openings.id','=','bills.job_id');
        $bills_query = $bills_query->leftjoin('bills_efforts','bills_efforts.bill_id','=','bills.id');
        $bills_query = $bills_query->leftjoin('client_basicinfo','client_basicinfo.id','=','job_openings.client_id');
        $bills_query = $bills_query->join('candidate_basicinfo','candidate_basicinfo.id','=','bills.candidate_id');
        $bills_query = $bills_query->join('users','users.id','bills.uploaded_by');
        $bills_query = $bills_query->select('bills.*','users.name as name','job_openings.posting_title','client_basicinfo.display_name','job_openings.city','candidate_basicinfo.full_name','candidate_basicinfo.lname','client_basicinfo.id as client_id','job_openings.remote_working as remote_working','client_basicinfo.account_manager_id');

        if($all==0) {
            $bills_query = $bills_query->where(function($bills_query) use ($user_id) {

                $bills_query = $bills_query->where('bills_efforts.employee_name',$user_id);
                $bills_query = $bills_query->orwhere('client_basicinfo.account_manager_id',$user_id);
            });
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

        if (isset($search) && $search != '') {

            $bills_query = $bills_query->where(function($bills_query) use ($search) {

                $date_search = false;
                $date_array = explode("-",$search);

                if(isset($date_array) && sizeof($date_array) > 0) {

                    $stamp = strtotime($search);
                    if (is_numeric($stamp)) {

                        $month = date( 'm', $stamp );
                        $day   = date( 'd', $stamp );
                        $year  = date( 'Y', $stamp );

                        if(checkdate($month, $day, $year)) {
                            $date_search = true;
                        }
                    }
                }

                $bills_query = $bills_query->where('users.name','like',"%$search%");
                $bills_query = $bills_query->orwhere('client_basicinfo.display_name','like',"%$search%");
                $bills_query = $bills_query->orwhere('candidate_basicinfo.full_name','like',"%$search%");
                $bills_query = $bills_query->orwhere('bills.fixed_salary','like',"%$search%");
                $bills_query = $bills_query->orwhere('candidate_basicinfo.mobile','like',"%$search%");
                $bills_query = $bills_query->orwhere('bills.client_name','like',"%$search%");
                $bills_query = $bills_query->orwhere('job_openings.posting_title','like',"%$search%");
                $bills_query = $bills_query->orwhere('job_openings.city','like',"%$search%");
                
                if(($search == 'Remote') || ($search == 'remote')) {

                    $bills_query = $bills_query->orwhere('job_openings.remote_working','=',"1");
                }

                if($date_search) {

                    $dateClass = new Date();
                    $search_string = $dateClass->changeDMYtoYMD($search);
                    $from_date = date("Y-m-d 00:00:00",strtotime($search_string));
                    $to_date = date("Y-m-d 23:59:59",strtotime($search_string));
                    $bills_query = $bills_query->orwhere('bills.date_of_joining','>=',"$from_date");
                    $bills_query = $bills_query->Where('bills.date_of_joining','<=',"$to_date");
                }
            });
        }

        // Get data by financial year
        if (isset($current_year) && $current_year != NULL) {
            $bills_query = $bills_query->where('bills.date_of_joining','>=',$current_year);
        }
        if (isset($next_year) && $next_year != NULL) {
            $bills_query = $bills_query->where('bills.date_of_joining','<=',$next_year);
        }

        $bills_query = $bills_query->where('bills.status','=','1');
        $bills_query = $bills_query->where('bills.joining_confirmation_mail','=',$confirmation);
        $bills_query = $bills_query->where('cancel_bill','=',$cancel_bill);

        $bills_query = $bills_query->groupBy('bills.id');
        $bills_res = $bills_query->get();

        $bills = array();
        $i = 0 ;
        $date_class = new Date();

        foreach ($bills_res as $key => $value) {

            $bills[$i]['id'] = $value->id;
            $bills[$i]['company_name'] = $value->company_name;
            $bills[$i]['candidate_name'] = $value->candidate_name;
            $bills[$i]['candidate_contact_number'] = $value->candidate_contact_number;
            $bills[$i]['designation_offered'] = $value->designation_offered;
            $bills[$i]['date_of_joining'] = $date_class->changeYMDtoDMY($value->date_of_joining);
            $bills[$i]['date_of_joining_ts'] = strtotime($value->date_of_joining);

            if($value->remote_working == '1') {

                $bills[$i]['job_location'] = "Remote";
            }
            else {

                $bills[$i]['job_location'] = $value->job_location;
            }

            $salary = str_replace(",", "", $value->fixed_salary);
            $salary = (int)$salary;
            $bills[$i]['fixed_salary'] = round($salary);

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

             if($value->remote_working == '1') {

                $bills[$i]['city'] = "Remote";
            }
            else {

                $bills[$i]['city'] = $value->city;
            }

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

            // Generate Excel & PDF Invoice URL

            $bill_invoice = BillsDoc::getBillInvoice($value->id,'Invoice');

            if(isset($bill_invoice) && $bill_invoice != '') {

                $excel_url = $bill_invoice['file'];
                $pdf_url = str_replace(".xls", ".pdf", $bill_invoice['file']);
            }
            else {
                $excel_url = '';
                $pdf_url = '';
            }

            if (!file_exists($excel_url) && !is_dir($excel_url)) {
                $bills[$i]['excel_invoice_url'] = NULL;
            }
            else{
                $bills[$i]['excel_invoice_url'] = $excel_url;
            }

            if (!file_exists($pdf_url) && !is_dir($pdf_url)) {
                $bills[$i]['pdf_invoice_url'] = NULL;
            }
            else{
                $bills[$i]['pdf_invoice_url'] = $pdf_url;
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
            $bills[$i]['account_manager_id'] = $value->account_manager_id;

            $i++;
        }
        return $bills;
    }

    public static function getConfirmationWiseRecoveryCount($all=0,$user_id,$search=0,$current_year=NULL,$next_year=NULL,$confirmation,$cancel_bill) {
        
        $bills_query = Bills::query();
        $bills_query = $bills_query->join('job_openings','job_openings.id','=','bills.job_id');
        $bills_query = $bills_query->leftjoin('bills_efforts','bills_efforts.bill_id','=','bills.id');
        $bills_query = $bills_query->leftjoin('client_basicinfo','client_basicinfo.id','=','job_openings.client_id');
        $bills_query = $bills_query->join('candidate_basicinfo','candidate_basicinfo.id','=','bills.candidate_id');
        $bills_query = $bills_query->join('users','users.id','bills.uploaded_by');
        $bills_query = $bills_query->select('bills.*','users.name as name','job_openings.posting_title','client_basicinfo.display_name','job_openings.city','candidate_basicinfo.full_name','candidate_basicinfo.lname','client_basicinfo.id as client_id','job_openings.remote_working as remote_working','client_basicinfo.account_manager_id');

        if($all == 0) {
            $bills_query = $bills_query->where(function($bills_query) use ($user_id) {

                $bills_query = $bills_query->where('bills_efforts.employee_name',$user_id);
                $bills_query = $bills_query->orwhere('client_basicinfo.account_manager_id',$user_id);
            });
        }

        if (isset($search) && $search != '') {

            $bills_query = $bills_query->where(function($bills_query) use ($search) {

                $date_search = false;
                $date_array = explode("-",$search);

                if(isset($date_array) && sizeof($date_array) > 0) {

                    $stamp = strtotime($search);
                    if (is_numeric($stamp)) {

                        $month = date( 'm', $stamp );
                        $day   = date( 'd', $stamp );
                        $year  = date( 'Y', $stamp );

                        if(checkdate($month, $day, $year)) {
                            $date_search = true;
                        }
                    }
                }

                $bills_query = $bills_query->where('users.name','like',"%$search%");
                $bills_query = $bills_query->orwhere('client_basicinfo.display_name','like',"%$search%");
                $bills_query = $bills_query->orwhere('candidate_basicinfo.full_name','like',"%$search%");
                $bills_query = $bills_query->orwhere('bills.fixed_salary','like',"%$search%");
                $bills_query = $bills_query->orwhere('candidate_basicinfo.mobile','like',"%$search%");
                $bills_query = $bills_query->orwhere('bills.client_name','like',"%$search%");
                $bills_query = $bills_query->orwhere('job_openings.posting_title','like',"%$search%");
                $bills_query = $bills_query->orwhere('job_openings.city','like',"%$search%");
                
                if(($search == 'Remote') || ($search == 'remote')) {

                    $bills_query = $bills_query->orwhere('job_openings.remote_working','=',"1");
                }

                if($date_search) {
                    
                    $dateClass = new Date();
                    $search_string = $dateClass->changeDMYtoYMD($search);
                    $from_date = date("Y-m-d 00:00:00",strtotime($search_string));
                    $to_date = date("Y-m-d 23:59:59",strtotime($search_string));
                    $bills_query = $bills_query->orwhere('bills.date_of_joining','>=',"$from_date");
                    $bills_query = $bills_query->Where('bills.date_of_joining','<=',"$to_date");
                }
            });
        }

        // Get data by financial year
        if (isset($current_year) && $current_year != NULL) {
            $bills_query = $bills_query->where('bills.date_of_joining','>=',$current_year);
        }
        if (isset($next_year) && $next_year != NULL) {
            $bills_query = $bills_query->where('bills.date_of_joining','<=',$next_year);
        }

        $bills_query = $bills_query->where('bills.status','=','1');
        $bills_query = $bills_query->where('bills.joining_confirmation_mail','=',$confirmation);
        $bills_query = $bills_query->where('cancel_bill','=',$cancel_bill);

        $bills_query = $bills_query->groupBy('bills.id');
        $bills_query = $bills_query->get();
        $bills_count = $bills_query->count();

        return $bills_count;
    }

    public static function getProductivityReportOfferAcceptanceCandidate($user_id=0,$from_date=NULL,$to_date=NULL) {

        $query = Bills::query();
        $query = $query->leftjoin('candidate_otherinfo','candidate_otherinfo.candidate_id','=','bills.candidate_id');
        $query = $query->leftjoin('bills_date','bills_date.bills_id','=','bills.id');
        $query = $query->select('candidate_otherinfo.candidate_id as candidate_id');

        if(isset($user_id) && $user_id > 0) {
            $query = $query->where('candidate_otherinfo.owner_id','=',$user_id);
        }

        $query = $query->where('bills_date.forecasting_date','>=',$from_date);

        $to_date = date("Y-m-d 23:59:59",strtotime($to_date));
        $query = $query->where('bills_date.forecasting_date','<=',$to_date);
        $response = $query->get();

        $candidate_names = '';

        iF(isset($response) && $response != '') {

            foreach ($response as $key => $value) {

                if($candidate_names == '') {

                    $candidate_names = CandidateBasicInfo::getCandidateNameById($value->candidate_id);
                }
                else {

                    $candidate_names = $candidate_names . "," . CandidateBasicInfo::getCandidateNameById($value->candidate_id);
                }
            }
        }
        return $candidate_names;  
    }

    public static function getProductivityReportJoiningCandidate($user_id=0,$from_date=NULL,$to_date=NULL) {

        $query = Bills::query();
        $query = $query->leftjoin('candidate_otherinfo','candidate_otherinfo.candidate_id','=','bills.candidate_id');
        $query = $query->leftjoin('bills_date','bills_date.bills_id','=','bills.id');
        $query = $query->select('candidate_otherinfo.candidate_id as candidate_id');

        if(isset($user_id) && $user_id > 0) {
            $query = $query->where('candidate_otherinfo.owner_id','=',$user_id);
        }

        $query = $query->where('bills_date.recovery_date','>=',$from_date);

        $to_date = date("Y-m-d 23:59:59",strtotime($to_date));
        $query = $query->where('bills_date.recovery_date','<=',$to_date);
        $response = $query->get();

        $candidate_names = '';

        iF(isset($response) && $response != '') {

            foreach ($response as $key => $value) {

                if($candidate_names == '') {

                    $candidate_names = CandidateBasicInfo::getCandidateNameById($value->candidate_id);
                }
                else {

                    $candidate_names = $candidate_names . "," . CandidateBasicInfo::getCandidateNameById($value->candidate_id);
                }
            }
        }
        return $candidate_names;  
    }

    public static function getProductivityReportJoiningSuccessCandidate($user_id=0,$from_date=NULL,$to_date=NULL) {

        $query = Bills::query();
        $query = $query->leftjoin('candidate_otherinfo','candidate_otherinfo.candidate_id','=','bills.candidate_id');
        $query = $query->leftjoin('bills_date','bills_date.bills_id','=','bills.id');
        $query = $query->select('candidate_otherinfo.candidate_id as candidate_id');

        if(isset($user_id) && $user_id > 0) {
            $query = $query->where('candidate_otherinfo.owner_id','=',$user_id);
        }

        $query = $query->where('bills_date.joining_success_date','>=',$from_date);
        $to_date = date("Y-m-d 23:59:59",strtotime($to_date));
        $query = $query->where('bills_date.joining_success_date','<=',$to_date);
        $response = $query->get();

        $success_candidate_names = '';

        if(isset($response) && $response != '') {

            foreach ($response as $key => $value) {

                if($success_candidate_names == '') {

                    $success_candidate_names = CandidateBasicInfo::getCandidateNameById($value->candidate_id);
                }
                else {
                    $success_candidate_names = $success_candidate_names . "," . CandidateBasicInfo::getCandidateNameById($value->candidate_id);
                }
            }
        }
        return $success_candidate_names;
    }
}