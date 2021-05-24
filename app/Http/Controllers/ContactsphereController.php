<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Contactsphere;
use App\User;
use App\Lead;
use Illuminate\Support\Facades\Input;
use Mockery\CountValidator\Exception;
use App\Events\NotificationEvent;
use App\Events\NotificationMail;

class ContactsphereController extends Controller
{
    public function index() {

        $user = \Auth::user();
        $all_perm = $user->can('display-contactsphere');
        $userwise_perm = $user->can('display-user-wise-contactsphere');

        if($all_perm) {
        
            $count = Contactsphere::getAllContactsCount(1,$user->id);

            $convert_lead_count = Contactsphere::getConvertedLead(1,$user->id);
        }
        else if($userwise_perm) {

            $count = Contactsphere::getAllContactsCount(0,$user->id);

            $convert_lead_count = Contactsphere::getConvertedLead(0,$user->id);
        }

        return view('adminlte::contactsphere.index',compact('count','convert_lead_count'));
    }

    public function getOrderColumnName($order) {

        $order_column_name = '';

        if (isset($order) && $order >= 0) {
            if ($order == 0) {
                $order_column_name = "contactsphere.id";
            }
            else if ($order == 3) {
                $order_column_name = "contactsphere.name";
            }
            else if ($order == 4) {
                $order_column_name = "contactsphere.designation";
            }
            else if ($order == 5) {
                $order_column_name = "contactsphere.company";
            }
            else if ($order == 6) {
                $order_column_name = "contactsphere.contact_number";
            }
            else if ($order == 7) {
                $order_column_name = "contactsphere.city";
            }
            else if ($order == 8) {
                $order_column_name = "contactsphere.official_email_id";
            }
            else if ($order == 9) {
                $order_column_name = "contactsphere.personal_id";
            }
            else if ($order == 10) {
                $order_column_name = "users.name";
            }
        }
        return $order_column_name;
    }

    public function getAllContactsphereDetails() {

        $draw = $_GET['draw'];
        $limit = $_GET['length'];
        $offset = $_GET['start'];
        $search = $_GET['search']['value'];
        $order = $_GET['order'][0]['column'];
        $type = $_GET['order'][0]['dir'];

        $order_column_name = self::getOrderColumnName($order);
        $user = \Auth::user();

        $all_perm = $user->can('display-contactsphere');
        $userwise_perm = $user->can('display-user-wise-contactsphere');
        $hold_perm = $user->can('hold-contactsphere');
        $forbid_perm = $user->can('forbid-contactsphere');
        $contact_to_lead_perm = $user->can('contactsphere-to-lead');
        $delete_perm = $user->can('contactsphere-delete');

        if($all_perm) {

            $count = Contactsphere::getAllContactsCount(1,$user->id,$search,'');
            $contacts_res = Contactsphere::getAllContacts(1,$user->id,$limit,$offset,$search,$order_column_name,$type);
        }
        else if($userwise_perm) {

            $count = Contactsphere::getAllContactsCount(0,$user->id,$search,'');
            $contacts_res = Contactsphere::getAllContacts(0,$user->id,$limit,$offset,$search,$order_column_name,$type);
        }

        $contacts = array();
        $i = 0;$j = 0;

        foreach ($contacts_res as $key => $value) {

            $action = '';

            $action .= '<a class="fa fa-circle" title="Show" href="'.route('contactsphere.show',$value['id']).'" style="margin:2px;"></a>';

            if($all_perm || $userwise_perm) {
                $action .= '<a class="fa fa-edit" title="Edit" href="'.route('contactsphere.edit',$value['id']).'" style="margin:2px;"></a>';
            }

            if ($delete_perm) {

                $delete_view = \View::make('adminlte::partials.deleteModalNew', ['data' => $value, 'name' => 'contactsphere','display_name'=>'Contact','Contact_Type' => 'Index']);
                $delete = $delete_view->render();
                $action .= $delete;
            }

            if ($hold_perm) {

                $hold_view = \View::make('adminlte::partials.holdContact', ['data' => $value, 'name' => 'contactsphere','display_name'=>'Contact']);
                $hold = $hold_view->render();
                $action .= $hold;
            }

            if ($forbid_perm) {
                
                $forbid_view = \View::make('adminlte::partials.forbidContact', ['data' => $value, 'name' => 'contactsphere','display_name'=>'Contact']);
                $forbid = $forbid_view->render();
                $action .= $forbid;
            }

            if ($value['convert_lead'] == 0) {

                if($contact_to_lead_perm) {

                    $action .= '<a title="Convert Contact to Lead"  class="fa fa-clone" href="'.route('contactsphere.clone',$value['id']).'" style="margin:2px;"></a>';
                }
            }

            $checkbox = '<input type=checkbox name=contact value='.$value['id'].' class=other_contacts id='.$value['id'].'/>';

            $name = '<a style="white-space: pre-wrap; word-wrap: break-word; color:black; text-decoration:none;">'.$value['name'].'</a>';

            $designation = '<a style="white-space: pre-wrap; word-wrap: break-word; color:black; text-decoration:none;">'.$value['designation'].'</a>';

            $company_name = '<a style="white-space: pre-wrap; word-wrap: break-word; color:black; text-decoration:none;">'.$value['name'].'</a>';

            if($value['convert_lead'] == '1') {

                $checkbox = '';

                $data = array(++$j,$checkbox,$action,$name,$designation,$company_name,$value['contact_number'],$value['city'],$value['referred_by'],$value['official_email_id'],$value['personal_id'],$value['convert_lead']);
            }
            else {
                $data = array(++$j,$checkbox,$action,$name,$designation,$company_name,$value['contact_number'],$value['city'],$value['referred_by'],$value['official_email_id'],$value['personal_id'],$value['convert_lead']);
            }
            $contacts[$i] = $data;
            $i++;
        }

        $json_data = array(
            'draw' => intval($draw),
            'recordsTotal' => intval($count),
            'recordsFiltered' => intval($count),
            "data" => $contacts
        );

        echo json_encode($json_data);exit;
    }

