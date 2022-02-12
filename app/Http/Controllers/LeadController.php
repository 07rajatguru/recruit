<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Lead;
use App\Industry;
use App\user;
use App\ClientBasicinfo;
use App\ClientAddress;
use App\ClientDoc;
use Illuminate\Support\Facades\Input;
use Mockery\CountValidator\Exception;
use App\Events\NotificationEvent;
use App\Events\NotificationMail;
use App\ClientTimeline;

class LeadController extends Controller
{
    public function index() {

        $user = \Auth::user();
        $all_perm = $user->can('display-lead');
        $userwise_perm = $user->can('display-user-wise-lead');

        if($all_perm) {
        
            $all_leads = Lead::getAllLeads(1,$user->id);
            $count = sizeof($all_leads);

            $convert_client_count = Lead::getConvertedClient(1,$user->id,'');
        }
        else if($userwise_perm) {

            $all_leads = Lead::getAllLeads(0,$user->id);
            $count = sizeof($all_leads);

            $convert_client_count = Lead::getConvertedClient(0,$user->id,'');
        }

        $recruitment = 0;
        $contract_staffing = 0;
        $payroll = 0;
        $hr_advisory = 0;

        foreach($all_leads as $lead) {

            if($lead['service'] == 'Recruitment') {
                $recruitment++;
            }
            else if ($lead['service'] == 'Contract Staffing') {
                $contract_staffing++;
            }
            else if($lead['service'] == 'Payroll') {
                $payroll++;
            }
            else if($lead['service'] == 'HR Advisory') {
                $hr_advisory++;
            }
        }

        return view('adminlte::lead.index',compact('count','convert_client_count','recruitment','contract_staffing','payroll','hr_advisory'));
    }

    public static function getLeadOrderColumnName($order) {

        $order_column_name = '';

        if (isset($order) && $order >= 0) {
            if ($order == 0) {
                $order_column_name = "lead_management.id";
            }
            else if ($order == 3) {
                $order_column_name = "lead_management.name";
            }
            else if ($order == 4) {
                $order_column_name = "lead_management.coordinator_name";
            }
            else if ($order == 5) {
                $order_column_name = "lead_management.mail";
            }
            else if ($order == 6) {
                $order_column_name = "lead_management.mobile";
            }
            else if ($order == 7) {
                $order_column_name = "lead_management.city";
            }
            else if ($order == 8) {
                $order_column_name = "users.name";
            }
            else if ($order == 9) {
                $order_column_name = "lead_management.website";
            }
            else if ($order == 10) {
                $order_column_name = "lead_management.lead_status";
            }
        }
        return $order_column_name;
    }

    public function getAllLeadsDetails() {

        $draw = $_GET['draw'];
        $limit = $_GET['length'];
        $offset = $_GET['start'];
        $search = $_GET['search']['value'];
        $order = $_GET['order'][0]['column'];
        $type = $_GET['order'][0]['dir'];

        $order_column_name = self::getLeadOrderColumnName($order);
        $user = \Auth::user();

        $all_perm = $user->can('display-lead');
        $userwise_perm = $user->can('display-user-wise-lead');
        $cancel_perm = $user->can('cancel-lead');
        $lead_to_client_perm = $user->can('lead-to-client');
        $delete_perm = $user->can('lead-delete');

        if($all_perm) {

            $count = Lead::getAllLeadsCount(1,$user->id,$search,'');
            $leads_res = Lead::getAllLeads(1,$user->id,$limit,$offset,$search,$order_column_name,$type,'');
        }
        else if($userwise_perm) {

            $count = Lead::getAllLeadsCount(0,$user->id,$search,'');
            $leads_res = Lead::getAllLeads(0,$user->id,$limit,$offset,$search,$order_column_name,$type,'');
        }

        $lead = array();
        $i = 0;$j = 0;

        foreach ($leads_res as $key => $value) {

            $action = '';

            $action .= '<a class="fa fa-circle" title="Show" href="'.route('lead.show',$value['id']).'" style="margin:2px;"></a>';

            if($all_perm || $value['access']) {
                $action .= '<a class="fa fa-edit" title="Edit" href="'.route('lead.edit',$value['id']).'" style="margin:2px;"></a>';
            }
            if ($delete_perm) {
                $delete_view = \View::make('adminlte::partials.deleteModalNew', ['data' => $value, 'name' => 'lead','display_name'=>'Lead','Lead_Type' => 'Index']);
                $delete = $delete_view->render();
                $action .= $delete;
            }
            if ($cancel_perm) {
                $cancel_view = \View::make('adminlte::partials.cancelbill', ['data' => $value, 'name' => 'lead','display_name'=>'Lead']);
                $cancel = $cancel_view->render();
                $action .= $cancel;
            }
            if ($value['convert_client'] == 0) {

                if($lead_to_client_perm) {
                    $action .= '<a title="Convert lead to client"  class="fa fa-clone" href="'.route('lead.clone',$value['id']).'" style="margin:2px;"></a>';
                }
            }

            $checkbox = '<input type=checkbox name=lead value='.$value['id'].' class=other_leads id='.$value['id'].'/>';

            $company_name = '<a style="white-space: pre-wrap; word-wrap: break-word; color:black; text-decoration:none;">'.$value['name'].'</a>';

            $coordinator_name = '<a style="white-space: pre-wrap; word-wrap: break-word; color:black; text-decoration:none;">'.$value['coordinator_name'].'</a>';

            if($value['convert_client'] == '1') {

                $checkbox = '';

                $data = array(++$j,$checkbox,$action,$company_name,$coordinator_name,$value['mail'],$value['mobile'],$value['city'],$value['referredby'],$value['website'],$value['lead_status'],$value['convert_client']);
            }
            else {
                $data = array(++$j,$checkbox,$action,$company_name,$coordinator_name,$value['mail'],$value['mobile'],$value['city'],$value['referredby'],$value['website'],$value['lead_status'],$value['convert_client']);
            }
            $lead[$i] = $data;
            $i++;
        }

        $json_data = array(
            'draw' => intval($draw),
            'recordsTotal' => intval($count),
            'recordsFiltered' => intval($count),
            "data" => $lead
        );

        echo json_encode($json_data);exit;
    }

