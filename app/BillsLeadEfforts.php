<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BillsLeadEfforts extends Model
{
    public $timestamps = false;
    public $table = "bills_lead_effort";

    public static function getLeadEmployeeEffortsById($id){

        $lead_efforts_query = BillsLeadEfforts::query();
        $lead_efforts_query = $lead_efforts_query->join('users','users.id','=','bills_lead_effort.employee_name');
        $lead_efforts_query = $lead_efforts_query->where('bill_id',$id);
        $res = $lead_efforts_query->get();

        $lead_employee = array();
        $i = 0 ;
        foreach ($res as $key=>$value){
            $lead_employee[$value->id] = $value->employee_percentage;
            $i++;
        }

        return $lead_employee;
    }

    public static function getLeadEmployeeEffortsNameById($id){

        $lead_emp = BillsLeadEfforts::query();
        $lead_emp = $lead_emp->join('users','users.id','=','bills_lead_effort.employee_name');
        $lead_emp = $lead_emp->where('bill_id',$id);
        $res = $lead_emp->get();

        $employees = array();
        $i = 0 ;
        foreach ($res as $key=>$value){
            $employees[$value->name] = $value->employee_percentage;
            $i++;
        }

        return $employees;
    }
}
