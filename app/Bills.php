<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

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

        $bills_query = Bills::query();
        $bills_query = $bills_query->join('job_openings','job_openings.id','=','bills.job_id');
        $bills_query = $bills_query->join('client_basicinfo','client_basicinfo.id','=','job_openings.client_id');
        $bills_query = $bills_query->join('candidate_basicinfo','candidate_basicinfo.id','=','bills.candidate_id');
        $bills_query = $bills_query->join('users','users.id','bills.uploaded_by');
        $bills_query = $bills_query->select('bills.*','users.name as name','job_openings.posting_title','client_basicinfo.display_name','job_openings.city','candidate_basicinfo.fname'
        ,'candidate_basicinfo.lname');

        if($all==0){
            $bills_query = $bills_query->where('uploaded_by',$user_id);
        }

        $bills_query = $bills_query->where('status',$status);

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
            $bills[$i]['cname'] = $value->fname." ".$value->lname;

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
            $i++;
        }

        return $bills;
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