    public function getAllLeadsByService($service) {

        $user =  \Auth::user();

        $all_perm = $user->can('display-lead');
        $userwise_perm = $user->can('display-user-wise-lead');

        if($all_perm) {
            $all_leads = Lead::getAllLeads(1,$user->id);
        }
        else if($userwise_perm) {
            $all_leads = Lead::getAllLeads(0,$user->id);
        }

        $count = '0';

        $recruitment = 0;
        $contract_staffing = 0;
        $payroll = 0;
        $hr_advisory = 0;

        foreach($all_leads as $lead) {

            if($lead['service'] == 'Recruitment') {
                $recruitment++;
            }
            else if ($lead['service'] == 'Contract Staffing') {
                $contract_staffing++;
            }
            else if($lead['service'] == 'Payroll') {
                $payroll++;
            }
            else if($lead['service'] == 'HR Advisory') {
                $hr_advisory++;
            }
        }

        if($service == 'recruitment') {

            $service_name = 'Recruitment';
        }
        else if($service == 'contract-staffing') {

            $service_name = 'Contract Staffing';
        }
        else if($service == 'payroll') {

            $service_name = 'Payroll';
        }
        else if($service == 'hr-advisory') {
            
            $service_name = 'Hr Advisory';
        }

        if($all_perm) {
            $convert_client_count = Lead::getConvertedClient(1,$user->id,$service_name);
        }
        else if($userwise_perm) {
            $convert_client_count = Lead::getConvertedClient(1,$user->id,$service_name);
        }

        return view('adminlte::lead.leadserviceindex',compact('service','count','recruitment','contract_staffing','payroll','hr_advisory','service_name','convert_client_count'));
    }

