<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Lead;
use App\Industry;
use App\user;
use App\ClientBasicinfo;
use App\ClientAddress;
use Illuminate\Support\Facades\Input;
use Mockery\CountValidator\Exception;
use App\Events\NotificationEvent;

class LeadController extends Controller
{

    public function index(){

        $user = \Auth::user();
        $user_role_id = User::getLoggedinUserRole($user);

        $superadmin_role_id = env('SUPERADMIN');
        $strategy_role_id =  env('STRATEGY');

        $access_roles_id = array($superadmin_role_id,$strategy_role_id);
        if(in_array($user_role_id,$access_roles_id)){
            $leads = Lead::getAllLeads(1,$user->id);
            $convert_client = Lead::getConvertedClient(1,$user->id);
        }
        else{
            $leads = Lead::getAllLeads(0,$user->id);
            $convert_client = Lead::getConvertedClient(0,$user->id);
        }
       // print_r($leads);exit;

        $convert_client_count = sizeof($convert_client);
        //print_r($convert_client_count);exit;

        $lead_count = 0;

        $count = sizeof($leads);
        //$lead = Lead::orderBy('id','DESC')->paginate(50);
        return view('adminlte::lead.index',compact('leads','lead_count','count','convert_client_count'));

    }

    public function cancellead()
    {
        $user = \Auth::user();
        $user_role_id = User::getLoggedinUserRole($user);

        $superadmin_role_id = env('SUPERADMIN');

        $access_roles_id = array($superadmin_role_id);
        if(in_array($user_role_id,$access_roles_id)){
            $leads = Lead::getCancelLeads(1,$user->id);
        }
        else{
            $leads = Lead::getCancelLeads(0,$user->id);
        }
       // print_r($leads);exit;

        $lead_count = 0;

        $count = sizeof($leads);
        //$lead = Lead::orderBy('id','DESC')->paginate(50);
        return view('adminlte::lead.cancel',compact('leads','lead_count','count'));        
    }

    public function cancel($id){
        
        $cancel_lead =1;
        $lead = array();
        $lead = Lead::find($id);
        $lead->cancel_lead = $cancel_lead;
        $lead_cancel = $lead->save();

        //print_r($lead_cancel);exit;

        return redirect()->route('lead.index')->with('success', 'BNM Updated Successfully');

    }

    public function create(){

        $user = \Auth::user();
        $user_id = $user->id;
        $action = 'add';
        $generate_lead = '0';
        $cancel_lead = '0';
        $leadservices_status=Lead::getLeadService();
        $users=User::getAllUsers();
        $status = Lead::getLeadStatus();
        $service ='';
        $lead_status ='';
        $referredby = $user_id;

        return view('adminlte::lead.create',compact('leadservices_status','action','generate_lead','service','users', 'referredby','status','cancel_lead','lead_status'));
    }