    public function hold($id) {

        $contact = Contactsphere::find($id);
        $contact->hold = '1';
        $contact->save();

        $company_name = $contact->name;
        $city = $contact->city;
        $referredby_id = $contact->referred_by;

        $user = \Auth::user();
        $user_id = $user->id;
        $user_email = $user->email;

        $superadminuserid = getenv('SUPERADMINUSERID');
        $superadminemail = User::getUserEmailById($superadminuserid);

        $allclientvisibleuserid = getenv('ALLCLIENTVISIBLEUSERID');
        $allclientvisibleuseremail = User::getUserEmailById($allclientvisibleuserid);

        $referredby_email = User::getUserEmailById($referredby_id);

        $cc_users_array = array($superadminemail,$allclientvisibleuseremail,$referredby_email);

        $module = "Hold Contact";
        $sender_name = $user_id;
        $to = $user_email;

        $cc_users_array = array_filter($cc_users_array);
        $cc = implode(",",$cc_users_array);
        
        $subject = "Hold Contact " . " - ". $company_name . " - " . $city;
        $message = "Hold Contact " . " - ". $company_name . " - " . $city;
        $module_id = $id;

        event(new NotificationMail($module,$sender_name,$to,$subject,$message,$module_id,$cc));

        return redirect()->route('contactsphere.index')->with('success','Contact on Hold Successfully.');
    }

    public function holdContactsphere() {

        $user = \Auth::user();
        $hold_perm = $user->can('display-hold-contactsphere');
        $all_perm = $user->can('display-contactsphere');
        $userwise_perm = $user->can('display-user-wise-contactsphere');

        if($hold_perm && $all_perm) {

            $count = Contactsphere::getHoldContactsCount(1,$user->id);
        }
        else if($hold_perm && $userwise_perm) {

            $count = Contactsphere::getHoldContactsCount(0,$user->id);
        }
        return view('adminlte::contactsphere.hold',compact('count'));        
    }

