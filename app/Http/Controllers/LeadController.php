<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Lead;

class LeadController extends Controller
{
   

  public function index(){

  	$lead = Lead::orderBy('id','DESC')->paginate(5);
         return view('adminlte::lead.index',compact('lead'));

    }

    
  public function create(){

  			 $action = 'add';
             $generate_lead = '0';
  			 $leadservices_status=Lead::getLeadService();


       
          return view('adminlte::lead.create',compact('leadservices_status','action','generate_lead'));
    }
 public function store(Request $request){

 	    $input = $request->all();

         $company_name = $input['company_name'];
         $hr_name = $input['hr_name'];
         $email=$input['mail'];
         $secondary_email=$input['secondary_email'];
         $mobile=$input['mobile'];
         $other_number=$input['other_number'];
         $display_name=$input['display_name'];
         $leads=$input['leads'];
         $remark=$input['remarks'];
         $city=$input['city'];
         $state=$input['state'];
         $country=$input['country'];

         $lead=new Lead();
         $lead->company_name=$company_name;
         $lead->hr_name=$hr_name;
         $lead->mail=$email;
         $lead->secondary_email=$secondary_email;
         $lead->mobile=$mobile;
         $lead->other_number=$other_number;
         $lead->display_name=$display_name;
         $lead->service=$leads;
         $lead->status="";
         $lead->remarks=$remark;
         $lead->city=$city;
         $lead->state=$state;
         $lead->country=$country;
         $lead->save();

         //$validator = \Validator::make(Input::all(),$lead::$rules);s
         return redirect()->route('lead.index')
            ->with('success','Leads created successfully');


	}
	 public function edit($id){
        

         $action = 'edit';
         $generate_lead = '0';
         $leadservices_status = Lead::getLeadService();
         $lead = Lead::find($id);
        
        //print_r($lead_s); exit;
        $leads_info = \DB::table('lead_management')
        
            
            ->get();
	        
	             return view('adminlte::lead.edit',compact('lead','action','generate_lead','leadservices_status','leads'));

	 }
	 public function update(Request $request, $id){

        
        $input = $request->all();

	 	$company_name = $request->get('company_name');
        $hr_name = $request->get('hr_name');
        $email = $request->get('mail');
        $secondary_email = $request->get('secondary_email');
        $mobile = $request->get('mobile');
        $other_number = $request->get('other_number');
        $display_name = $request->get('display_name');
        $leads = $request->get('service');
        $remarks = $request->get('remarks');
        $city=$request->get('city');
        $state=$request->get('state');
        $country=$request->get('country');


         
         $lead_basic = Lead::find($id);


        if(isset($company_name))
            $lead_basic->company_name = $company_name;
        if(isset($hr_name))
            $lead_basic->hr_name = $hr_name;
        if(isset($email))
            $lead_basic->mail = $email;
        if(isset($secondary_email))
            $lead_basic->secondary_email =$secondary_email;
        if(isset($mobile))
            $lead_basic->mobile = $mobile;
        if(isset($other_number))
            $lead_basic->other_number = $other_number;
        if(isset($display_name))
            $lead_basic->display_name = $display_name;
        if(isset($leads))
            $lead_basic->service = $leads;
        if(isset($remarks))
            $lead_basic->remarks = $remarks;
        if(isset($city))
            $lead_basic->city =$city;
        if(isset($state))
            $lead_basic->city=$state;
        if(isset($country))
            $lead_basic->country=$country;

        $leadUpdated = $lead_basic->save();

        return redirect()->route('lead.index')->with('success','ToDo Updated Successfully');

	 }

	 public function destroy($id){
        $lead = Lead::where('id',$id)->delete();

        return redirect()->route('lead.index')->with('success','Leads Deleted Successfully');
    }

}