    public function getAllLeadsDetailsByService() {

        $draw = $_GET['draw'];
        $limit = $_GET['length'];
        $offset = $_GET['start'];
        $search = $_GET['search']['value'];
        $order = $_GET['order'][0]['column'];
        $type = $_GET['order'][0]['dir'];
        $service = $_GET['service'];

        $order_column_name = self::getLeadOrderColumnName($order);

        $user =  \Auth::user();

        $all_perm = $user->can('display-lead');
        $userwise_perm = $user->can('display-user-wise-lead');
        $cancel_perm = $user->can('cancel-lead');
        $lead_to_client_perm = $user->can('lead-to-client');
        $delete_perm = $user->can('lead-delete');

        // Get Recruitment Leads
        if($service == 'recruitment') {

            if($all_perm) {

                $leads_res = Lead::getAllLeads(1,$user->id,$limit,$offset,$search,$order_column_name,$type,'Recruitment');
                $count = Lead::getAllLeadsCount(1,$user->id,$search,'Recruitment');
            }
            else if($userwise_perm) {

                $leads_res = Lead::getAllLeads(0,$user->id,$limit,$offset,$search,$order_column_name,$type,'Recruitment');
                $count = Lead::getAllLeadsCount(0,$user->id,$search,'Recruitment');
            }
        }

        // Get Contract Staffing Leads
        if($service == 'contract-staffing') {

            if($all_perm) {

                $leads_res = Lead::getAllLeads(1,$user->id,$limit,$offset,$search,$order_column_name,$type,'Contract Staffing');
                $count = Lead::getAllLeadsCount(1,$user->id,$search,'Contract Staffing');
            }
            else if($userwise_perm) {
                
                $leads_res = Lead::getAllLeads(0,$user->id,$limit,$offset,$search,$order_column_name,$type,'Contract Staffing');
                $count = Lead::getAllLeadsCount(0,$user->id,$search,'Contract Staffing');
            }
        }

        // Get Payroll Leads
        if($service == 'payroll') {

            if($all_perm) {

                $leads_res = Lead::getAllLeads(1,$user->id,$limit,$offset,$search,$order_column_name,$type,'Payroll');
                $count = Lead::getAllLeadsCount(1,$user->id,$search,'Payroll');
            }
            else if($userwise_perm) {

                $leads_res = Lead::getAllLeads(0,$user->id,$limit,$offset,$search,$order_column_name,$type,'Payroll');
                $count = Lead::getAllLeadsCount(0,$user->id,$search,'Payroll');
            }
        }

        // Get HR Advisory Leads
        if($service == 'hr-advisory') {

            if($all_perm) {

                $leads_res = Lead::getAllLeads(1,$user->id,$limit,$offset,$search,$order_column_name,$type,'HR Advisory');
                $count = Lead::getAllLeadsCount(1,$user->id,$search,'HR Advisory');
            }
            else if($userwise_perm) {

                $leads_res = Lead::getAllLeads(0,$user->id,$limit,$offset,$search,$order_column_name,$type,'HR Advisory');
                $count = Lead::getAllLeadsCount(0,$user->id,$search,'HR Advisory');
            }
        }

        $lead = array();
        $i = 0;$j=0;

        foreach ($leads_res as $key => $value) {

            $action = '';
            $action .= '<a class="fa fa-circle" title="Show" href="'.route('lead.show',$value['id']).'" style="margin:2px;"></a>';

            if($all_perm || $value['access']) {

                $action .= '<a class="fa fa-edit" title="Edit" href="'.route('lead.edit',$value['id']).'" style="margin:2px;"></a>';
            }

            if($delete_perm) {

                $delete_view = \View::make('adminlte::partials.deleteModalNew', ['data' => $value, 'name' => 'lead','display_name'=>'Lead','Lead_Type' => 'Index']);
                $delete = $delete_view->render();
                $action .= $delete;
            }

            if($cancel_perm) {

                $cancel_view = \View::make('adminlte::partials.cancelbill', ['data' => $value, 'name' => 'lead','display_name'=>'Lead']);
                $cancel = $cancel_view->render();
                $action .= $cancel;
            }

            if ($value['convert_client'] == 0) {

                if($lead_to_client_perm) {
                    $action .= '<a title="Convert lead to client"  class="fa fa-clone" href="'.route('lead.clone',$value['id']).'" style="margin:2px;"></a>';
                }

            }

            $checkbox = '<input type=checkbox name=lead value='.$value['id'].' class=other_leads id='.$value['id'].'/>';

            $company_name = '<a style="white-space: pre-wrap; word-wrap: break-word; color:black; text-decoration:none;">'.$value['name'].'</a>';

            $coordinator_name = '<a style="white-space: pre-wrap; word-wrap: break-word; color:black; text-decoration:none;">'.$value['coordinator_name'].'</a>';

            if($value['convert_client'] == '1') {

                $checkbox = '';

                $data = array(++$j,$checkbox,$action,$company_name,$coordinator_name,$value['mail'],$value['mobile'],$value['city'],$value['referredby'],$value['website'],$value['lead_status'],$value['convert_client']);
            }
            else {

                $data = array(++$j,$checkbox,$action,$company_name,$coordinator_name,$value['mail'],$value['mobile'],$value['city'],$value['referredby'],$value['website'],$value['lead_status'],$value['convert_client']);
            }
            
            $lead[$i] = $data;
            $i++;
        }

        $json_data = array(
            'draw' => intval($draw),
            'recordsTotal' => intval($count),
            'recordsFiltered' => intval($count),
            "data" => $lead
        );
        echo json_encode($json_data);exit;
    }