    public function getHoldContactsphereDetails() {

        $draw = $_GET['draw'];
        $limit = $_GET['length'];
        $offset = $_GET['start'];
        $search = $_GET['search']['value'];
        $order = $_GET['order'][0]['column'];
        $type = $_GET['order'][0]['dir'];

        $order_column_name = self::getOrderColumnName($order);
        $user = \Auth::user();

        $all_perm = $user->can('display-contactsphere');
        $userwise_perm = $user->can('display-user-wise-contactsphere');
        $hold_perm = $user->can('display-hold-contactsphere');
        $delete_perm = $user->can('contactsphere-delete');

        if($hold_perm && $all_perm) {

            $contacts_res = Contactsphere::getHoldContacts(1,$user->id,$limit,$offset,$search,$order_column_name,$type);
            $count = Contactsphere::getHoldContactsCount(1,$user->id,$search);
        }
        else if($hold_perm && $userwise_perm) {

            $contacts_res = Contactsphere::getHoldContacts(0,$user->id,$limit,$offset,$search,$order_column_name,$type);
            $count = Contactsphere::getHoldContactsCount(0,$user->id,$search);
        }

        $contacts = array();
        $i = 0;$j = 0;

        foreach ($contacts_res as $key => $value) {

            $action = '';

            $action .= '<a class="fa fa-circle" title="Show" href="'.route('contactsphere.show',$value['id']).'" style="margin:2px;"></a>';

            if($all_perm || $userwise_perm) {
                $action .= '<a class="fa fa-edit" title="Edit" href="'.route('contactsphere.edit',$value['id']).'" style="margin:2px;"></a>';
            }

            if ($delete_perm) {

                $delete_view = \View::make('adminlte::partials.deleteModalNew', ['data' => $value, 'name' => 'contactsphere','display_name'=>'Contact','Contact_Type' => 'Index']);
                $delete = $delete_view->render();
                $action .= $delete;
            }

            $name = '<a style="white-space: pre-wrap; word-wrap: break-word; color:black; text-decoration:none;">'.$value['name'].'</a>';

            $designation = '<a style="white-space: pre-wrap; word-wrap: break-word; color:black; text-decoration:none;">'.$value['designation'].'</a>';

            $company_name = '<a style="white-space: pre-wrap; word-wrap: break-word; color:black; text-decoration:none;">'.$value['name'].'</a>';

            $data = array(++$j,$action,$name,$designation,$company_name,$value['contact_number'],$value['city'],$value['referred_by'],$value['official_email_id'],$value['personal_id']);
            
            $contacts[$i] = $data;
            $i++;
        }

        $json_data = array(
            'draw' => intval($draw),
            'recordsTotal' => intval($count),
            'recordsFiltered' => intval($count),
            "data" => $contacts
        );

        echo json_encode($json_data);exit;
    }

    public function forbid($id) {

        $contact = Contactsphere::find($id);
        $contact->forbid = '1';
        $contact->save();

        $company_name = $contact->name;
        $city = $contact->city;
        $referredby_id = $contact->referred_by;

        $user = \Auth::user();
        $user_id = $user->id;
        $user_email = $user->email;

        $superadminuserid = getenv('SUPERADMINUSERID');
        $superadminemail = User::getUserEmailById($superadminuserid);

        $allclientvisibleuserid = getenv('ALLCLIENTVISIBLEUSERID');
        $allclientvisibleuseremail = User::getUserEmailById($allclientvisibleuserid);

        $referredby_email = User::getUserEmailById($referredby_id);

        $cc_users_array = array($superadminemail,$allclientvisibleuseremail,$referredby_email);

        $module = "Forbid Contact";
        $sender_name = $user_id;
        $to = $user_email;

        $cc_users_array = array_filter($cc_users_array);
        $cc = implode(",",$cc_users_array);
        
        $subject = "Forbid Contact " . " - ". $company_name . " - " . $city;
        $message = "Forbid Contact " . " - ". $company_name . " - " . $city;
        $module_id = $id;

        event(new NotificationMail($module,$sender_name,$to,$subject,$message,$module_id,$cc));
        
        return redirect()->route('contactsphere.index')->with('success','Contact Forbid Successfully.');
    }