 public function store(Request $request){

        $user = \Auth::user();
 	    $input = $request->all();

         $company_name = $input['name'];
         $coordinator_name = $input['coordinator_name'];
         $email=$input['mail'];
         $s_email=$input['s_email'];
         $mobile=$input['mobile'];
         $other_number=$input['other_number'];
         $display_name=$input['display_name'];
         $leads=$input['leads'];
         $remark=$input['remarks'];
         $city=$input['city'];
         $state=$input['state'];
         $country=$input['country'];
         $website=$input['website'];
         $source=$input['source'];
         $designation=$input['designation'];
         $referredby_id=$input['referredby_id'];
         $lead_status = $input['status'];

         $lead=new Lead();
         $lead->name=$company_name;
         $lead->coordinator_name=$coordinator_name;
         $lead->mail=$email;
         $lead->s_email=$s_email;
         $lead->mobile=$mobile;
         $lead->other_number=$other_number;
         $lead->display_name=$display_name;
         $lead->service=$leads;
         $lead->remarks=$remark;
         $lead->city=$city;
         $lead->state=$state;
         $lead->country=$country;
         $lead->convert_client = 0;
         $lead->account_manager_id = $user->id;
         $lead->website=$website;
         $lead->source=$source;
         $lead->designation=$designation;
         $lead->referredby=$referredby_id;
         $lead->lead_status=$lead_status;
         $lead->save();

         $validator = \Validator::make(Input::all(),$lead::$rules);

        if($validator->fails()){
            return redirect('lead/create')->withInput(Input::all())->withErrors($validator->errors());
        }
         return redirect()->route('lead.index')->with('success','Leads created successfully');

	}
	 public function edit($id){
        

        $action = 'edit';
        $generate_lead = '0';
        $leadservices_status = Lead::getLeadService();
        $status = Lead::getLeadStatus();
        $lead = Lead::find($id);
        $cancel_lead = $lead->cancel_lead;
        $convert_client = $lead->convert_client;
        if($convert_client == 1){
            $generate_lead = 1;
        }

        $service = $lead->service;
        $referredby = $lead->referredby;
        $lead_status = $lead->lead_status;
        //print_r($lead_s); exit;
        $users=User::getAllUsersWithInactive();
        $leadsarr = array();
        $leads_info = \DB::table('lead_management')
        ->get();

        	        
	   return view('adminlte::lead.edit',compact('lead','action','users','generate_lead','leadservices_status','service','convert_client', 'referredby','status','cancel_lead','lead_status'));

	 }
	 public function update(Request $request, $id){

	     $user  = \Auth::user()->id;

        $input = $request->all();

	 	$name = $request->get('name');
        $coordinator_name = $request->get('coordinator_name');
        $email = $request->get('mail');
        $s_email = $request->get('s_email');
        $mobile = $request->get('mobile');
        $other_number = $request->get('other_number');
        $display_name = $request->get('display_name');
        $leads = $request->get('leads');
        $remarks = $request->get('remarks');
        $city=$request->get('city');
        $state=$request->get('state');
        $country=$request->get('country');
        $generatelead = $request->get('generatelead');
        $website = $request->get('website');
        $source = $request->get('source');
        $designation = $request->get('designation');
        $referredby_id= $request->get('referredby_id');
        $lead_status = $request->get('status');

        if ($lead_status == 'Cancel') {
            $cancel_lead = 1;
        }
        else{
            $cancel_lead =0;
        }

         
        $lead_basic = Lead::find($id);


        if(isset($name))
            $lead_basic->name = $name;
        if(isset($coordinator_name))
            $lead_basic->coordinator_name = $coordinator_name;
        if(isset($email))
            $lead_basic->mail = $email;
        if(isset($s_email))
            $lead_basic->s_email =$s_email;
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
            $lead_basic->state=$state;
        if(isset($country))
            $lead_basic->country=$country;
        if(isset($website))
            $lead_basic->website=$website;
        if(isset($source))
            $lead_basic->source=$source;
        if(isset($designation))
            $lead_basic->designation=$designation;
        if(isset($referredby_id))
            $lead_basic->referredby=$referredby_id;
        if(isset($lead_status))
            $lead_basic->lead_status=$lead_status;
        if(isset($cancel_lead))
            $lead_basic->cancel_lead=$cancel_lead;

         $lead_basic->account_manager_id = $user;

        $leadUpdated = $lead_basic->save();

        $validator = \Validator::make(Input::all(),$lead_basic::$rules);

        if($validator->fails()){
            //print_r($validator->errors());exit;
            return redirect('lead/'.$lead_basic->id.'/edit')->withInput(Input::all())->withErrors($validator->errors());
        }

        return redirect()->route('lead.index')->with('success','ToDo Updated Successfully');

	 }

     public function clone($id){

        $generate_lead = '0';
        $industry_res = Industry::orderBy('id','DESC')->get();
        $industry = array();

        $user = \Auth::user();
        $userRole = $user->roles->pluck('id','id')->toArray();
        $role_id = key($userRole);

        $user_obj = new User();
        $isAdmin = $user_obj::isAdmin($role_id);
        $isSuperAdmin = $user_obj::isSuperAdmin($role_id);
        $user_id = $user->id;

        // For account manager
         $users = User::getAllUsers();

        if(sizeof($industry_res)>0){
            foreach($industry_res as $r){
                $industry[$r->id]=$r->name;
            }
        }

        $lead = Lead::find($id);
        $name = $lead->name;
        $website = $lead->website;
        $billing_city = $lead->city;
        $billing_state = $lead->state;
        $billing_country = $lead->country;
            $convert_client = 0;
            if($generate_lead==1){
                $lead->convert_client = 1;
            }
            $lead->save();
        //print_r($billing_state);exit;

        $industry_id = '';

        $action = "copy" ;
         $co_prefix=ClientBasicinfo::getcoprefix();
         $co_category='';

         return view('adminlte::client.create',compact('co_prefix','co_category','name', 'website', 'billing_city','billing_state','billing_country','lead','action','generate_lead','industry','users','isSuperAdmin','user_id','isAdmin','industry_id'));
     }