    public function cancellead() {

        $user = \Auth::user();
        $cancel_perm = $user->can('display-cancel-lead');
        $all_perm = $user->can('display-lead');
        $userwise_perm = $user->can('display-user-wise-lead');

        if($cancel_perm && $all_perm) {

            $count = Lead::getCancelLeadsCount(1,$user->id);
        }
        else if($cancel_perm && $userwise_perm) {

            $count = Lead::getCancelLeadsCount(0,$user->id);
        }
        return view('adminlte::lead.cancel',compact('count'));        
    }

    public function getCancelLeadsDetails() {

        $draw = $_GET['draw'];
        $limit = $_GET['length'];
        $offset = $_GET['start'];
        $search = $_GET['search']['value'];
        $order = $_GET['order'][0]['column'];
        $type = $_GET['order'][0]['dir'];

        $order_column_name = self::getLeadOrderColumnName($order);
        $user = \Auth::user();
     
        $cancel_perm = $user->can('display-cancel-lead');
        $all_perm = $user->can('display-lead');
        $userwise_perm = $user->can('display-user-wise-lead');
        $delete_perm = $user->can('lead-delete');

        if($cancel_perm && $all_perm) {

            $leads_res = Lead::getCancelLeads(1,$user->id,$limit,$offset,$search,$order_column_name,$type);
            $count = Lead::getCancelLeadsCount(1,$user->id,$search);
        }
        else if($cancel_perm && $userwise_perm) {

            $leads_res = Lead::getCancelLeads(0,$user->id,$limit,$offset,$search,$order_column_name,$type);
            $count = Lead::getCancelLeadsCount(0,$user->id,$search);
        }

        $lead = array();
        $i = 0;$j = 0;

        foreach ($leads_res as $key => $value) {

            $action = '';

            $action .= '<a class="fa fa-circle" title="Show" href="'.route('lead.show',$value['id']).'" style="margin:2px;"></a>';

            if($all_perm || $value['access']) {
                $action .= '<a class="fa fa-edit" title="Edit" href="'.route('lead.edit',$value['id']).'" style="margin:2px;"></a>';
            }
            if ($delete_perm) {
                $delete_view = \View::make('adminlte::partials.deleteModalNew', ['data' => $value, 'name' => 'lead','display_name'=>'Lead','Lead_Type' => 'Cancel']);
                $delete = $delete_view->render();
                $action .= $delete;
            }

            $company_name = '<a style="white-space: pre-wrap; word-wrap: break-word; color:black; text-decoration:none;">'.$value['name'].'</a>';
            $coordinator_name = '<a style="white-space: pre-wrap; word-wrap: break-word; color:black; text-decoration:none;">'.$value['coordinator_name'].'</a>';

            $data = array(++$j,$action,$company_name,$coordinator_name,$value['mail'],$value['mobile'],$value['city'],$value['referredby'],$value['website'],$value['lead_status'],$value['convert_client']);
            $lead[$i] = $data;
            $i++;
        }

        $json_data = array(
            'draw' => intval($draw),
            'recordsTotal' => intval($count),
            'recordsFiltered' => intval($count),
            "data" => $lead
        );

        echo json_encode($json_data);exit;
    }

    public function cancel($id) {

        $lead = Lead::find($id);
        $lead->cancel_lead = '1';
        $lead_cancel = $lead->save();

        $company_name = $lead->name;
        $service = $lead->service;
        $city = $lead->city;
        $referredby_id = $lead->referredby;

        $user = \Auth::user();
        $user_id = $user->id;
        $user_email = $user->email;

        $superadminuserid = getenv('SUPERADMINUSERID');
        $superadminemail = User::getUserEmailById($superadminuserid);

        //$strategyuserid = getenv('STRATEGYUSERID');
        $strategyuserid = getenv('ALLCLIENTVISIBLEUSERID');
        
        $strategyemail = User::getUserEmailById($strategyuserid);
        $referredby_email = User::getUserEmailById($referredby_id);

        $cc_users_array = array($superadminemail,$strategyemail,$referredby_email);

        $module = "Cancel Lead";
        $sender_name = $user_id;
        $to = $user_email;

        $cc_users_array = array_filter($cc_users_array);
        $cc = implode(",",$cc_users_array);
        
        $subject = "Cancel Lead for " . $service . " - ". $company_name . " - " . $city;
        $message = "Cancel Lead for " . $service . " - ". $company_name . " - " . $city;
        $module_id = $id;

        event(new NotificationMail($module,$sender_name,$to,$subject,$message,$module_id,$cc));
        return redirect()->route('lead.index')->with('success','Lead Canceled Successfully.');
    }