    public function forbidContactsphere() {

        $user = \Auth::user();
        $forbid_perm = $user->can('display-forbid-contactsphere');
        $all_perm = $user->can('display-contactsphere');
        $userwise_perm = $user->can('display-user-wise-contactsphere');

        if($forbid_perm && $all_perm) {

            $count = Contactsphere::getForbidContactsCount(1,$user->id);
        }
        else if($forbid_perm && $userwise_perm) {

            $count = Contactsphere::getForbidContactsCount(0,$user->id);
        }
        return view('adminlte::contactsphere.forbid',compact('count'));        
    }

    public function getForbidContactsphereDetails() {

        $draw = $_GET['draw'];
        $limit = $_GET['length'];
        $offset = $_GET['start'];
        $search = $_GET['search']['value'];
        $order = $_GET['order'][0]['column'];
        $type = $_GET['order'][0]['dir'];

        $order_column_name = self::getOrderColumnName($order);
        $user = \Auth::user();

        $all_perm = $user->can('display-contactsphere');
        $userwise_perm = $user->can('display-user-wise-contactsphere');
        $forbid_perm = $user->can('display-forbid-contactsphere');
        $delete_perm = $user->can('contactsphere-delete');

        if($forbid_perm && $all_perm) {

            $contacts_res = Contactsphere::getForbidContacts(1,$user->id,$limit,$offset,$search,$order_column_name,$type);
            $count = Contactsphere::getForbidContactsCount(1,$user->id,$search);
        }
        else if($forbid_perm && $userwise_perm) {

            $contacts_res = Contactsphere::getForbidContacts(0,$user->id,$limit,$offset,$search,$order_column_name,$type);
            $count = Contactsphere::getForbidContactsCount(0,$user->id,$search);
        }

        $contacts = array();
        $i = 0;$j = 0;

        foreach ($contacts_res as $key => $value) {

            $action = '';

            $action .= '<a class="fa fa-circle" title="Show" href="'.route('contactsphere.show',$value['id']).'" style="margin:2px;"></a>';

            if($all_perm || $userwise_perm) {
                $action .= '<a class="fa fa-edit" title="Edit" href="'.route('contactsphere.edit',$value['id']).'" style="margin:2px;"></a>';
            }

            if ($delete_perm) {

                $delete_view = \View::make('adminlte::partials.deleteModalNew', ['data' => $value, 'name' => 'contactsphere','display_name'=>'Contact','Contact_Type' => 'Index']);
                $delete = $delete_view->render();
                $action .= $delete;
            }

            $name = '<a style="white-space: pre-wrap; word-wrap: break-word; color:black; text-decoration:none;">'.$value['name'].'</a>';

            $designation = '<a style="white-space: pre-wrap; word-wrap: break-word; color:black; text-decoration:none;">'.$value['designation'].'</a>';

            $company_name = '<a style="white-space: pre-wrap; word-wrap: break-word; color:black; text-decoration:none;">'.$value['name'].'</a>';

            $data = array(++$j,$action,$name,$designation,$company_name,$value['contact_number'],$value['city'],$value['referred_by'],$value['official_email_id'],$value['personal_id']);
            
            $contacts[$i] = $data;
            $i++;
        }

        $json_data = array(
            'draw' => intval($draw),
            'recordsTotal' => intval($count),
            'recordsFiltered' => intval($count),
            "data" => $contacts
        );

        echo json_encode($json_data);exit;
    }

    public function add() {

        $user = \Auth::user();
        $user_id = $user->id;
        $action = 'add';
        $generate_contact = '0';
        $hold_contact = '0';
        $forbid_contact = '0';

        $users = User::getAllUsers();
        $referredby_id = $user_id;

        return view('adminlte::contactsphere.add',compact('action','generate_contact','users','referredby_id','hold_contact','forbid_contact'));
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
}