     public function clonestore(Request $request,$id){

        $user_id = \Auth::user()->id;
        $user_name = \Auth::user()->name;

        $input = $request->all();

        $generate_lead = '1';
        $lead = Lead::find($id);
            if($generate_lead==1){
                $lead->convert_client = 1;
            }
        $lead->save();

        $client_basic_info = new ClientBasicinfo();
        $client_basic_info->name = $input['name'];
        $client_basic_info->display_name = $input['display_name'];
        $client_basic_info->mail = $input['mail'];
        $client_basic_info->s_email = $input['s_email'];
        $client_basic_info->description = $input['description'];
        $client_basic_info->mobile = $input['mobile'];
        $client_basic_info->other_number = $input['other_number'];
        $client_basic_info->website = $input['website'];
        //$client_basic_info->fax = $input['fax'];
        $client_basic_info->account_manager_id = $input['account_manager'];
        $client_basic_info->industry_id = $input['industry_id'];
        //$client_basic_info->source = $input['source'];
        $generatelead = $input['generatelead'];
        $convert_client = 0;
        if($generatelead==1){
            $client_basic_info->convert_client = 1;
            $client_basic_info->lead_id = $id;
        }
        
        $client_basic_info->about = $input['description'];
        if(isset($input['source']) && $input['source']!='')
            $client_basic_info->source = $input['source'];
        else
            $client_basic_info->source = '';
        if(isset($input['gst_no']) && $input['gst_no']!='')
            $client_basic_info->gst_no = $input['gst_no'];
        else
            $client_basic_info->gst_no = '';
        if(isset($input['tds']) && $input['tds']!='')
            $client_basic_info->tds = $input['tds'];
        else
            $client_basic_info->tds = '';
        if(isset($input['tan']) && $input['tan']!='')
            $client_basic_info->tan = $input['tan'];
        else
            $client_basic_info->tan = '';
        $client_basic_info->coordinator_name = $input['coordinator_name'];
        $client_basic_info->created_at = time();
        $client_basic_info->updated_at = time();

        if($client_basic_info->save()){

            $client_id = $client_basic_info->id;

            $client_address = new ClientAddress();
            $client_address->client_id = $client_id;

            if(isset($input['billing_country']) && $input['billing_country']!=''){
                $client_address->billing_country = $input['billing_country'];
            }
            if(isset($input['billing_state']) && $input['billing_state']!=''){
                $client_address->billing_state = $input['billing_state'];
            }
            if(isset($input['billing_street1']) && $input['billing_street1']!=''){
                $client_address->billing_street1 = $input['billing_street1'];
            }
            if(isset($input['billing_street2']) && $input['billing_street2']!=''){
                $client_address->billing_street2 = $input['billing_street2'];
            }
            if(isset($input['billing_code']) && $input['billing_code']!=''){
                $client_address->billing_code = $input['billing_code'];
            }
            if(isset($input['billing_city']) && $input['billing_city']!=''){
                $client_address->billing_city = $input['billing_city'];
            }

            if(isset($input['shipping_country']) && $input['shipping_country']!=''){
                $client_address->shipping_country = $input['shipping_country'];
            }
            if(isset($input['shipping_state']) && $input['shipping_state']!=''){
                $client_address->shipping_state = $input['shipping_state'];
            }
            if(isset($input['shipping_street1']) && $input['shipping_street1']!=''){
                $client_address->shipping_street1 = $input['shipping_street1'];
            }
            if(isset($input['shipping_street2']) && $input['shipping_street2']!=''){
                $client_address->shipping_street2 = $input['shipping_street2'];
            }
            if(isset($input['shipping_code']) && $input['shipping_code']!=''){
                $client_address->shipping_code = $input['shipping_code'];
            }
            if(isset($input['shipping_city']) && $input['shipping_city']!=''){
                $client_address->shipping_city = $input['shipping_city'];
            }
            $client_address->updated_at = date("Y-m-d H:i:s");
            $client_address->save();

            // save client address
           // $input['client_id'] = $client_id;
           // ClientAddress::create($input);

            // save client documents
            $client_contract = $request->file('client_contract');
            $client_logo = $request->file('client_logo');
            $others_doc = $request->file('others_doc');

            if (isset($client_contract) && $client_contract->isValid()) {
                $client_contract_name = $client_contract->getClientOriginalName();
                $filesize = filesize($client_contract);

                $dir_name = "uploads/clients/".$client_id."/";
                $client_contract_key = "uploads/clients/".$client_id."/".$client_contract_name;

                if (!file_exists($dir_name)) {
                    mkdir("uploads/clients/$client_id", 0777,true);
                }

                if(!$client_contract->move($dir_name, $client_contract_name)){
                    return false;
                }
                else{
                    $client_doc = new ClientDoc;

                    $client_doc->client_id = $client_id;
                    $client_doc->category = 'Client Contract';
                    $client_doc->name = $client_contract_name;
                    $client_doc->file = $client_contract_key;
                    $client_doc->uploaded_by = $user_id;
                    $client_doc->size = $filesize;
                    $client_doc->created_at = time();
                    $client_doc->updated_at = time();
                    $client_doc->save();
                }

            }

            if (isset($client_logo) && $client_logo->isValid()) {
                $client_logo_name = $client_logo->getClientOriginalName();
                $client_logo_filesize = filesize($client_logo);

                $dir_name = "uploads/clients/".$client_id."/";
                $client_logo_key = "uploads/clients/".$client_id."/".$client_logo_name;
                if (!file_exists($dir_name)) {
                    mkdir("uploads/clients/$client_id", 0777,true);
                }

                if(!$client_logo->move($dir_name, $client_logo_key)){
                    return false;
                }
                else{
                    $client_doc = new ClientDoc;

                    $client_doc->client_id = $client_id;
                    $client_doc->category = 'Client Logo';
                    $client_doc->name = $client_logo_name;
                    $client_doc->file = $client_logo_key;
                    $client_doc->uploaded_by = $user_id;
                    $client_doc->size = $client_logo_filesize;
                    $client_doc->created_at = time();
                    $client_doc->updated_at = time();
                    $client_doc->save();
                }

            }

            if (isset($others_doc) && $others_doc->isValid()) {
                $others_doc_name = $others_doc->getClientOriginalName();
                $others_filesize = filesize($others_doc);

                $dir_name = "uploads/clients/".$client_id."/";
                $others_doc_key = "uploads/clients/".$client_id."/".$others_doc_name;

                if (!file_exists($dir_name)) {
                    mkdir("uploads/clients/$client_id", 0777,true);
                }

                if(!$others_doc->move($dir_name, $others_doc_name)){
                    return false;
                }
                else{
                    $client_doc = new ClientDoc;

                    $client_doc->client_id = $client_id;
                    $client_doc->category = 'Others';
                    $client_doc->name = $others_doc_name;
                    $client_doc->file = $others_doc_key;
                    $client_doc->uploaded_by = $user_id;
                    $client_doc->size = $others_filesize;
                    $client_doc->created_at = time();
                    $client_doc->updated_at = time();
                    $client_doc->save();
                }
            }

            /*$lead = new lead();
            $lead->name = $input['name'];
            $lead->mobile = $input['mobile'];
            $lead->city = $input['citya'];
            $generatelead = $input['generatelead'];
            $convert_client = 0;
            if($generatelead==1){
                $lead->convert_client = 1;
            }
            $lead->save();*/

            // TODO:: Notifications : On adding new client notify Super Admin via notification
            $module_id = $client_id;
            $module = 'Client';
            $message = $user_name . " added new Client";
            $link = route('client.show',$client_id);

            $super_admin_userid = getenv('SUPERADMINUSERID');
            $user_arr = array();
            $user_arr[] = $super_admin_userid;

            event(new NotificationEvent($module_id, $module, $message, $link, $user_arr));
            return redirect()->route('client.index')->with('success','Client Created Successfully');
        }
        else{
            return redirect('client/create')->withInput(Input::all())->withErrors($client_basic_info->errors());
        }
     }

	public function destroy($id){
        $lead = Lead::where('id',$id)->delete();

        return redirect()->route('lead.index')->with('success','Leads Deleted Successfully');
    }

}