    public function create() {

        $user = \Auth::user();
        $user_id = $user->id;
        $action = 'add';
        $generate_lead = '0';
        $cancel_lead = '0';
        $leadservices_status = Lead::getLeadService();

        $users = User::getAllUsers();

        $status = Lead::getLeadStatus();
        $service ='';
        $lead_status ='Active';
        $referredby = $user_id;

        $co_prefix = ClientBasicinfo::getcoprefix();
        $co_category = '';

        return view('adminlte::lead.create',compact('leadservices_status','action','generate_lead','service','users', 'referredby','status','cancel_lead','lead_status','co_prefix','co_category'));
    }

    public function store(Request $request) {

        $user = \Auth::user();
 	    $input = $request->all();

        $company_name = trim($input['name']);
        $co_category = $input['co_category'];
        $coordinator_name = trim($input['contact_point']);
        $email=$input['mail'];
        $s_email=$input['s_email'];
        $mobile=$input['mobile'];
        $other_number=$input['other_number'];
        $display_name=trim($input['display_name']);
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
        $lead->coordinator_prefix=$co_category;
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
        
        $validator = \Validator::make(Input::all(),$lead::$rules);

        if($validator->fails()){
            return redirect('lead/create')->withInput(Input::all())->withErrors($validator->errors());
        }

        $lead->save();

        // For Lead Emails [data entry in email_notification table]
        $lead_id = $lead->id;
        $user_id = $user->id;
        $user_email = $user->email;
        $superadminuserid = getenv('SUPERADMINUSERID');
        //$strategyuserid = getenv('STRATEGYUSERID');
        $strategyuserid = getenv('ALLCLIENTVISIBLEUSERID');

        $superadminemail = User::getUserEmailById($superadminuserid);
        $strategyemail = User::getUserEmailById($strategyuserid);
        $referredby_email = User::getUserEmailById($referredby_id);

        $cc_users_array = array($superadminemail,$strategyemail,$referredby_email);

        $module = "Lead";
        $sender_name = $user_id;
        $to = $user_email;

        $cc_users_array = array_filter($cc_users_array);
        $cc = implode(",",$cc_users_array);
        
        $subject = "New Lead for " . $leads . " - ". $company_name . " - " . $city;
        $message = "New Lead for " . $leads . " - ". $company_name . " - " . $city;
        $module_id = $lead_id;

        event(new NotificationMail($module,$sender_name,$to,$subject,$message,$module_id,$cc));
        return redirect()->route('lead.index')->with('success','Leads Created Successfully.');
	}

    public function show($id) {

        $lead_details = Lead::getLeadDetailsById($id);

        return view('adminlte::lead.show',compact('lead_details'));
    }

	public function edit($id) {

        $action = 'edit';
        $generate_lead = '0';
        $leadservices_status = Lead::getLeadService();
        $status = Lead::getLeadStatus();
        $lead = Lead::find($id);

        $user = \Auth::user();
        $all_perm = $user->can('display-lead');
        $userwise_perm = $user->can('display-user-wise-lead');

        if($all_perm || $userwise_perm) {

            $cancel_lead = $lead->cancel_lead;
            $convert_client = $lead->convert_client;
            if($convert_client == 1){
                $generate_lead = 1;
            }

            $service = $lead->service;
            $referredby = $lead->referredby;
            $lead_status = $lead->lead_status;

            $users = User::getAllUsers();
        }
        else {
            return view('errors.403');
        }

        $co_prefix = ClientBasicinfo::getcoprefix();
        $co_category = $lead->coordinator_prefix;
        $lead['contact_point'] = $lead->coordinator_name;
        	        
	   return view('adminlte::lead.edit',compact('lead','action','users','generate_lead','leadservices_status','service','convert_client', 'referredby','status','cancel_lead','lead_status','co_prefix','co_category'));
	}

	public function update(Request $request, $id) {

	    $user  = \Auth::user()->id;
        $input = $request->all();

	 	$name = trim($request->get('name'));
        $co_category = $request->get('co_category');
        $coordinator_name = trim($request->get('contact_point'));
        $email = $request->get('mail');
        $s_email = $request->get('s_email');
        $mobile = $request->get('mobile');
        $other_number = $request->get('other_number');
        $display_name = trim($request->get('display_name'));
        $leads = $request->get('leads');
        $remarks = $request->get('remarks');
        $city = $request->get('city');
        $state = $request->get('state');
        $country = $request->get('country');
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
            $cancel_lead = 0;
        }
         
        $lead_basic = Lead::find($id);

        if(isset($name))
            $lead_basic->name = $name;
        if(isset($co_category))
            $lead_basic->coordinator_prefix = $co_category;
        if(isset($coordinator_name))
            $lead_basic->coordinator_name = $coordinator_name;
        if(isset($email))
            $lead_basic->mail = $email;
        if(isset($s_email))
            $lead_basic->s_email = $s_email;
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
            $lead_basic->city = $city;
        if(isset($state))
            $lead_basic->state = $state;
        if(isset($country))
            $lead_basic->country = $country;
        if(isset($website))
            $lead_basic->website = $website;
        if(isset($source))
            $lead_basic->source = $source;
        if(isset($designation))
            $lead_basic->designation = $designation;
        if(isset($referredby_id))
            $lead_basic->referredby = $referredby_id;
        if(isset($lead_status))
            $lead_basic->lead_status = $lead_status;
        if(isset($cancel_lead))
            $lead_basic->cancel_lead = $cancel_lead;

        $validator = \Validator::make(Input::all(),$lead_basic::$rules);

        if($validator->fails()) {
            return redirect('lead/'.$lead_basic->id.'/edit')->withInput(Input::all())->withErrors($validator->errors());
        }

        $lead_basic->save();
        return redirect()->route('lead.index')->with('success','Lead Updated Successfully.');
	 }

    public function leadClone($id) {

        $client_cat = ClientBasicinfo::getCategory();
        $client_category = ' ';

        $client_status_key = ClientBasicinfo::getStatus();
        $client_status = 1;

        // For Superadmin,Strategy,Manager Users
        $client_all_status_key = ClientBasicinfo::getAllStatus();
        $client_all_status = 1;

        $generate_lead = '0';
        $industry_res = Industry::orderBy('name','ASC')->get();
        $industry = array();
        $industry_id = '';

        if(sizeof($industry_res) > 0) {
            foreach($industry_res as $r) {
                $industry[$r->id]=$r->name;
            }
        }

        $user = \Auth::user();

        // For account manager

        $recruitment = getenv('RECRUITMENT');
        $hr_advisory = getenv('HRADVISORY');
        $management = getenv('MANAGEMENT');
        $type_array = array($recruitment,$hr_advisory,$management);

        $users_array = User::getAllUsers(NULL,'Yes');
        $users = array();
        
        if(isset($users_array) && sizeof($users_array) > 0) {

            foreach ($users_array as $k1 => $v1) {
                               
                $user_details = User::getAllDetailsByUserID($k1);

                if($user_details->type == '2') {
                    if($user_details->hr_adv_recruitemnt == 'Yes') {
                        $users[$k1] = $v1;
                    }
                }
                else {
                    $users[$k1] = $v1;
                }    
            }
        }
        $users[0] = 'Yet to Assign';
        
        $lead = Lead::find($id);
        $name = $lead->name;
        $website = $lead->website;
        $billing_city = $lead->city;
        $billing_state = $lead->state;
        $billing_country = $lead->country;
        $user_id = $lead->referredby;
        $convert_client = 0;

        if($generate_lead == 1) {
            $lead->convert_client = 1;
        }
        $lead->save();

        $action = "copy";

        $co_prefix = ClientBasicinfo::getcoprefix();
        $co_category = $lead->coordinator_prefix;

        $percentage_charged_below = '8.33';
        $percentage_charged_above = '8.33';
        $referredby = $lead->referredby;

        $lead['contact_point'] = $lead->coordinator_name;

        return view('adminlte::client.create',compact('co_prefix','co_category','name', 'website', 'billing_city','billing_state','billing_country','lead','action','generate_lead','industry','users','user_id','industry_id','client_cat','client_category','client_status_key','client_status','percentage_charged_below','percentage_charged_above','referredby','client_all_status_key','client_all_status'));
    }

    public function clonestore(Request $request,$id) {

        $user_id = \Auth::user()->id;
        $user_name = \Auth::user()->name;
        $user_email = \Auth::user()->email;

        $input = $request->all();

        $client_basic_info = new ClientBasicinfo();
        $client_basic_info->name = trim($input['name']);
        $client_basic_info->display_name = trim($input['display_name']);
        $client_basic_info->mail = $input['mail'];
        $client_basic_info->s_email = $input['s_email'];
        $client_basic_info->description = $input['description'];
        $client_basic_info->mobile = $input['mobile'];
        $client_basic_info->other_number = $input['other_number'];
        $client_basic_info->website = $input['website'];
        $client_basic_info->account_manager_id = $input['account_manager'];
        $client_basic_info->industry_id = $input['industry_id'];
        $generatelead = $input['generatelead'];

        $convert_client = 0;
        if($generatelead == 1) {
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

        $client_basic_info->coordinator_name = trim($input['contact_point']);
        $client_basic_info->coordinator_prefix= $input['co_category'];

        $status = $input['status'];
        $client_basic_info->status = $status;

        if(isset($input['client_category']))
            $client_basic_info->category = $input['client_category'];
        else            
            $client_basic_info->category = '';
        
        if(isset($input['percentage_charged_below']) && $input['percentage_charged_below']!= '' ) {
            $client_basic_info->percentage_charged_below=$input['percentage_charged_below'];
        }
        else {
            $client_basic_info->percentage_charged_below='8.33';
        }
        
        if(isset($input['percentage_charged_above']) && $input['percentage_charged_above']!='' ) {
            $client_basic_info->percentage_charged_above=$input['percentage_charged_above'];
        }
        else {
            $client_basic_info->percentage_charged_above='8.33';
        }
        
        if(isset($input['second_line_am'])) {
            $client_basic_info->second_line_am = $input['second_line_am'];
        }
        else {
            $client_basic_info->second_line_am = 0;
        }

        $client_basic_info->created_at = time();
        $client_basic_info->updated_at = time();
        $client_basic_info->delete_client = 0;

        // Save Department Id for Different Dashboard
        $vibhuti_user_id = getenv('STRATEGYUSERID');
        $account_manager_id = $input['account_manager'];

        if($account_manager_id == $vibhuti_user_id) {

            $client_basic_info->department_id = 2;
        }
        else {

            $client_basic_info->department_id = 1;
        }

        if($client_basic_info->save()) {

            // If client basic info added successfully then lead convert to client
            $lead = Lead::find($id);
            if($generatelead == 1) {
                $lead->convert_client = 1;
            }
            $lead->save();

            $client_id = $client_basic_info->id;
            $client_name = $client_basic_info->name;

            // Add Entry in Client Timeline.
            $client_timeline = new ClientTimeline();
            $client_timeline->user_id = $input['account_manager'];
            $client_timeline->client_id = $client_id;
            $client_timeline->save();
            
            $client_address = new ClientAddress();
            $client_address->client_id = $client_id;

            if(isset($input['billing_country']) && $input['billing_country']!='') {
                $client_address->billing_country = $input['billing_country'];
            }
            if(isset($input['billing_state']) && $input['billing_state']!='') {
                $client_address->billing_state = $input['billing_state'];
            }
            if(isset($input['billing_street1']) && $input['billing_street1']!='') {
                $client_address->billing_street1 = $input['billing_street1'];
            }
            if(isset($input['billing_street2']) && $input['billing_street2']!='') {
                $client_address->billing_street2 = $input['billing_street2'];
            }
            if(isset($input['billing_code']) && $input['billing_code']!='') {
                $client_address->billing_code = $input['billing_code'];
            }
            if(isset($input['billing_city']) && $input['billing_city']!='') {
                $client_address->billing_city = $input['billing_city'];
            }
            if(isset($input['shipping_country']) && $input['shipping_country']!='') {
                $client_address->shipping_country = $input['shipping_country'];
            }
            if(isset($input['shipping_state']) && $input['shipping_state']!='') {
                $client_address->shipping_state = $input['shipping_state'];
            }
            if(isset($input['shipping_street1']) && $input['shipping_street1']!='') {
                $client_address->shipping_street1 = $input['shipping_street1'];
            }
            if(isset($input['shipping_street2']) && $input['shipping_street2']!='') {
                $client_address->shipping_street2 = $input['shipping_street2'];
            }
            if(isset($input['shipping_code']) && $input['shipping_code']!='') {
                $client_address->shipping_code = $input['shipping_code'];
            }
            if(isset($input['shipping_city']) && $input['shipping_city']!='') {
                $client_address->shipping_city = $input['shipping_city'];
            }

            $client_address->updated_at = date("Y-m-d H:i:s");
            $client_address->save();

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

                if(!$client_contract->move($dir_name, $client_contract_name)) {
                    return false;
                }
                else {

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

                if(!$client_logo->move($dir_name, $client_logo_key)) {
                    return false;
                }
                else {

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

            if (isset($others_doc) && $others_doc != '') {

                foreach ($others_doc as $k => $v) {

                    if (isset($v) && $v->isValid()) {

                        $others_doc_name = $v->getClientOriginalName();
                        $others_filesize = filesize($v);

                        $dir_name = "uploads/clients/".$client_id."/";
                        $others_doc_key = "uploads/clients/".$client_id."/".$others_doc_name;

                        if (!file_exists($dir_name)) {
                            mkdir("uploads/clients/$client_id", 0777,true);
                        }

                        if(!$v->move($dir_name, $others_doc_name)) {
                            return false;
                        }
                        else {

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
                } 
            }

            // Notifications : On adding new client notify Super Admin via notification
            $module_id = $client_id;
            $module = 'Client';
            $message = $user_name . " added new Client";
            $link = route('client.show',$client_id);

            $super_admin_userid = getenv('SUPERADMINUSERID');
            $user_arr = array();
            $user_arr[] = $super_admin_userid;

            event(new NotificationEvent($module_id, $module, $message, $link, $user_arr));

            //$referredby_id = $input['referredby'];

            $account_manager_id = $input['account_manager'];

            // Email Notification : data store in datebase
            //$strategyuserid = getenv('STRATEGYUSERID');
            $all_client_user_id = getenv('ALLCLIENTVISIBLEUSERID');
            
            $superadminemail = User::getUserEmailById($super_admin_userid);
            $all_client_user_email = User::getUserEmailById($all_client_user_id);
            //$referredby_email = User::getUserEmailById($referredby_id);
            $account_manager_email = User::getUserEmailById($account_manager_id);

            //$cc_users_array = array($superadminemail,$all_client_user_email,$referredby_email);
            $cc_users_array = array($superadminemail,$all_client_user_email,$account_manager_email);

            $module = "Client";
            $sender_name = $user_id;
            $to = $user_email;
            $subject = "New Client - " . $client_name . " - " . $input['billing_city'];
            $message = "<tr><td>" . $user_name . " added new Client </td></tr>";
            $module_id = $client_id;
            $cc_users_array = array_filter($cc_users_array);
            $cc = implode(",",$cc_users_array);

            event(new NotificationMail($module,$sender_name,$to,$subject,$message,$module_id,$cc));

            return redirect()->route('client.index')->with('success','Client Created Successfully.');
        }
        else {
            return redirect('client/create')->withInput(Input::all())->withErrors($client_basic_info->errors());
        }
     }

	public function destroy(Request $request,$id) {

        Lead::where('id',$id)->delete();

        $Lead_Type = $request->input('Lead_Type');

        if($Lead_Type == 'Index') {
            return redirect()->route('lead.index')->with('success','Lead Deleted Successfully.');
        }
        else {
            return redirect()->route('lead.leadcancel')->with('success','Lead Deleted Successfully.');
        }
    }

    public function checkLeadId() {

        if (isset($_POST['leads_ids']) && $_POST['leads_ids'] != '') {
            $leads_ids = $_POST['leads_ids'];
        }

        if (isset($leads_ids) && sizeof($leads_ids) > 0) {
            $msg['success'] = 'Success';
        }
        else {
            $msg['err'] = '<b>Please Select Lead.</b>';
            $msg['msg'] = "Fail";
        }
        return $msg;
    }

    public function postLeadEmails(Request $request) {

        $user = \Auth::user();
        $user_id = $user->id;

        $leads_ids = $request->input('email_leads_ids');
        $lead_ids_array = explode(",",$leads_ids);

        $email_subject = $request->input('email_subject');
        $email_body = $request->input('email_body');
        $updated_at = date('Y-m-d H:i:s');

        foreach($lead_ids_array as $key => $value) {

            $lead_details = Lead::getLeadDetailsById($value);
            $client_email = $lead_details['mail'];

            $module = 'Lead Bulk Email';
            $sender_name = $user_id;
            $to = $client_email;
            $subject = $email_subject; 
            //$cc = 'rajlalwani@adlertalent.com';
            $cc = 'info@adlertalent.com';
            $module_id = $value;
            $new_email_body = "<tr><td style='padding:8px;'>$email_body</td></tr>";
            
            event(new NotificationMail($module,$sender_name,$to,$subject,$new_email_body,$module_id,$cc));
        }

        return redirect()->route('lead.index')->with('success','Email Sent Successfully.');
    }
}