<?php

namespace App\Http\Controllers;

use App\Comments;
use App\Utils;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\ClientBasicinfo;
use App\ClientAddress;
use App\ClientDoc;
use App\Industry;
use Illuminate\Support\Facades\Input;
use Mockery\CountValidator\Exception;
use Storage;
use App\User;
use App\JobOpen;
use App\Lead;
use Excel;
use App\Events\NotificationEvent;
use App\Events\NotificationMail;
use App\EmailsNotifications;
use App\JobVisibleUsers;
use App\Post;
use App\EmailTemplate;
use App\ClientRemarks;
use App\ClientTimeline;
use App\Notifications;
use Illuminate\Validation\Rule;
use Validator;
use App\JobAssociateCandidates;
use App\Interview;

class ClientController extends Controller
{
    public function index(Request $request) {

        $user =  \Auth::user();
        $user_id =  $user->id;

        $all_perm = $user->can('display-client');
        $userwise_perm = $user->can('display-account-manager-wise-client');

        if($all_perm) {
            $client_array = ClientBasicinfo::getAllClients(1,$user_id,0,0,0,0,'','','','','','','','');
            $count = sizeof($client_array);
        }
        else if($userwise_perm) {
            $client_array = ClientBasicinfo::getAllClients(0,$user_id,0,0,0,0,'','','','','','','','');
            $count = sizeof($client_array);
        }

        $i = 0;
        $active = 0;
        $passive = 0;
        $leaders = 0;
        $forbid = 0;
        $left = 0;
        $para_cat = 0;
        $mode_cat = 0;
        $std_cat = 0;

        foreach($client_array as $client) {

            if($client['status'] == 'Active') {
                $active++;
            }
            else if ($client['status'] == 'Passive') {
                $passive++;
            }
            else if($client['status'] == 'Leaders') {
                $leaders++;
            }
            else if($client['status'] == 'Forbid') {
                $forbid++;
            }
            else if($client['status'] == 'Left') {
                $left++;
            }

            if($client['category'] == 'Paramount') {
                $para_cat++;
            }
            else if($client['category'] == 'Moderate') {
                $mode_cat++;
            }
            else if($client['category'] == 'Standard') {
                $std_cat++;
            }
        }

        $users_array = User::getAllUsers(NULL,'Yes');
        $all_account_manager = array();
        
        if(isset($users_array) && sizeof($users_array) > 0) {

            foreach ($users_array as $k1 => $v1) {
                               
                $user_details = User::getAllDetailsByUserID($k1);

                if($user_details->type == '2') {
                    if($user_details->hr_adv_recruitemnt == 'Yes') {
                        $all_account_manager[$k1] = $v1;
                    }
                }
                else {
                    $all_account_manager[$k1] = $v1;
                }    
            }
        }

        $all_account_manager[0] = 'Yet to Assign';

        $email_template_names = EmailTemplate::getAllEmailTemplateNames($user_id);

        // Get clients for popup of add information
        $client_name_string = ClientBasicinfo::getBefore7daysClientDetails($user_id);

        // For not display superadmin popup
        $superadmin = getenv('SUPERADMINUSERID');
        $manager = getenv('MANAGERUSERID');

        // Get Client Status
        $status = ClientBasicinfo::getAllStatus();
        $status_id = 1;

        // Get Master Search Field List
        $field_list = ClientBasicinfo::getFieldsList();

        // Get Managed By Person List
        $users = array();
        
        if(isset($users_array) && sizeof($users_array) > 0) {

            $users[''] = 'Select User';

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

        // Get Category List
        $category_list = ClientBasicinfo::getCategory();

        // Get Status List
        $all_status[''] = 'Select Status';
        $all_status[1] = 'Active';
        $all_status[0] = 'Passive';
        $all_status[2] = 'Leaders';
        $all_status[4] = 'Left';

        // Get Client Industry

        $industry_res = Industry::orderBy('name','ASC')->get();
        $industry = array();

        if(sizeof($industry_res) > 0) {

            $industry[''] = 'Select Industry';

            foreach($industry_res as $r) {
                $industry[$r->id] = $r->name;
            }
        }
        
        return view('adminlte::client.index',compact('count','active','passive','leaders','forbid','left','para_cat','mode_cat','std_cat','all_account_manager','email_template_names','client_name_string','user_id','superadmin','manager','status','status_id','field_list','users','category_list','all_status','industry'));
    }

    public function getOrderColumnName($order) {

        $order_column_name = '';
        if (isset($order) && $order >= 0) {

            if ($order == 0) {
                $order_column_name = "client_basicinfo.id";
            }
            else if ($order == 3) {
                $order_column_name = "users.name";
            }
            else if ($order == 4) {
                $order_column_name = "client_basicinfo.name";
            }
            else if ($order == 5) {
                $order_column_name = "client_basicinfo.coordinator_prefix";
            }
            else if ($order == 6) {
                $order_column_name = "client_basicinfo.category";
            }
            else if ($order == 7) {
                $order_column_name = "client_basicinfo.status";
            }
            else if ($order == 8) {
                $order_column_name = "client_address.billing_city";
            }
            else if ($order == 9) {
                $order_column_name = "industry.name";
            }
        }
        return $order_column_name;
    }

    public function getAllClientsDetails() {

        $draw = $_GET['draw'];
        $limit = $_GET['length'];
        $offset = $_GET['start'];
        $search = $_GET['search']['value'];
        $order = $_GET['order'][0]['column'];
        $type = $_GET['order'][0]['dir'];

        $client_owner = $_GET['client_owner'];
        $client_company = $_GET['client_company'];
        $client_contact_point = $_GET['client_contact_point'];
        $client_cat = $_GET['client_cat'];
        $client_status = $_GET['client_status'];
        $client_city = $_GET['client_city'];
        $client_industry = $_GET['client_industry'];
        
        $user =  \Auth::user();
        $all_perm = $user->can('display-client');
        $userwise_perm = $user->can('display-account-manager-wise-client');
        $edit_perm = $user->can('client-edit');
        $delete_perm = $user->can('client-delete');
        $category_perm = $user->can('display-client-category-in-client-list');

        if($all_perm) {

            $order_column_name = self::getOrderColumnName($order);
            $client_res = ClientBasicinfo::getAllClients(1,$user->id,$limit,$offset,$search,$order_column_name,$type,$client_owner,$client_company,$client_contact_point,$client_cat,$client_status,$client_city,$client_industry);
            
            $count = ClientBasicinfo::getAllClientsCount(1,$user->id,$search,$client_owner,$client_company,$client_contact_point,$client_cat,$client_status,$client_city,$client_industry);
        }
        else if($userwise_perm) {

            $order_column_name = self::getOrderColumnName($order);
            $client_res = ClientBasicinfo::getAllClients(0,$user->id,$limit,$offset,$search,$order_column_name,$type,$client_owner,$client_company,$client_contact_point,$client_cat,$client_status,$client_city,$client_industry);
            $count = ClientBasicinfo::getAllClientsCount(0,$user->id,$search,$client_owner,$client_company,$client_contact_point,$client_cat,$client_status,$client_city,$client_industry);
        }


        
        $recruitment = getenv('RECRUITMENT');
        $hr_advisory = getenv('HRADVISORY');
        $management = getenv('MANAGEMENT');
        $type_array = array($recruitment,$hr_advisory,$management);

        $users_array = User::getAllUsers(NULL,'Yes');
        $account_manager = array();

        if(isset($users_array) && sizeof($users_array) > 0) {

            foreach ($users_array as $k1 => $v1) {
                               
                $user_details = User::getAllDetailsByUserID($k1);

                if($user_details->type == '2') {
                    if($user_details->hr_adv_recruitemnt == 'Yes') {
                        $account_manager[$k1] = $v1;
                    }
                }
                else {
                    $account_manager[$k1] = $v1;
                }    
            }
        }

        $account_manager[0] = 'Yet to Assign';

        $clients = array();
        $i = 0;$j = 0;

        foreach ($client_res as $key => $value) {

            $action = '';
            $action .= '<a title="Show" class="fa fa-circle"  href="'.route('client.show',$value['id']).'" style="margin:2px;"></a>'; 
        
            if($edit_perm) {

                $action .= '<a title="Edit" class="fa fa-edit" href="'.route('client.edit',$value['id']).'" style="margin:2px;"></a>';
            }
            if($delete_perm) {

                $delete_view = \View::make('adminlte::partials.deleteModalNew', ['data' => $value, 'name' => 'client','display_name'=>'Client']);
                $delete = $delete_view->render();
                $action .= $delete;

                if(isset($value['url']) && $value['url'] != '') {
                    $action .= '<a target="_blank" href="'.$value['url'].'"><i  class="fa fa-fw fa-download"></i></a>';
                }
            }
            if($all_perm) {

                $account_manager_view = \View::make('adminlte::partials.client_account_manager', ['data' => $value, 'name' => 'client', 'account_manager' => $account_manager, 'source' => '']);
                $account = $account_manager_view->render();
                $action .= $account;
            }

            if($userwise_perm) {

                $secondline_account_manager_view = \View::make('adminlte::partials.secondline_account_manager', ['data' => $value, 'name' => 'client', 'account_manager' => $account_manager, 'source' => '']);
                $secondline_account = $secondline_account_manager_view->render();
                $action .= $secondline_account;

            }
            if($all_perm || $value['client_owner']) {

                $action .= '<a title="Remarks" class="fa fa-plus"  href="'.route('client.remarks',$value['id']).'" style="margin:2px;"></a>';
            }

            if($all_perm || $value['client_owner'] || $value['second_line_client_owner']) {

                // Client Hiring Report
                $hiring_report = \View::make('adminlte::partials.client_hiring_report', ['data' => $value,'page' => 'Main','source' => '']);
                $report = $hiring_report->render();
                $action .= $report;
            }
            if($all_perm || $value['client_owner']) {

                $days_array = ClientTimeline::getTimelineDetailsByClientId($value['id']);

                $timeline_view = \View::make('adminlte::partials.client_timeline_view', ['data' => $value,'days_array' => $days_array]);
                $timeline = $timeline_view->render();
                $action .= $timeline;
            }

            $checkbox = '<input type=checkbox name=client value='.$value['id'].' class=others_client id='.$value['id'].'/>';
            $company_name = '<a style="white-space: pre-wrap; word-wrap: break-word; color:black; text-decoration:none;">'.$value['name'].'</a>';
            $contact_point = '<a style="white-space: pre-wrap; word-wrap: break-word; color:black; text-decoration:none;">'.$value['hr_name'].'</a>';
            $industry_name = '<a style="white-space: pre-wrap; word-wrap: break-word; color:black; text-decoration:none;">'.$value['industry_name'].'</a>';

            if($value['status'] == 'Active')
                $client_status = '<span class="label label-sm label-success">'.$value['status'].'</span></td>';
            else if($value['status'] == 'Passive')
                $client_status = '<span class="label label-sm label-danger">'.$value['status'].'</span></td>';
            else if($value['status'] == 'Leaders')
                $client_status = '<span class="label label-sm label-primary">'.$value['status'].'</span></td>';
            else if($value['status'] == 'Forbid')
                $client_status = '<span class="label label-sm label-default">'.$value['status'].'</span>';
            else if($value['status'] == 'Left')
                $client_status = '<span class="label label-sm label-info">'.$value['status'].'</span>';

            $client_category = $value['category'];

            if(isset($value['second_line_am_name']) && $value['second_line_am_name'] != '') {

                $am_name = $value['am_name']." | ".$value['second_line_am_name'];
            }
            else {
                $am_name = $value['am_name'];
            }

            if($category_perm) {

                $data = array(++$j,$checkbox,$action,$am_name,$company_name,$contact_point,$client_category,$client_status,$value['address'],$industry_name,$value['second_line_am']);
            }
            else {

                $data = array(++$j,$checkbox,$action,$am_name,$company_name,$contact_point,$client_status,$value['address'],$industry_name,$value['second_line_am']);
            }

            $clients[$i] = $data;
            $i++;
        }

        $json_data = array(
            'draw' => intval($draw),
            'recordsTotal' => intval($count),
            'recordsFiltered' => intval($count),
            "data" => $clients
        );

        echo json_encode($json_data);exit;
    }

    public function getAllClientsBySource($source) {

        $user =  \Auth::user();
        $all_perm = $user->can('display-client');
        $userwise_perm = $user->can('display-account-manager-wise-client');

        if($all_perm) {
            $clients = ClientBasicinfo::getAllClients(1,$user->id,0,0,0,0,'','','','','','','','');
            $count = '0';
        }
        else if($userwise_perm) {
            $clients = ClientBasicinfo::getAllClients(0,$user->id,0,0,0,0,'','','','','','','','');
            $count = '0';
        }

        $i = 0;
        $active = 0;
        $passive = 0;
        $leaders = 0;
        $forbid = 0;
        $left = 0;
        $para_cat = 0;
        $mode_cat = 0;
        $std_cat = 0;

        foreach($clients as $client) {

            if($client['status'] == 'Active' ) {
                $active++;
            }
            else if ($client['status'] == 'Passive') {
                $passive++;
            }
            else if($client['status'] == 'Leaders' ) {
                $leaders++;
            }
            else if($client['status'] == 'Forbid' ) {
                $forbid++;
            }
            else if($client['status'] == 'Left' ) {
                $left++;
            }

            if($client['category'] == 'Paramount') {
                $para_cat++;
            }
            else if($client['category'] == 'Moderate') {
                $mode_cat++;
            }
            else if($client['category'] == 'Standard') {
                $std_cat++;
            }
        }

        $email_template_names = EmailTemplate::getAllEmailTemplateNames($user->id);

        $users_array = User::getAllUsers(NULL,'Yes');
        $all_account_manager = array();

        if(isset($users_array) && sizeof($users_array) > 0) {

            foreach ($users_array as $k1 => $v1) {
                               
                $user_details = User::getAllDetailsByUserID($k1);

                if($user_details->type == '2') {
                    if($user_details->hr_adv_recruitemnt == 'Yes') {
                        $all_account_manager[$k1] = $v1;
                    }
                }
                else {
                    $all_account_manager[$k1] = $v1;
                }    
            }
        }

        $all_account_manager[0] = 'Yet to Assign';

        // Get Client Status

        $status = ClientBasicinfo::getAllStatus();
        $status_id = 1;

        // Get Master Search Field List
        $field_list = ClientBasicinfo::getFieldsList();

        // Get Managed By Person List
        $users = array();
        
        if(isset($users_array) && sizeof($users_array) > 0) {

            $users[''] = 'Select User';

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

        // Get Category List
        $category_list = ClientBasicinfo::getCategory();

        // Get Status List
        $all_status[''] = 'Select Status';
        $all_status[1] = 'Active';
        $all_status[0] = 'Passive';
        $all_status[2] = 'Leaders';
        $all_status[4] = 'Left';

        // Get Client Industry

        $industry_res = Industry::orderBy('name','ASC')->get();
        $industry = array();

        if(sizeof($industry_res) > 0) {

            $industry[''] = 'Select Industry';

            foreach($industry_res as $r) {
                $industry[$r->id] = $r->name;
            }
        }

        return view('adminlte::client.clienttypeindex',compact('active','passive','leaders','forbid','left','para_cat','mode_cat','std_cat','source','count','email_template_names','all_account_manager','status','status_id','field_list','users','category_list','all_status','industry'));
    }

    public function getAllClientsDetailsByType() {

        $draw = $_GET['draw'];
        $limit = $_GET['length'];
        $offset = $_GET['start'];
        $search = $_GET['search']['value'];
        $order = $_GET['order'][0]['column'];
        $type = $_GET['order'][0]['dir'];
        $source = $_GET['source'];

        $client_owner = $_GET['client_owner'];
        $client_company = $_GET['client_company'];
        $client_contact_point = $_GET['client_contact_point'];
        $client_cat = $_GET['client_cat'];
        $client_status = $_GET['client_status'];
        $client_city = $_GET['client_city'];
        $client_industry = $_GET['client_industry'];

        $user =  \Auth::user();
        $all_perm = $user->can('display-client');
        $userwise_perm = $user->can('display-account-manager-wise-client');
        $edit_perm = $user->can('client-edit');
        $delete_perm = $user->can('client-delete');
        $category_perm = $user->can('display-client-category-in-client-list');

        // Three Category Permissions
        $para_perm = $user->can('display-paramount-client-list');
        $stan_perm = $user->can('display-standard-client-list');
        $mode_perm = $user->can('display-moderate-client-list');

        // Get Active Clients
        if($source == 'Active') {

            if($all_perm) {

                $order_column_name = self::getOrderColumnName($order);
                $client_res = ClientBasicinfo::getClientsByType(1,$user->id,$limit,$offset,$search,$order_column_name,$type,1,'',$client_owner,$client_company,$client_contact_point,$client_cat,$client_status,$client_city,$client_industry);
                $count = ClientBasicinfo::getClientsByTypeCount(1,$user->id,$search,1,'',$client_owner,$client_company,$client_contact_point,$client_cat,$client_status,$client_city,$client_industry);

                $client_res_array = ClientBasicinfo::getClientsByType(1,$user->id,0,0,'',$order_column_name,$type,1,'',$client_owner,$client_company,$client_contact_point,$client_cat,$client_status,$client_city,$client_industry);
            }
            else if($userwise_perm) {

                $order_column_name = self::getOrderColumnName($order);
                $client_res = ClientBasicinfo::getClientsByType(0,$user->id,$limit,$offset,$search,$order_column_name,$type,1,'',$client_owner,$client_company,$client_contact_point,$client_cat,$client_status,$client_city,$client_industry);
                $count = ClientBasicinfo::getClientsByTypeCount(0,$user->id,$search,1,'',$client_owner,$client_company,$client_contact_point,$client_cat,$client_status,$client_city,$client_industry);

                $client_res_array = ClientBasicinfo::getClientsByType(0,$user->id,0,0,'',$order_column_name,$type,1,'',$client_owner,$client_company,$client_contact_point,$client_cat,$client_status,$client_city,$client_industry);
            }
        }

        // Get Passive Clients
        if($source == 'Passive') {

            if($all_perm) {

                $order_column_name = self::getOrderColumnName($order);
                $client_res = ClientBasicinfo::getClientsByType(1,$user->id,$limit,$offset,$search,$order_column_name,$type,0,'',$client_owner,$client_company,$client_contact_point,$client_cat,$client_status,$client_city,$client_industry);
                $count = ClientBasicinfo::getClientsByTypeCount(1,$user->id,$search,0,'',$client_owner,$client_company,$client_contact_point,$client_cat,$client_status,$client_city,$client_industry);

                $client_res_array = ClientBasicinfo::getClientsByType(1,$user->id,0,0,'',$order_column_name,$type,0,'',$client_owner,$client_company,$client_contact_point,$client_cat,$client_status,$client_city,$client_industry);
            }
            else if($userwise_perm) {

                $order_column_name = self::getOrderColumnName($order);
                $client_res = ClientBasicinfo::getClientsByType(0,$user->id,$limit,$offset,$search,$order_column_name,$type,0,'',$client_owner,$client_company,$client_contact_point,$client_cat,$client_status,$client_city,$client_industry);
                $count = ClientBasicinfo::getClientsByTypeCount(0,$user->id,$search,0,'',$client_owner,$client_company,$client_contact_point,$client_cat,$client_status,$client_city,$client_industry);

                $client_res_array = ClientBasicinfo::getClientsByType(0,$user->id,0,0,'',$order_column_name,$type,0,'',$client_owner,$client_company,$client_contact_point,$client_cat,$client_status,$client_city,$client_industry);
            }
        }

        // Get Leaders Clients
        if($source == 'Leaders') {

            if($all_perm) {

                $order_column_name = self::getOrderColumnName($order);
                $client_res = ClientBasicinfo::getClientsByType(1,$user->id,$limit,$offset,$search,$order_column_name,$type,2,'',$client_owner,$client_company,$client_contact_point,$client_cat,$client_status,$client_city,$client_industry);
                $count = ClientBasicinfo::getClientsByTypeCount(1,$user->id,$search,2,'',$client_owner,$client_company,$client_contact_point,$client_cat,$client_status,$client_city,$client_industry);

                $client_res_array = ClientBasicinfo::getClientsByType(1,$user->id,0,0,'',$order_column_name,$type,2,'',$client_owner,$client_company,$client_contact_point,$client_cat,$client_status,$client_city,$client_industry);
            }
            else if($userwise_perm) {

                $order_column_name = self::getOrderColumnName($order);
                $client_res = ClientBasicinfo::getClientsByType(0,$user->id,$limit,$offset,$search,$order_column_name,$type,2,'',$client_owner,$client_company,$client_contact_point,$client_cat,$client_status,$client_city,$client_industry);
                $count = ClientBasicinfo::getClientsByTypeCount(0,$user->id,$search,2,'',$client_owner,$client_company,$client_contact_point,$client_cat,$client_status,$client_city,$client_industry);

                $client_res_array = ClientBasicinfo::getClientsByType(0,$user->id,0,0,'',$order_column_name,$type,2,'',$client_owner,$client_company,$client_contact_point,$client_cat,$client_status,$client_city,$client_industry);
            }
        }

        // Get Left Clients
        if($source == 'Left') {

            if($all_perm) {

                $order_column_name = self::getOrderColumnName($order);
                $client_res = ClientBasicinfo::getClientsByType(1,$user->id,$limit,$offset,$search,$order_column_name,$type,4,'',$client_owner,$client_company,$client_contact_point,$client_cat,$client_status,$client_city,$client_industry);
                $count = ClientBasicinfo::getClientsByTypeCount(1,$user->id,$search,4,'',$client_owner,$client_company,$client_contact_point,$client_cat,$client_status,$client_city,$client_industry);

                $client_res_array = ClientBasicinfo::getClientsByType(1,$user->id,0,0,'',$order_column_name,$type,4,'',$client_owner,$client_company,$client_contact_point,$client_cat,$client_status,$client_city,$client_industry);
            }
            else if($userwise_perm) {

                $order_column_name = self::getOrderColumnName($order);
                $client_res = ClientBasicinfo::getClientsByType(0,$user->id,$limit,$offset,$search,$order_column_name,$type,4,'',$client_owner,$client_company,$client_contact_point,$client_cat,$client_status,$client_city,$client_industry);
                $count = ClientBasicinfo::getClientsByTypeCount(0,$user->id,$search,4,'',$client_owner,$client_company,$client_contact_point,$client_cat,$client_status,$client_city,$client_industry);

                $client_res_array = ClientBasicinfo::getClientsByType(0,$user->id,0,0,'',$order_column_name,$type,4,'',$client_owner,$client_company,$client_contact_point,$client_cat,$client_status,$client_city,$client_industry);
            }
        }

        // Get Forbid Clients
        if($source == 'Forbid') {

            if($all_perm) {

                $order_column_name = self::getOrderColumnNameByAM($order);
                $client_res = ClientBasicinfo::getClientsByType(1,$user->id,$limit,$offset,$search,$order_column_name,$type,3,'',$client_owner,$client_company,$client_contact_point,$client_cat,$client_status,$client_city,$client_industry);
                $count = ClientBasicinfo::getClientsByTypeCount(1,$user->id,$search,3,'',$client_owner,$client_company,$client_contact_point,$client_cat,$client_status,$client_city,$client_industry);

                $client_res_array = ClientBasicinfo::getClientsByType(1,$user->id,0,0,'',$order_column_name,$type,3,'',$client_owner,$client_company,$client_contact_point,$client_cat,$client_status,$client_city,$client_industry);
            }
            else if($userwise_perm) {

                $order_column_name = self::getOrderColumnNameByAM($order);
                $client_res = ClientBasicinfo::getClientsByType(0,$user->id,$limit,$offset,$search,$order_column_name,$type,3,'',$client_owner,$client_company,$client_contact_point,$client_cat,$client_status,$client_city,$client_industry);
                $count = ClientBasicinfo::getClientsByTypeCount(0,$user->id,$search,3,'',$client_owner,$client_company,$client_contact_point,$client_cat,$client_status,$client_city,$client_industry);

                $client_res_array = ClientBasicinfo::getClientsByType(0,$user->id,0,0,'',$order_column_name,$type,3,'',$client_owner,$client_company,$client_contact_point,$client_cat,$client_status,$client_city,$client_industry);
            }
        }

        // Get Paramount Clients
        if($source == 'Paramount') {

            if($all_perm && $para_perm) {

                $order_column_name = self::getOrderColumnName($order);
                $client_res = ClientBasicinfo::getClientsByType(1,$user->id,$limit,$offset,$search,$order_column_name,$type,NULL,$source,$client_owner,$client_company,$client_contact_point,$client_cat,$client_status,$client_city,$client_industry);
                $count = ClientBasicinfo::getClientsByTypeCount(1,$user->id,$search,NULL,$source,$client_owner,$client_company,$client_contact_point,$client_cat,$client_status,$client_city,$client_industry);

                $client_res_array = ClientBasicinfo::getClientsByType(1,$user->id,0,0,'',$order_column_name,$type,NULL,$source,$client_owner,$client_company,$client_contact_point,$client_cat,$client_status,$client_city,$client_industry);
            }
            else if($userwise_perm && $para_perm) {

                $order_column_name = self::getOrderColumnName($order);
                $client_res = ClientBasicinfo::getClientsByType(0,$user->id,$limit,$offset,$search,$order_column_name,$type,NULL,$source,$client_owner,$client_company,$client_contact_point,$client_cat,$client_status,$client_city,$client_industry);
                $count = ClientBasicinfo::getClientsByTypeCount(0,$user->id,$search,NULL,$source,$client_owner,$client_company,$client_contact_point,$client_cat,$client_status,$client_city,$client_industry);

                $client_res_array = ClientBasicinfo::getClientsByType(0,$user->id,0,0,'',$order_column_name,$type,NULL,$source,$client_owner,$client_company,$client_contact_point,$client_cat,$client_status,$client_city,$client_industry);
            }
        }

        // Get Moderate Clients
        if($source == 'Moderate') {

            if($all_perm && $mode_perm) {

                $order_column_name = self::getOrderColumnName($order);
                $client_res = ClientBasicinfo::getClientsByType(1,$user->id,$limit,$offset,$search,$order_column_name,$type,NULL,$source,$client_owner,$client_company,$client_contact_point,$client_cat,$client_status,$client_city,$client_industry);
                $count = ClientBasicinfo::getClientsByTypeCount(1,$user->id,$search,NULL,$source,$client_owner,$client_company,$client_contact_point,$client_cat,$client_status,$client_city,$client_industry);

                $client_res_array = ClientBasicinfo::getClientsByType(1,$user->id,0,0,'',$order_column_name,$type,NULL,$source,$client_owner,$client_company,$client_contact_point,$client_cat,$client_status,$client_city,$client_industry);
            }
            else if($userwise_perm && $mode_perm) {

                $order_column_name = self::getOrderColumnName($order);
                $client_res = ClientBasicinfo::getClientsByType(0,$user->id,$limit,$offset,$search,$order_column_name,$type,NULL,$source,$client_owner,$client_company,$client_contact_point,$client_cat,$client_status,$client_city,$client_industry);
                $count = ClientBasicinfo::getClientsByTypeCount(0,$user->id,$search,NULL,$source,$client_owner,$client_company,$client_contact_point,$client_cat,$client_status,$client_city,$client_industry);

                $client_res_array = ClientBasicinfo::getClientsByType(0,$user->id,0,0,'',$order_column_name,$type,NULL,$source,$client_owner,$client_company,$client_contact_point,$client_cat,$client_status,$client_city,$client_industry);
            }
        }

        // Get Standard Clients
        if($source == 'Standard') {

            if($all_perm && $stan_perm) {

                $order_column_name = self::getOrderColumnName($order);
                $client_res = ClientBasicinfo::getClientsByType(1,$user->id,$limit,$offset,$search,$order_column_name,$type,NULL,$source,$client_owner,$client_company,$client_contact_point,$client_cat,$client_status,$client_city,$client_industry);
                $count = ClientBasicinfo::getClientsByTypeCount(1,$user->id,$search,NULL,$source,$client_owner,$client_company,$client_contact_point,$client_cat,$client_status,$client_city,$client_industry);

                $client_res_array = ClientBasicinfo::getClientsByType(1,$user->id,0,0,'',$order_column_name,$type,NULL,$source,$client_owner,$client_company,$client_contact_point,$client_cat,$client_status,$client_city,$client_industry);
            }
            else if($userwise_perm && $stan_perm) {

                $order_column_name = self::getOrderColumnName($order);
                $client_res = ClientBasicinfo::getClientsByType(0,$user->id,$limit,$offset,$search,$order_column_name,$type,NULL,$source,$client_owner,$client_company,$client_contact_point,$client_cat,$client_status,$client_city,$client_industry);
                $count = ClientBasicinfo::getClientsByTypeCount(0,$user->id,$search,NULL,$source,$client_owner,$client_company,$client_contact_point,$client_cat,$client_status,$client_city,$client_industry);

                $client_res_array = ClientBasicinfo::getClientsByType(0,$user->id,0,0,'',$order_column_name,$type,NULL,$source,$client_owner,$client_company,$client_contact_point,$client_cat,$client_status,$client_city,$client_industry);
            }
        }

        $recruitment = getenv('RECRUITMENT');
        $hr_advisory = getenv('HRADVISORY');
        $management = getenv('MANAGEMENT');
        $type_array = array($recruitment,$hr_advisory,$management);

        $users_array = User::getAllUsers(NULL,'Yes');
        $account_manager = array();

        if(isset($users_array) && sizeof($users_array) > 0) {

            foreach ($users_array as $k1 => $v1) {
                               
                $user_details = User::getAllDetailsByUserID($k1);

                if($user_details->type == '2') {
                    if($user_details->hr_adv_recruitemnt == 'Yes') {
                        $account_manager[$k1] = $v1;
                    }
                }
                else {
                    $account_manager[$k1] = $v1;
                }    
            }
        }

        $account_manager[0] = 'Yet to Assign';

        $clients = array();
        $i = 0;$j=0;

        foreach ($client_res as $key => $value) {

            $action = '';
            $action .= '<a title="Show" class="fa fa-circle"  href="'.route('client.show',$value['id']).'" style="margin:2px;"></a>'; 
           
            if($edit_perm) {

                $action .= '<a title="Edit" class="fa fa-edit" href="'.route('client.edit',$value['id']).'" style="margin:2px;"></a>';
            }
            if($delete_perm) {

                $delete_view = \View::make('adminlte::partials.deleteModalNew', ['data' => $value, 'name' => 'client','display_name'=>'Client']);
                $delete = $delete_view->render();
                $action .= $delete;

                if(isset($value['url']) && $value['url'] != '') {
                    $action .= '<a target="_blank" href="'.$value['url'].'"><i  class="fa fa-fw fa-download"></i></a>';
                }
            }
            if($all_perm) {

                $account_manager_view = \View::make('adminlte::partials.client_account_manager', ['data' => $value, 'name' => 'client','display_name'=>'More Information', 'account_manager' => $account_manager, 'source' => $source]);
                $account = $account_manager_view->render();
                $action .= $account;
            }

            if($userwise_perm) {

                $secondline_account_manager_view = \View::make('adminlte::partials.secondline_account_manager', ['data' => $value, 'name' => 'client', 'account_manager' => $account_manager, 'source' => $source]);
                $secondline_account = $secondline_account_manager_view->render();
                $action .= $secondline_account;

            }

            if($all_perm || $value['client_owner']) {

                $action .= '<a title="Remarks" class="fa fa-plus"  href="'.route('client.remarks',$value['id']).'" style="margin:2px;"></a>';
            }

            if($all_perm || $value['client_owner'] || $value['second_line_client_owner']) {

                if($source == 'Forbid') {
                }
                else {

                    // Client Hiring Report
                    $hiring_report = \View::make('adminlte::partials.client_hiring_report', ['data' => $value,'page' => 'Type','source' => $source]);
                    $report = $hiring_report->render();
                    $action .= $report;
                }
            }

            if($all_perm || $value['client_owner']) {
                
                $days_array = ClientTimeline::getTimelineDetailsByClientId($value['id']);

                $timeline_view = \View::make('adminlte::partials.client_timeline_view', ['data' => $value,'days_array' => $days_array]);
                $timeline = $timeline_view->render();
                $action .= $timeline;
            }

            $checkbox = '<input type=checkbox name=client value='.$value['id'].' class=others_client id='.$value['id'].'/>';
            $company_name = '<a style="white-space: pre-wrap; word-wrap: break-word; color:black; text-decoration:none;">'.$value['name'].'</a>';
            $contact_point = '<a style="white-space: pre-wrap; word-wrap: break-word; color:black; text-decoration:none;">'.$value['hr_name'].'</a>';
            $industry_name = '<a style="white-space: pre-wrap; word-wrap: break-word; color:black; text-decoration:none;">'.$value['industry_name'].'</a>';

            if($value['status'] == 'Active')
                $client_status = '<span class="label label-sm label-success">'.$value['status'].'</span></td>';
            else if($value['status'] == 'Passive')
                $client_status = '<span class="label label-sm label-danger">'.$value['status'].'</span></td>';
            else if($value['status'] == 'Leaders')
                $client_status = '<span class="label label-sm label-primary">'.$value['status'].'</span></td>';
            else if($value['status'] == 'Forbid')
                $client_status = '<span class="label label-sm label-default">'.$value['status'].'</span>';
            else if($value['status'] == 'Left')
                $client_status = '<span class="label label-sm label-info">'.$value['status'].'</span>';

            $client_category = $value['category'];

            if(isset($value['second_line_am_name']) && $value['second_line_am_name'] != '') {

                $am_name = $value['am_name']." | ".$value['second_line_am_name'];
            }
            else {
                $am_name = $value['am_name'];
            }

            if($category_perm) {

                if($source == 'Forbid') {
                    $data = array(++$j,$action,$am_name,$company_name,$contact_point,$client_category,$client_status,$value['address'],$industry_name,$value['second_line_am']);
                }
                else {
                    $data = array(++$j,$checkbox,$action,$am_name,$company_name,$contact_point,$client_category,$client_status,$value['address'],$industry_name,$value['second_line_am']);
                }
            }
            else {

                if($source == 'Forbid') {
                    $data = array(++$j,$action,$am_name,$company_name,$contact_point,$client_status,$value['address'],$industry_name,$value['second_line_am']);
                }
                else {
                    $data = array(++$j,$checkbox,$action,$am_name,$company_name,$contact_point,$client_status,$value['address'],$industry_name,$value['second_line_am']);
                }
            }

            $clients[$i] = $data;
            $i++;
        }

        $active = 0;
        $passive = 0;
        $leaders = 0;
        $left = 0;
        $para_cat = 0;
        $mode_cat = 0;
        $std_cat = 0;

        foreach($client_res_array as $client) {

            if($client['status'] == 'Active') {
                $active++;
            }
            else if ($client['status'] == 'Passive') {
                $passive++;
            }
            else if($client['status'] == 'Leaders') {
                $leaders++;
            }
            else if($client['status'] == 'Left') {
                $left++;
            }

            if($client['category'] == 'Paramount') {
                $para_cat++;
            }
            else if($client['category'] == 'Moderate') {
                $mode_cat++;
            }
            else if($client['category'] == 'Standard') {
                $std_cat++;
            }
        }

        $json_data = array(
            'draw' => intval($draw),
            'recordsTotal' => intval($count),
            'recordsFiltered' => intval($count),
            "data" => $clients,
            "active_count" => $active,
            "passive_count" => $passive,
            "leaders_count" => $leaders,
            "left_count" => $left,
            "paramount_count" => $para_cat,
            "moderate_count" => $mode_cat,
            "standard_count" => $std_cat
        );

        echo json_encode($json_data);exit;
    }

    // Forbid client listing page function
    public function getForbidClient() {

        $user =  \Auth::user();
        $all_perm = $user->can('display-client');
        $userwise_perm = $user->can('display-account-manager-wise-client');

        if($all_perm) {

            $count = ClientBasicinfo::getClientsByTypeCount(1,$user->id,'',3,'','','','','','','','');
        }
        else if($userwise_perm) {

            $count = ClientBasicinfo::getClientsByTypeCount(0,$user->id,'',3,'','','','','','','','');
        }

        $source = 'Forbid';
        return view('adminlte::client.forbidclients',compact('count','source'));
    }

    public function create() {

        $co_prefix = ClientBasicinfo::getcoprefix();
        $co_category = '';

        $client_cat = ClientBasicinfo::getCategory();
        $client_category = '';

        $client_status_key = ClientBasicinfo::getStatus();
        $client_status = 1;

        // For Superadmin,Strategy,Manager Users
        $client_all_status_key = ClientBasicinfo::getAllStatus();
        $client_all_status = 1;

        $generate_lead = '1';

        $industry_res = Industry::orderBy('name','ASC')->get();
        $industry = array();

        if(sizeof($industry_res) > 0) {
            foreach($industry_res as $r) {
                $industry[$r->id] = $r->name;
            }
        }
        $industry_id = '';

        $user = \Auth::user();
        $user_id = $user->id;

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
        
        // User Account Manager access check
        $user_acc_manager = \Auth::user()->account_manager;
        if ($user_acc_manager == 'No') {
            return view('errors.clientpermission');
        }

        
        $percentage_charged_below = '8.33';
        $percentage_charged_above = '8.33';

        $action = "add";

        return view('adminlte::client.create',compact('client_status','client_status_key','action','industry','users','user_id','generate_lead','industry_id','co_prefix','co_category','client_cat','client_category','percentage_charged_below','percentage_charged_above','client_all_status_key','client_all_status'));
    }

    public function postClientNames() {

        $client_ids = $_GET['client_ids'];
        $client_ids_array = explode(",",$client_ids);
        $client = ClientBasicinfo::getClientInfo($client_ids);

        echo json_encode($client);exit;
    }

    public function edit($id) {

        $generate_lead = '1';

        $co_prefix = ClientBasicinfo::getcoprefix();
        $client_cat = ClientBasicinfo::getCategory();
        $client_status_key = ClientBasicinfo::getStatus();

        // For Superadmin,Strategy,Manager Users
        $client_all_status_key = ClientBasicinfo::getAllStatus();

        $user = \Auth::user();
        $user_id = $user->id;
        $all_perm = $user->can('display-client');

        $industry_res = Industry::orderBy('name','ASC')->get();
        $industry = array();

        if(sizeof($industry_res)>0) {

            foreach($industry_res as $r) {
                $industry[$r->id] = $r->name;
            }
        }

        $client = array();
        $client_basicinfo  = \DB::table('client_basicinfo')
        ->leftjoin('users', 'users.id', '=', 'client_basicinfo.account_manager_id')
        ->leftjoin('industry', 'industry.id', '=', 'client_basicinfo.industry_id')
        ->select('client_basicinfo.*', 'users.name as am_name','users.id as am_id','industry.name as ind_name')->where('client_basicinfo.id','=',$id)->first();

        if(isset($client_basicinfo) && $client_basicinfo != '') {

            if($all_perm || ($client_basicinfo->am_id == $user_id) || ($client_basicinfo->second_line_am == $user_id)) {

                $client['name'] = $client_basicinfo->name;
                $client['display_name'] = $client_basicinfo->display_name;
                $client['source'] = $client_basicinfo->source;
                $client['mobile'] = $client_basicinfo->mobile;
                $client['other_number'] = $client_basicinfo->other_number;
                $client['am_name'] = $client_basicinfo->am_name;
                $client['mail'] = $client_basicinfo->mail;
                $client['s_email'] = $client_basicinfo->s_email;
                $client['ind_name'] = $client_basicinfo->ind_name;
                $client['website'] = $client_basicinfo->website;
                $client['description'] = $client_basicinfo->description;
                $client['gst_no'] = $client_basicinfo->gst_no;
                $client['contact_point'] = $client_basicinfo->coordinator_name;
                $client['tan'] = $client_basicinfo->tan;
                $client['percentage_charged_below'] = $client_basicinfo->percentage_charged_below;
                $client['percentage_charged_above'] = $client_basicinfo->percentage_charged_above;

                $client_status = $client_basicinfo->status;
                $client_all_status = $client_basicinfo->status;
                $client_category = $client_basicinfo->category;
                $co_category = $client_basicinfo->coordinator_prefix;
                $user_id = $client_basicinfo->account_manager_id;
                $industry_id = $client_basicinfo->industry_id;
                $percentage_charged_below = $client_basicinfo->percentage_charged_below;
                $percentage_charged_above = $client_basicinfo->percentage_charged_above;
                $second_line_am = $client_basicinfo->second_line_am;
            }
            else {
                return view('errors.403');
            }
        }

        $client['id'] = $id;

        $client_address = \DB::table('client_address')->where('client_id','=',$id)->first();

        if(isset($client_address) && $client_address != '') {

            $client['billing_country'] = $client_address->billing_country;
            $client['billing_state'] = $client_address->billing_state;
            $client['billing_street1'] = $client_address->billing_street1;
            $client['billing_street2'] = $client_address->billing_street2;
            $client['billing_code'] = $client_address->billing_code;
            $client['billing_city'] = $client_address->billing_city;
            $client['shipping_country'] = $client_address->shipping_country;
            $client['shipping_state'] = $client_address->shipping_state;
            $client['shipping_street1'] = $client_address->shipping_street1;
            $client['shipping_street2'] = $client_address->shipping_street2;
            $client['shipping_code'] = $client_address->shipping_code;
            $client['shipping_city'] = $client_address->shipping_city;
            $client['client_address_id'] = $client_address->id;
        }

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

        $action = "edit";

        $client_basicinfo_model = new ClientBasicinfo();
        $client_upload_type = $client_basicinfo_model->client_upload_type;

        $i = 0;
        $client['doc'] = array();
        $client_doc = \DB::table('client_doc')
        ->join('users', 'users.id', '=', 'client_doc.uploaded_by')
        ->select('client_doc.*', 'users.name as upload_name')->where('client_id','=',$id)->get();

        $utils = new Utils();

        foreach ($client_doc as $key=>$value) {

            $client['doc'][$i]['name'] = $value->name;
            $client['doc'][$i]['id'] = $value->id;
            $client['doc'][$i]['url'] = "../".$value->file;
            $client['doc'][$i]['category'] = $value->category;
            $client['doc'][$i]['uploaded_by'] = $value->upload_name;
            $client['doc'][$i]['size'] = $utils->formatSizeUnits($value->size);
            $i++;

            if(array_search($value->category, $client_upload_type)) {
                unset($client_upload_type[array_search($value->category, $client_upload_type)]);
            }
        }

        $client_upload_type['Others'] = 'Others';

        return view('adminlte::client.edit',compact('client_status_key','action','industry','client','users','user_id','generate_lead','industry_id','co_prefix','co_category','client_status','client_cat','client_category','percentage_charged_below','percentage_charged_above','client_all_status_key','client_all_status','client_upload_type','second_line_am'));
    }

    public function store(Request $request) {

        $this->validate($request, [
            'name' => 'required',
            'mail' => [
                'required','email',Rule::unique('client_basicinfo')->where(function($query) {
                  $query->where('delete_client', '=', '0');
              })
            ],
        ]);

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

        if(isset($input['percentage_charged_below']) && $input['percentage_charged_below']!= '') {
            $client_basic_info->percentage_charged_below = $input['percentage_charged_below'];
        }
        else {
            $client_basic_info->percentage_charged_below = '8.33';
        }
        
        if(isset($input['percentage_charged_above']) && $input['percentage_charged_above']!='' ) {
            $client_basic_info->percentage_charged_above = $input['percentage_charged_above'];
        }
        else {
            $client_basic_info->percentage_charged_above = '8.33';
        }
        
        $status = $input['status'];
        $client_basic_info->status = $status;

        // save passive date for passive client
        if($status == '0') {

            $today_date = date('Y-m-d'); 
            $client_basic_info->passive_date = $today_date;
        }

        $client_basic_info->account_manager_id = $input['account_manager'];
        $client_basic_info->industry_id = $input['industry_id'];
        $client_basic_info->about = $input['description'];

        if(isset($input['source']) && $input['source']!='')
            $client_basic_info->source = $input['source'];
        else
            $client_basic_info->source = '';

        if(isset($input['gst_no']) && $input['gst_no']!='')
            $client_basic_info->gst_no = $input['gst_no'];
        else
            $client_basic_info->gst_no = '';

        if(isset($input['tan']) && $input['tan']!='')
            $client_basic_info->tan = $input['tan'];
        else
            $client_basic_info->tan = '';

        $client_basic_info->coordinator_name = trim($input['contact_point']);
        $client_basic_info->coordinator_prefix = $input['co_category'];

        if(isset($input['client_category'])) {
            $client_basic_info->category = $input['client_category'];
        }
        else {
            $client_basic_info->category = '';
        }

        if(isset($input['second_line_am'])) {
            $client_basic_info->second_line_am = $input['second_line_am'];
        }
        else {
            $client_basic_info->second_line_am = '0';
        }

        $client_basic_info->created_at = time();
        $client_basic_info->updated_at = time();
        $client_basic_info->delete_client = 0;

        // Save Department Id for Different Dashboard
        $vibhuti_user_id = getenv('STRATEGYUSERID');

        if($user_id == $vibhuti_user_id) {

            $client_basic_info->department_id = 2;
        }
        else {

            $client_basic_info->department_id = 1;
        }

        if($client_basic_info->save()) {

            $client_id = $client_basic_info->id;
            $client_name = $client_basic_info->name;

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

            // Email Notification : data store in datebase

            $superadminemail = User::getUserEmailById($super_admin_userid);

            $module = "Client";
            $sender_name = $user_id;
            $to = $user_email;
            $subject = "New Client - " . $client_name . " - " . $input['billing_city'];
            $message = "<tr><td>" . $user_name . " added new Client </td></tr>";
            $module_id = $client_id;
            $cc = $superadminemail;

            event(new NotificationMail($module,$sender_name,$to,$subject,$message,$module_id,$cc));

            // Add Entry in Client Timeline.

            $client_timeline = new ClientTimeline();
            $client_timeline->user_id = $input['account_manager'];
            $client_timeline->client_id = $client_id;
            $client_timeline->save();

            return redirect()->route('client.index')->with('success','Client Added Successfully.');
        }
        else {
            return redirect('client/create')->withInput(Input::all())->withErrors($client_basic_info->errors());
        }
    }

    public function show($id) {
        
        $user = \Auth::user();
        $user_id = $user->id;
        $all_perm = $user->can('display-client');
        $userwise_perm = $user->can('display-account-manager-wise-client');

        $client_basicinfo_model = new ClientBasicinfo();
        $client_upload_type = $client_basicinfo_model->client_upload_type;

        $client = array();
        $client_basicinfo  = \DB::table('client_basicinfo')
            ->leftjoin('users', 'users.id', '=', 'client_basicinfo.account_manager_id')
            ->leftjoin('industry', 'industry.id', '=', 'client_basicinfo.industry_id')
            ->select('client_basicinfo.*', 'users.name as am_name','users.id as am_id', 'industry.name as ind_name')->where('client_basicinfo.id','=',$id)->first();

        $client['id'] = $id;
   
        if(isset($client_basicinfo) && $client_basicinfo != '') {

            if($all_perm || $userwise_perm ) {

                $client['name'] = $client_basicinfo->name;
                $client['source'] = $client_basicinfo->source;
                $client['fax'] = $client_basicinfo->fax;
                $client['mobile'] = $client_basicinfo->mobile;
                $client['other_number'] = $client_basicinfo->other_number;
                $client['am_name'] = $client_basicinfo->am_name;
                $client['mail'] = $client_basicinfo->mail;
                $client['s_email'] = $client_basicinfo->s_email;
                $client['ind_name'] = $client_basicinfo->ind_name;
                $client['website'] = $client_basicinfo->website;
                $client['description'] = $client_basicinfo->description;
                $client['gst_no'] = $client_basicinfo->gst_no;
                $client['coordinator_name'] = $client_basicinfo->coordinator_prefix. " " .$client_basicinfo->coordinator_name;
                $client['tan'] = $client_basicinfo->tan;
                $client['status'] = $client_basicinfo->status;
                $client['category'] = $client_basicinfo->category;
                $client['display_name'] = $client_basicinfo->display_name;

                if(isset($client['status'])) {

                    if($client['status'] == '1') {
                        $client['status'] = 'Active';
                    }
                    else if($client['status'] == '0') {
                        $client['status'] = 'Passive';
                    }
                    else if($client['status'] == '2') {
                        $client['status'] = 'Leaders';
                    }
                    else if($client['status'] == '3') {
                        $client['status'] = 'Forbid';
                    }
                    else if($client['status'] == '4') {
                        $client['status'] = 'Left';
                    }
                }

                $client['percentage_charged_below'] = $client_basicinfo->percentage_charged_below;
                $client['percentage_charged_above'] = $client_basicinfo->percentage_charged_above;

                if($client_basicinfo->am_id == $user->id) {
                    $client['client_owner'] = true;
                }
                else {
                    $client['client_owner'] = false;
                }
            }
           else {
               return view('errors.403');
           }
        }

        $client_address = \DB::table('client_address')->where('client_id','=',$id)->first();

        if(isset($client_address) && $client_address != '') {

            $client['billing_country'] = $client_address->billing_country;
            $client['billing_state'] = $client_address->billing_state;
            $client['billing_street'] = $client_address->billing_street1."\n".$client_address->billing_street2;
            $client['billing_code'] = $client_address->billing_code;
            $client['billing_city'] = $client_address->billing_city;
            $client['shipping_country'] = $client_address->shipping_country;
            $client['shipping_state'] = $client_address->shipping_state;
            $client['shipping_street'] = $client_address->shipping_street1."\n".$client_address->shipping_street2;
            $client['shipping_code'] = $client_address->shipping_code;
            $client['shipping_city'] = $client_address->shipping_city;
        }

        $i = 0;
        $client['doc'] = array();
        
        $client_doc = \DB::table('client_doc')
        ->join('users', 'users.id', '=', 'client_doc.uploaded_by')
        ->select('client_doc.*', 'users.name as upload_name')->where('client_id','=',$id)->get();

        $utils = new Utils();
        
        foreach ($client_doc as $key => $value) {

            $client['doc'][$i]['name'] = $value->name ;
            $client['doc'][$i]['id'] = $value->id;
            $client['doc'][$i]['url'] = "../".$value->file ;
            $client['doc'][$i]['category'] = $value->category ;
            $client['doc'][$i]['uploaded_by'] = $value->upload_name ;
            $client['doc'][$i]['size'] = $utils->formatSizeUnits($value->size);
            $i++;

            if(array_search($value->category, $client_upload_type))  {
                unset($client_upload_type[array_search($value->category, $client_upload_type)]);
            }
        }

        $client_upload_type['Others'] = 'Others';

        // For Display Last Five Comments
        $client_id = $id;
        $super_admin_userid = getenv('SUPERADMINUSERID');

        $client_get = ClientBasicinfo::find($client_id);
        $post = $client_get->post()->orderBy('created_at', 'desc')->limit(5)->get();

        $client_remarks = array();
        $client_remarks_edit = array();

        return view('adminlte::client.show',compact('client','client_upload_type','user_id','post','super_admin_userid','client_remarks','client_remarks_edit','client_id'));
    }

    public function attachmentsDestroy(Request $request,$docid) {

        $client_attach = \DB::table('client_doc')->select('client_doc.*')->where('id','=',$docid)
        ->first();

        $clientid = $client_attach->client_id;

        if(isset($client_attach)) {

            $path = "uploads/clients/".$client_attach->client_id . "/" . $client_attach->name;
            unlink($path);

            $client_doc = ClientDoc::where('id','=',$docid)->delete();
        }

        $type = $request->type;

        if($type == 'edit') {

            return redirect()->route('client.edit',[$clientid])->with('success','Attachment Deleted Successfully.');
        }
        else {

            return redirect()->route('client.show',[$clientid])->with('success','Attachment Deleted Successfully.');
        }
    }

    public function upload(Request $request) {

        $client_upload_type = $request->client_upload_type;
        $file = $request->file('file');
        $client_id = $request->id;
        $user_id = \Auth::user()->id;
        $type = $request->type;

        if (isset($file) && $file->isValid()) {

            $doc_name = $file->getClientOriginalName();
            $doc_filesize = filesize($file);

            $dir_name = "uploads/clients/".$client_id."/";
            $others_doc_key = "uploads/clients/".$client_id."/".$doc_name;

            if (!file_exists($dir_name)) {
                mkdir("uploads/clients/$client_id", 0777,true);
            }

            if(!$file->move($dir_name, $doc_name)) {
                return false;
            }
            else {

                $client_doc = new ClientDoc;
                $client_doc->client_id = $client_id;
                $client_doc->category = $client_upload_type;
                $client_doc->name = $doc_name;
                $client_doc->file = $others_doc_key;
                $client_doc->uploaded_by = $user_id;
                $client_doc->size = $doc_filesize;
                $client_doc->created_at = time();
                $client_doc->updated_at = time();
                $client_doc->save();
            }
        }

        if($type == 'show') {
            return redirect()->route('client.show',[$client_id])->with('success','Attachment Uploaded Successfully.');
        }
        else {
            return redirect()->route('client.edit',[$client_id])->with('success','Attachment Uploaded Successfully.');
        }
    }

    public function destroy($id) {

        $lead_res = \DB::table('client_basicinfo')->select('client_basicinfo.lead_id')
        ->where('id','=',$id)->first();

        if (isset($lead_res) && $lead_res !='') {
            $lead_id = $lead_res->lead_id;
        }

        if (isset($lead_id) && $lead_id !='') {
            $lead = Lead::find($lead_id);
            if (isset($lead) && $lead != '') {
                $lead->convert_client = 0;
                $lead->save();
            }
        }

        $res = ClientBasicinfo::checkAssociation($id);

        if($res) {

            $super_admin_userid = getenv('SUPERADMINUSERID');
            $superadminemail = User::getUserEmailById($super_admin_userid);

            /*$all_client_user_id = getenv('ALLCLIENTVISIBLEUSERID');
            $all_client_user_email = User::getUserEmailById($all_client_user_id);*/

            $jenny_user_id = getenv('JENNYUSERID');
            $all_client_user_email = User::getUserEmailById($jenny_user_id);

            $user_id = \Auth::user()->id;
            $user_name = \Auth::user()->name;

            $client = ClientBasicinfo::getClientDetailsById($id);

            $module = "Client Delete";
            $sender_name = $user_id;
            $to = $superadminemail;
            $subject = $client['name'] . " - " . $client['billing_city'] . " - Client Delete By - " . $user_name;
            $message = "<tr><td>" . $user_name . " Delete Client </td></tr>";
            $module_id = $id;
            $cc = $all_client_user_email;

            event(new NotificationMail($module,$sender_name,$to,$subject,$message,$module_id,$cc));

            \DB::statement("UPDATE client_basicinfo SET `delete_client`='1' where `id` = '$id'");

            /*\DB::table('client_address')->where('client_id', '=', $id)->delete();
            \DB::table('client_doc')->where('client_id', '=', $id)->delete();
            Notifications::where('module','=','Client')->where('module_id','=',$id)->delete();
            ClientBasicinfo::where('id',$id)->delete();

            // unlink documents
            $dir_name = "uploads/clients/".$id."/";
            $client_doc = new ClientDoc();
            if(is_dir($dir_name)) {
                $response = $client_doc->recursiveRemoveDirectory($dir_name);
            }

            // Delete Timeline table entry
            \DB::table('client_timeline')->where('client_id', '=', $id)->delete();*/

            return redirect()->route('client.index')->with('success','Client Deleted Successfully.');
        }
        else {
            return redirect()->route('client.index')->with('error','Client is associated with job.!!');
        }
    }

    public function update(Request $request, $id) {

         $this->validate($request, [
            'name' => 'required',
            'mail' => [
                'required','email',Rule::unique('client_basicinfo')->where(function($query) use ($id){
                  $query->where('delete_client', '=', '0');
                  $query->where('id', '!=', $id);
              })
            ],
        ]);

        $user_id = \Auth::user()->id;
        $input = $request->all();
        $input = (object)$input;

        $client_basicinfo = ClientBasicinfo::find($id);

        // Check Account Manager is changed or not
        $old_account_manager = $client_basicinfo->account_manager_id;
        $new_account_manager = $input->account_manager;

        // Check Second line Account Manager is changed or not
        $old_secondline_account_manager = $client_basicinfo->second_line_am;
        $new_secondline_account_manager = $input->second_line_am;

        // Check Status is changed or not
        $old_status = $client_basicinfo->status;
        $new_status = $input->status;

        $client_basicinfo->name = trim($input->name);
        $client_basicinfo->display_name = trim($input->display_name);
        $client_basicinfo->mobile = $input->mobile;
        $client_basicinfo->other_number = $input->other_number;
        $client_basicinfo->mail = $input->mail;
        $client_basicinfo->s_email = $input->s_email;
        $client_basicinfo->description = $input->description;

        if(isset($input->percentage_charged_below) && $input->percentage_charged_below != '') {
            $client_basicinfo->percentage_charged_below = $input->percentage_charged_below;
        }
        else if (isset($client_basicinfo->percentage_charged_below) && $client_basicinfo->percentage_charged_below != '') {
            $client_basicinfo->percentage_charged_below = $client_basicinfo->percentage_charged_below;
        }
        else {
            $client_basicinfo->percentage_charged_below = '8.33';
        }
        
        if(isset($input->percentage_charged_above) && $input->percentage_charged_above !='') {
            $client_basicinfo->percentage_charged_above = $input->percentage_charged_above;
        }
        else if (isset($client_basicinfo->percentage_charged_above) && $client_basicinfo->percentage_charged_above != '') {
            $client_basicinfo->percentage_charged_above = $client_basicinfo->percentage_charged_above;
        }
        else {
            $client_basicinfo->percentage_charged_above = '8.33';
        }

        $client_basicinfo->industry_id = $input->industry_id;
        $client_basicinfo->status = $new_status;

        // save passive date for passive client
        if($new_status == '0') {
            $today_date = date('Y-m-d'); 
            $client_basicinfo->passive_date = $today_date;
        }

        if(isset($input->source) && $input->source!='') {
            $client_basicinfo->source = $input->source;
        }
        else {
            $client_basicinfo->source = '';
        }

        $client_basicinfo->website = $input->website;
        $client_basicinfo->coordinator_name = trim($input->contact_point);
        $client_basicinfo->coordinator_prefix = $input->co_category;
        $client_basicinfo->account_manager_id = $input->account_manager;

        if(isset($input->gst_no) && $input->gst_no!='')
            $client_basicinfo->gst_no = $input->gst_no;
        else
            $client_basicinfo->gst_no = '';
        
        if(isset($input->tan) && $input->tan!='')
            $client_basicinfo->tan = $input->tan;
        else
            $client_basicinfo->tan = '';

        if(isset($input->client_category)) {
            $client_basicinfo->category = $input->client_category;
        }
        else if (isset($client_basicinfo->category) && $client_basicinfo->category != '') {
            $client_basicinfo->category = $client_basicinfo->category;
        }
        else {
            $client_basicinfo->category = '';
        }

        if(isset($input->second_line_am)) {
            $client_basicinfo->second_line_am = $input->second_line_am;
        }
        else {
            $client_basicinfo->second_line_am = '0';
        }

        if($client_basicinfo->save()) {

            // update client address
            $client_address = ClientAddress::find($input->client_address_id);

            if(!isset($client_address) && empty($client_address)) {
                $client_address = new ClientAddress();
                $client_address->client_id = $id;
            }
            if(isset($input->billing_country) && $input->billing_country!='') {
                $client_address->billing_country = $input->billing_country;
            }
            if(isset($input->billing_state) && $input->billing_state!='') {
                $client_address->billing_state = $input->billing_state;
            }
            if(isset($input->billing_street1) && $input->billing_street1!='') {
                $client_address->billing_street1 = $input->billing_street1;
            }
            if(isset($input->billing_street2) && $input->billing_street2!='') {
                $client_address->billing_street2 = $input->billing_street2;
            }
            if(isset($input->billing_code) && $input->billing_code!='') {
                $client_address->billing_code = $input->billing_code;
            }
            if(isset($input->billing_city) && $input->billing_city!='') {
                $client_address->billing_city = $input->billing_city;
            }
            if(isset($input->shipping_country) && $input->shipping_country!='') {
                $client_address->shipping_country = $input->shipping_country;
            }
            if(isset($input->shipping_state) && $input->shipping_state!='') {
                $client_address->shipping_state = $input->shipping_state;
            }
            if(isset($input->shipping_street1) && $input->shipping_street1!='') {
                $client_address->shipping_street1 = $input->shipping_street1;
            }
            if(isset($input->shipping_street2) && $input->shipping_street2!='') {
                $client_address->shipping_street2 = $input->shipping_street2;
            }
            if(isset($input->shipping_code) && $input->shipping_code!='') {
                $client_address->shipping_code = $input->shipping_code;
            }
            if(isset($input->shipping_city) && $input->shipping_city!='') {
                $client_address->shipping_city = $input->shipping_city;
            }

            $client_address->updated_at = date("Y-m-d H:i:s");
            $client_address->save();

            // if account manager change then jobs hiring manager all changes
            $job_ids = JobOpen::getJobIdByClientId($id);
            /*if ($input->account_manager == '0') {
                $super_admin_userid = getenv('SUPERADMINUSERID');
                $a_m = $super_admin_userid;
            }
            else {
                $a_m = $input->account_manager;
            }*/

            $a_m = $input->account_manager;

            foreach ($job_ids as $key => $value) {

                \DB::statement("UPDATE job_openings SET hiring_manager_id = '$a_m' where id = $value");

                if ($a_m == '0') {

                    \DB::statement("UPDATE job_openings SET priority = '4' where id = $value");
                }

                $check_job_user_id = JobVisibleUsers::getCheckJobUserIdAdded($value,$a_m);

                if ($check_job_user_id == false) {
                    $job_visible_users = new JobVisibleUsers();
                    $job_visible_users->job_id = $value;
                    $job_visible_users->user_id = $a_m;
                    $job_visible_users->save();
                }
            }

            // If Client Account Manager is changed.

            if($old_account_manager == $new_account_manager) {

            }
            else {

                $module_id = $id;
                $module = 'Client Account Manager';
                $link = route('client.show',$id);
                $subject = "Client Account Manager Changed - " . $input->name . " - " . $input->billing_city;
                $message = "<tr><td>" . $input->name . " Change Account Manager </td></tr>";
                $sender_name = $user_id;

                $super_admin_userid = getenv('SUPERADMINUSERID');
                $superadminemail = User::getUserEmailById($super_admin_userid);

                /*$all_client_user_id = getenv('ALLCLIENTVISIBLEUSERID');
                $all_client_user_email = User::getUserEmailById($all_client_user_id);*/

                $jenny_user_id = getenv('JENNYUSERID');
                $all_client_user_email = User::getUserEmailById($jenny_user_id);

                if ($input->account_manager != '0') {

                    $to = $superadminemail;
                    $account_manager_email = User::getUserEmailById($input->account_manager);
                    $cc_users_array = array($all_client_user_email,$account_manager_email);
                    $cc = implode(",",$cc_users_array);
                }
                else {

                    $to = $superadminemail;
                    $cc = $all_client_user_email;
                }

                event(new NotificationMail($module,$sender_name,$to,$subject,$message,$module_id,$cc));
            }

            // If Client Second line AM Changed

            if($old_secondline_account_manager == $new_secondline_account_manager) {

            }
            else {

                $module_id = $id;
                $module = 'Client 2nd Line Account Manager';

                $subject = "Client 2nd Line Account Manager Assigned - " . $input->name .  " - " . $input->contact_point . " - " . $input->billing_city;
                $message = "<tr><td>" . $input->name . " Client 2nd Line Account Manager </td></tr>";
                $sender_name = $user_id;

                $super_admin_userid = getenv('SUPERADMINUSERID');
                $superadminemail = User::getUserEmailById($super_admin_userid);

                /*$all_client_user_id = getenv('ALLCLIENTVISIBLEUSERID');
                $all_client_user_email = User::getUserEmailById($all_client_user_id);*/

                $jenny_user_id = getenv('JENNYUSERID');
                $all_client_user_email = User::getUserEmailById($jenny_user_id);

                // Get Account Manager Id

                $account_manager_id = $input->account_manager;
                $account_manager_email = User::getUserEmailById($account_manager_id);

                if ($new_secondline_account_manager != '0') {

                    $to = $superadminemail;
                    $secondline_account_manager_email = User::getUserEmailById($new_secondline_account_manager);
                    $cc_users_array = array($account_manager_email,$secondline_account_manager_email,$all_client_user_email);
                    $cc = implode(",",$cc_users_array);
                }
                else {

                    $to = $superadminemail;
                    $cc_users_array = array($account_manager_email,$all_client_user_email);
                    $cc = implode(",",$cc_users_array);
                }

                event(new NotificationMail($module,$sender_name,$to,$subject,$message,$module_id,$cc));
            }

            // Add Entry in Client Timeline.
            $check_entry_exist = ClientTimeline::checkTimelineEntry($input->account_manager,$id);

            if(isset($check_entry_exist) && $check_entry_exist != '') {
            }
            else {

                $get_latest_record = \DB::table('client_timeline')
                ->select('client_timeline.*')->where('client_id','=',$id)->orderBy('client_timeline.id','desc')->first();

                $to_date = date('Y-m-d');

                if(isset($get_latest_record) && $get_latest_record != '') {

                    $to = strtotime($to_date);
                    $from = strtotime($get_latest_record->created_at);
                    $diff_in_days = ($to - $from)/60/60/24;

                    \DB::statement("UPDATE client_timeline SET to_date = '$to_date', days = '$diff_in_days' where client_id = $id AND user_id = $get_latest_record->user_id");
                }

                $client_timeline = new ClientTimeline();
                $client_timeline->user_id = $input->account_manager;
                $client_timeline->client_id = $id;
                $client_timeline->save();
            }

            if($old_status == $new_status) {

            }
            else {

                if($new_status == '3') {

                    // Forbid Client Email Notifications : On change status of client as forbid
                    $module_id = $id;
                    $module = 'Forbid Client';
                    $link = route('client.show',$id);
                    $subject = "Forbid Client - " . $input->name . " - " . $input->billing_city;
                    $message = "<tr><td>" . $input->name . " Convert as forbid Client </td></tr>";
                    $sender_name = $user_id;

                    $super_admin_userid = getenv('SUPERADMINUSERID');
                    $superadminemail = User::getUserEmailById($super_admin_userid);

                    /*$all_client_user_id = getenv('ALLCLIENTVISIBLEUSERID');
                    $all_client_user_email = User::getUserEmailById($all_client_user_id);*/

                    $jenny_user_id = getenv('JENNYUSERID');
                    $all_client_user_email = User::getUserEmailById($jenny_user_id);

                    $account_manager_id = $input->account_manager;

                    if ($account_manager_id != '0') {

                        $to = $superadminemail;
                        $account_manager_email = User::getUserEmailById($account_manager_id);
                        $cc_users_array = array($all_client_user_email,$account_manager_email);
                        $cc = implode(",",$cc_users_array);
                    }
                    else {

                        $to = $superadminemail;
                        $cc = $all_client_user_email;
                    }

                    \DB::statement("UPDATE job_openings SET priority = '4' where client_id = $id");

                    event(new NotificationMail($module,$sender_name,$to,$subject,$message,$module_id,$cc));
                }
            }

            // Upload attchments

            $client_upload_type = $request->client_upload_type;
            $file = $request->file('file');

            if (isset($file) && $file->isValid()) {

                $doc_name = $file->getClientOriginalName();
                $doc_filesize = filesize($file);

                $dir_name = "uploads/clients/" . $id . "/";
                $doc_key = "uploads/clients/" . $id . "/" . $doc_name;

                if (!file_exists($dir_name)) {
                    mkdir("uploads/clients/$id", 0777, true);
                }

                if (!$file->move($dir_name, $doc_name)) {
                    return false;
                }
                else {

                    $client_doc = new ClientDoc;
                    $client_doc->client_id = $id;
                    $client_doc->category = $client_upload_type;
                    $client_doc->name = $doc_name;
                    $client_doc->file = $doc_key;
                    $client_doc->uploaded_by = $user_id;
                    $client_doc->size = $doc_filesize;
                    $client_doc->created_at = time();
                    $client_doc->updated_at = time();
                    $client_doc->save();
                }
                return redirect('client/'.$id.'/edit')->with('success','Attachment Uploaded Successfully.');
            }
            return redirect()->route('client.index')->with('success','Client Details Updated Successfully.');
        }
        else {
            return redirect('client/'.$client_basicinfo->id.'/edit')->withInput(Input::all())->withErrors ($client_basicinfo->errors());
        }
    }

    public function postClientEmails(Request $request) {

        $user = \Auth::user();
        $user_id = $user->id;

        $client_ids = $request->input('email_client_ids');
        $client_ids_array = explode(",",$client_ids);

        $email_template_id = $request->input('email_template_id');
        $template_nm = $request->input('template_nm');
        $email_subject = $request->input('email_subject');
        $email_body = $request->input('email_body');
        $updated_at = date('Y-m-d H:i:s');

        foreach($client_ids_array as $key => $value) {

            $client_details = ClientBasicinfo::getClientDetailsById($value);
            $client_email = $client_details['mail'];

            $module = 'Client Bulk Email';
            $sender_name = $user_id;
            $to = $client_email;
            $subject = $email_subject; 
            //$cc = 'rajlalwani@adlertalent.com';
            $cc = 'info@adlertalent.com';
            $module_id = $value;

            if(strpos($email_body, '{Clientname}') !== false) {

                $client_name = $client_details['coordinator_name'];
                $new_email_body = str_replace('{Clientname}',$client_name,$email_body);
                $body_message = "<tr><td style='padding:8px;'>$new_email_body</td></tr>";
            }
            else {
                $body_message = "<tr><td style='padding:8px;'>$email_body</td></tr>";
            }

            if(strpos($body_message, '{Amsignature}') !== false) {
                
                $client_am_id = $client_details['account_manager_id'];
                $user_info = User::getProfileInfo($client_am_id);
                $am_signature = $user_info->signature;

                $new_email_body_1 = str_replace('{Amsignature}',$am_signature,$body_message);
                $body_message_1 = "<tr><td style='padding:8px;'>$new_email_body_1</td></tr>";
            }
            else {
                $body_message_1 = "<tr><td style='padding:8px;'>$body_message</td></tr>";
            }

            if(strpos($body_message_1, '{Lusignature}') !== false) {
                
                $user_info = User::getProfileInfo($user_id);
                $lu_signature = $user_info->signature;

                $new_email_body_2 = str_replace('{Lusignature}',$lu_signature,$body_message_1);
                $body_message_2 = "<tr><td style='padding:8px;'>$new_email_body_2</td></tr>";
            }
            else {
                $body_message_2 = "<tr><td style='padding:8px;'>$body_message_1</td></tr>";
            }
            
            event(new NotificationMail($module,$sender_name,$to,$subject,$body_message_2,$module_id,$cc));
        }

        //\DB::statement("UPDATE email_template SET `user_id`=$user_id, `name`='$template_nm', `subject`='$email_subject', `email_body`='$email_body', `updated_at` = '$updated_at' where `id` = '$email_template_id'"); 

        return redirect()->route('client.index')->with('success','Email Sent Successfully.');
    }

    public function checkClientId() {

        if (isset($_POST['client_ids']) && $_POST['client_ids'] != '') {
            $client_ids = $_POST['client_ids'];
        }

        if (isset($client_ids) && sizeof($client_ids) > 0) {

            $status_array = ClientBasicinfo::getClientStatusArrayByIds($client_ids);

            if(in_array('2', $status_array)) {

                $msg['success'] = 'Leaders Clients';
            }
            else {

                $msg['success'] = 'Success';
            }
        }
        else {
            $msg['err'] = '<b>Please Select Client.</b>';
            $msg['msg'] = "Fail";
        }
        return $msg;
    }

    public function postClientAccountManager() {

        $account_manager_id = $_POST['account_manager_id'];

        $client_ids = $_POST['client_ids'];
        $client_ids_array = explode(",",$client_ids);

        $updated_at = date('Y-m-d H:i:s');

        $source = $_POST['am_source'];

        foreach($client_ids_array as $key => $value) {

            // If account manager change then jobs hiring manager also change
            $job_ids = JobOpen::getJobIdByClientId($value);
            /*if ($account_manager_id == '0') {
                $super_admin_userid = getenv('SUPERADMINUSERID');
                $a_m = $super_admin_userid;
            }
            else {
                $a_m = $account_manager_id;
            }*/

            $a_m = $account_manager_id;

            foreach ($job_ids as $k1 => $v1) {

                \DB::statement("UPDATE job_openings SET hiring_manager_id = '$a_m' where id = $v1");

                if ($a_m == '0') {

                    \DB::statement("UPDATE job_openings SET priority = '4' where id = $v1");
                }

                $check_job_user_id = JobVisibleUsers::getCheckJobUserIdAdded($v1,$a_m);

                if ($check_job_user_id == false) {
                    $job_visible_users = new JobVisibleUsers();
                    $job_visible_users->job_id = $v1;
                    $job_visible_users->user_id = $a_m;
                    $job_visible_users->save();
                }
            }

            // Add Entry in Client Timeline.

            $get_latest_record = \DB::table('client_timeline')->select('client_timeline.*')
            ->where('client_id','=',$value)->orderBy('client_timeline.id','desc')->first();

            $to_date = date('Y-m-d');

            if(isset($get_latest_record) && $get_latest_record != '') {

                $to = strtotime($to_date);
                $from = strtotime($get_latest_record->created_at);
                $diff_in_days = ($to - $from)/60/60/24;

                \DB::statement("UPDATE client_timeline SET to_date = '$to_date', days = '$diff_in_days' where client_id = $value AND user_id = $get_latest_record->user_id");
            }

            $client_timeline = new ClientTimeline();
            $client_timeline->user_id = $account_manager_id;
            $client_timeline->client_id = $value;
            $client_timeline->save();

            \DB::statement("UPDATE client_basicinfo SET `account_manager_id`='$account_manager_id', updated_at = '$updated_at' where `id` = '$value'");
        }

        // Email Notifications : On change account manager of client.

        $user_id = \Auth::user()->id;
        
        $module = 'List of Clients transferred';
        $sender_name = $user_id;
        $subject = "List of Clients transferred";
        $message = "<tr><td>List of Clients transferred</td></tr>";
        $module_id = $client_ids;

        $super_admin_userid = getenv('SUPERADMINUSERID');
        $superadminemail = User::getUserEmailById($super_admin_userid);

        /*$all_client_user_id = getenv('ALLCLIENTVISIBLEUSERID');
        $all_client_user_email = User::getUserEmailById($all_client_user_id);*/

        $jenny_user_id = getenv('JENNYUSERID');
        $all_client_user_email = User::getUserEmailById($jenny_user_id);

        if ($account_manager_id != '0') {

            $to = $superadminemail;
            $account_manager_email = User::getUserEmailById($account_manager_id);
            $cc_users_array = array($all_client_user_email,$account_manager_email);
            $cc = implode(",",$cc_users_array);
        }
        else {

            $to = $superadminemail;
            $cc = $all_client_user_email;
        }

        event(new NotificationMail($module,$sender_name,$to,$subject,$message,$module_id,$cc));

        if(isset($source) && $source != '') {

            return redirect('/client-list/'.$source)->with('success', 'Client Account Manager Changed Successfully.');
        }
        else {
            return redirect()->route('client.index')->with('success','Client Account Manager Changed Successfully.');
        }
    }

    public function postSecondlineClientAccountManager() {

        $second_line_am_id = $_POST['second_line_am_id'];

        $client_ids = $_POST['second_line_am_client_ids'];
        $client_ids_array = explode(",",$client_ids);

        $source = $_POST['second_line_am_source'];

        foreach($client_ids_array as $key => $value) {

            // Get Client Old Secondline AM
            $client_info = ClientBasicinfo::getClientDetailsById($value);
            $old_sl_am = $client_info['second_line_am'];

            // Update New Secondline AM
            $client = ClientBasicinfo::find($value);
            $client->second_line_am = $second_line_am_id;
            $client->save();

            // If account manager change then jobs hiring manager also change
            $job_ids = JobOpen::getJobIdByClientId($value);

            if(isset($job_ids) && sizeof($job_ids) > 0) {

                foreach ($job_ids as $k1 => $v1) {

                    if($old_sl_am > 0) {

                        JobVisibleUsers::where('job_id',$value)->where('user_id',$old_sl_am)->delete();
                    }
                   
                    $check_job_user_id = JobVisibleUsers::getCheckJobUserIdAdded($v1,$second_line_am_id);

                    if ($check_job_user_id == false) {
                        $job_visible_users = new JobVisibleUsers();
                        $job_visible_users->job_id = $v1;
                        $job_visible_users->user_id = $second_line_am_id;
                        $job_visible_users->save();
                    }
                }
            }
        }

        // Email Notifications : On change 2nd line account manager of client.

        $user_id = \Auth::user()->id;

        $module = '2nd Line of Multiple Clients';
        $sender_name = $user_id;
        $subject = "2nd Line of Multiple Clients";
        $message = "<tr><td>2nd Line of Multiple Clients</td></tr>";
        $module_id = $client_ids;

        $super_admin_userid = getenv('SUPERADMINUSERID');
        $superadminemail = User::getUserEmailById($super_admin_userid);

        /*$all_client_user_id = getenv('ALLCLIENTVISIBLEUSERID');
        $all_client_user_email = User::getUserEmailById($all_client_user_id);*/

        $jenny_user_id = getenv('JENNYUSERID');
        $all_client_user_email = User::getUserEmailById($jenny_user_id);

        // Get Account Manager Id

        $assigned_by_email = User::getUserEmailById($user_id);

        if ($second_line_am_id != '0') {

            $to = $superadminemail;
            $secondline_account_manager_email = User::getUserEmailById($second_line_am_id);
            $cc_users_array = array($assigned_by_email,$secondline_account_manager_email,$all_client_user_email);
            $cc = implode(",",$cc_users_array);
        }
        else {

            $to = $superadminemail;
            $cc_users_array = array($assigned_by_email,$all_client_user_email);
            $cc = implode(",",$cc_users_array);
        }

        event(new NotificationMail($module,$sender_name,$to,$subject,$message,$module_id,$cc));

        if(isset($source) && $source != '') {

            return redirect('/client-list/'.$source)->with('success', 'Second-line Account Manager Changed Successfully.');
        }
        else {
            return redirect()->route('client.index')->with('success', 'Second-line Account Manager Changed Successfully.');
        }
    }

    public function getMonthWiseClient($month,$year,$department_id) {

        $user =  \Auth::user();
        $all_perm = $user->can('display-client');
        $userwise_perm = $user->can('display-account-manager-wise-client');
        
        if($department_id == 0) {

            if($all_perm) {
                $response = ClientBasicinfo::getMonthWiseClientByUserId($user->id,1,$month,$year,$department_id);
                $count = sizeof($response);
            }
            else if($userwise_perm) {
                $response = ClientBasicinfo::getMonthWiseClientByUserId($user->id,0,$month,$year,$department_id);
                $count = sizeof($response);
            }
        }
        else {

            $response = ClientBasicinfo::getMonthWiseClientByUserId($user->id,1,$month,$year,$department_id);
            $count = sizeof($response);
        }

        return view('adminlte::client.monthwiseclient', array('clients' => $response,'count' => $count));
    }

    public function getClientsBySelectedMonth($month,$year) {

        $user =  \Auth::user();

        $response = ClientBasicinfo::getMonthWiseClientByUserId($user->id,1,$month,$year,0);
        $count = sizeof($response);

        return view('adminlte::client.monthwiseclient', array('clients' => $response,'count' => $count));
    }

    public function getAccountManager(Request $request) {

        $account_manager = $request->get('account_manager');
        $id = $request->get('id');
        $source = $request->get('source');

        $act_man = ClientBasicinfo::find($id);
        $a_m = '';

        if(isset($act_man)) {
            $a_m = $account_manager;
        }
        $act_man->account_manager_id = $a_m;
        $act_man->save();

        if ($act_man) {

            $job_ids = JobOpen::getJobIdByClientId($id);
           /* if ($account_manager == '0') {
                $super_admin_userid = getenv('SUPERADMINUSERID');
                $a_m = $super_admin_userid;
            }*/

            foreach ($job_ids as $key => $value) {

                \DB::statement("UPDATE job_openings SET hiring_manager_id = '$account_manager' where id = $value");

                if ($account_manager == '0') {

                    \DB::statement("UPDATE job_openings SET priority = '4' where id = $value");
                }

                $check_job_user_id = JobVisibleUsers::getCheckJobUserIdAdded($value,$a_m);

                if ($check_job_user_id == false) {

                    $job_visible_users = new JobVisibleUsers();
                    $job_visible_users->job_id = $value;
                    $job_visible_users->user_id = $a_m;
                    $job_visible_users->save();
                }
            }
        }

        // Add Entry in Client Timeline.
        $get_latest_record = \DB::table('client_timeline')->select('client_timeline.*')
        ->where('client_id','=',$id)->orderBy('client_timeline.id','desc')->first();

        $to_date = date('Y-m-d');

        if(isset($get_latest_record) && $get_latest_record != '') {

            $to = strtotime($to_date);
            $from = strtotime($get_latest_record->created_at);
            $diff_in_days = ($to - $from)/60/60/24;

            \DB::statement("UPDATE client_timeline SET to_date = '$to_date', days = '$diff_in_days' where client_id = $id AND user_id = $get_latest_record->user_id");
        }
        
        $client_timeline = new ClientTimeline();
        $client_timeline->user_id = $account_manager;
        $client_timeline->client_id = $id;
        $client_timeline->save();

        // Email Notifications : On change account manager of client.
        $client_name = $act_man->name;
        $billing_city = ClientBasicinfo::getBillingCityOfClientByID($id);
        $user_id = \Auth::user()->id;

        $module_id = $id;
        $module = 'Client Account Manager';
        $link = route('client.show',$id);
        $subject = "Client Account Manager Changed - " . $client_name . " - " . $billing_city;
        $message = "<tr><td>" . $client_name . " Change Account Manager </td></tr>";
        $sender_name = $user_id;

        $super_admin_userid = getenv('SUPERADMINUSERID');
        $superadminemail = User::getUserEmailById($super_admin_userid);

        /*$all_client_user_id = getenv('ALLCLIENTVISIBLEUSERID');
        $all_client_user_email = User::getUserEmailById($all_client_user_id);*/

        $jenny_user_id = getenv('JENNYUSERID');
        $all_client_user_email = User::getUserEmailById($jenny_user_id);

        if ($account_manager != '0') {

            $to = $superadminemail;
            $account_manager_email = User::getUserEmailById($account_manager);
            $cc_users_array = array($all_client_user_email,$account_manager_email);
            $cc = implode(",",$cc_users_array);
        }
        else {

            $to = $superadminemail;
            $cc = $all_client_user_email;
        }

        event(new NotificationMail($module,$sender_name,$to,$subject,$message,$module_id,$cc));

        if(isset($source) && $source != '') {

            return redirect('/client-list/'.$source)->with('success', 'Client Account Manager Changed Successfully.');
        }
        else {
            return redirect()->route('client.index')->with('success','Client Account Manager Changed Successfully.');
        }
    }

    public function getSecondlineAccountManager(Request $request) {

        $user_id = \Auth::user()->id;

        $secondline_account_manager = $request->get('secondline_account_manager');
        $id = $request->get('id');
        $source = $request->get('source');

        // Get Client Old Secondline AM
        $client_info = ClientBasicinfo::getClientDetailsById($id);
        $old_sl_am = $client_info['second_line_am'];

        // Update New Secondline AM
        $client = ClientBasicinfo::find($id);
        $client->second_line_am = $secondline_account_manager;
        $client->save();

        $job_ids_array = JobOpen::getJobIdByClientId($id);

        if(isset($job_ids_array) && sizeof($job_ids_array) > 0) {

            foreach ($job_ids_array as $key => $value) {

                if($old_sl_am > 0) {

                    JobVisibleUsers::where('job_id',$value)->where('user_id',$old_sl_am)
                    ->delete();
                }

                $job_visible_users = new JobVisibleUsers();
                $job_visible_users->job_id = $value;
                $job_visible_users->user_id = $secondline_account_manager;
                $job_visible_users->save();
            }
        }

        $module_id = $id;
        $module = 'Client 2nd Line Account Manager';

        $subject = "Client 2nd Line Account Manager Assigned - " . $client_info['name'] .  " - " . $client_info['coordinator_name'] . " - " . $client_info['billing_city'];
        $message = "<tr><td>" . $client_info['name'] . " Client 2nd Line Account Manager </td></tr>";
        $sender_name = $user_id;

        $super_admin_userid = getenv('SUPERADMINUSERID');
        $superadminemail = User::getUserEmailById($super_admin_userid);

        /*$all_client_user_id = getenv('ALLCLIENTVISIBLEUSERID');
        $all_client_user_email = User::getUserEmailById($all_client_user_id);*/

        $jenny_user_id = getenv('JENNYUSERID');
        $all_client_user_email = User::getUserEmailById($jenny_user_id);

        // Get Account Manager Id

        $account_manager_id = $client_info['account_manager_id'];
        $account_manager_email = User::getUserEmailById($account_manager_id);

        if ($secondline_account_manager != '0') {

            $to = $superadminemail;
            $secondline_account_manager_email = User::getUserEmailById($secondline_account_manager);
            $cc_users_array = array($account_manager_email,$secondline_account_manager_email,$all_client_user_email);
            $cc = implode(",",$cc_users_array);
        }
        else {

            $to = $superadminemail;
            $cc_users_array = array($account_manager_email,$all_client_user_email);
            $cc = implode(",",$cc_users_array);
        }

        event(new NotificationMail($module,$sender_name,$to,$subject,$message,$module_id,$cc));

        if(isset($source) && $source != '') {

            return redirect('/client-list/'.$source)->with('success', 'Second-line Account Manager Changed Successfully.');
        }
        else {
            return redirect()->route('client.index')->with('success', 'Second-line Account Manager Changed Successfully.');
        }
    }

    public function getAllClientsByAM() {

        $user =  \Auth::user();
        $all_perm = $user->can('display-client');
        $userwise_perm = $user->can('display-account-manager-wise-client');

        if($all_perm) {

            $count = ClientBasicinfo::getClientsCountByAM(1,$user->id,'');
        }
        else if($userwise_perm) {

            $count = ClientBasicinfo::getClientsCountByAM(0,$user->id,'');
        }

        return view('adminlte::client.clientlistamwise',compact('count'));
    }

    public function getOrderColumnNameByAM($order) {

        $order_column_name = '';

        if (isset($order) && $order >= 0) {

            if ($order == 0) {
                $order_column_name = "client_basicinfo.id";
            }
            else if ($order == 2) {
                $order_column_name = "users.name";
            }
            else if ($order == 3) {
                $order_column_name = "client_basicinfo.name";
            }
            else if ($order == 4) {
                $order_column_name = "client_basicinfo.coordinator_prefix";
            }
            else if ($order == 5) {
                $order_column_name = "client_basicinfo.category";
            }
            else if ($order == 6) {
                $order_column_name = "client_basicinfo.status";
            }
            else if ($order == 7) {
                $order_column_name = "client_address.billing_city";
            }
            else if ($order == 8) {
                $order_column_name = "industry.name";
            }
        }
        return $order_column_name;
    }

    public function getAllClientsDetailsByAM() {

        $draw = $_GET['draw'];
        $limit = $_GET['length'];
        $offset = $_GET['start'];
        $search = $_GET['search']['value'];
        $order = $_GET['order'][0]['column'];
        $type = $_GET['order'][0]['dir'];
        
        $user =  \Auth::user();
        $all_perm = $user->can('display-client');
        $userwise_perm = $user->can('display-account-manager-wise-client');
        $edit_perm = $user->can('client-edit');
        $delete_perm = $user->can('client-delete');
        $category_perm = $user->can('display-client-category-in-client-list');

        if($all_perm) {

            $order_column_name = self::getOrderColumnNameByAM($order);
            $client_res = ClientBasicinfo::getAllClientsByAM(1,$user->id,$limit,$offset,$search,$order_column_name,$type);
            $count = ClientBasicinfo::getClientsCountByAM(1,$user->id,$search);
        }
        else if($userwise_perm) {

            $order_column_name = self::getOrderColumnNameByAM($order);
            $client_res = ClientBasicinfo::getAllClientsByAM(0,$user->id,$limit,$offset,$search,$order_column_name,$type);
            $count = ClientBasicinfo::getClientsCountByAM(0,$user->id,$search);
        }
        
        $recruitment = getenv('RECRUITMENT');
        $hr_advisory = getenv('HRADVISORY');
        $management = getenv('MANAGEMENT');
        $type_array = array($recruitment,$hr_advisory,$management);

        $users_array = User::getAllUsers(NULL,'Yes');
        $account_manager = array();

        if(isset($users_array) && sizeof($users_array) > 0) {

            foreach ($users_array as $k1 => $v1) {
                               
                $user_details = User::getAllDetailsByUserID($k1);

                if($user_details->type == '2') {
                    if($user_details->hr_adv_recruitemnt == 'Yes') {
                        $account_manager[$k1] = $v1;
                    }
                }
                else {
                    $account_manager[$k1] = $v1;
                }    
            }
        }
        $account_manager[0] = 'Yet to Assign';

        $clients = array();
        $i = 0;$j = 0;

        foreach ($client_res as $key => $value) {

            $action = '';
            $action .= '<a title="Show" class="fa fa-circle"  href="'.route('client.show',$value['id']).'" style="margin:2px;"></a>'; 
        
            if($edit_perm) {

                $action .= '<a title="Edit" class="fa fa-edit" href="'.route('client.edit',$value['id']).'" style="margin:2px;"></a>';
            }
            if($delete_perm) {

                $delete_view = \View::make('adminlte::partials.deleteModalNew', ['data' => $value, 'name' => 'client','display_name'=>'Client']);
                $delete = $delete_view->render();
                $action .= $delete;

                if(isset($value['url']) && $value['url'] != '') {
                    $action .= '<a target="_blank" href="'.$value['url'].'"><i  class="fa fa-fw fa-download"></i></a>';
                }
            }
            if($all_perm) {

                $account_manager_view = \View::make('adminlte::partials.client_account_manager', ['data' => $value, 'name' => 'client', 'account_manager' => $account_manager,'source' => '']);
                $account = $account_manager_view->render();
                $action .= $account;
            }
            if($userwise_perm) {

                $secondline_account_manager_view = \View::make('adminlte::partials.secondline_account_manager', ['data' => $value, 'name' => 'client', 'account_manager' => $account_manager,'source' => '']);
                $secondline_account = $secondline_account_manager_view->render();
                $action .= $secondline_account;

            }
            if($all_perm || $value['client_owner']) {

                $action .= '<a title="Remarks" class="fa fa-plus"  href="'.route('client.remarks',$value['id']).'" style="margin:2px;"></a>';
            }
            if($all_perm || $value['client_owner'] || $value['second_line_client_owner']) {

                // Client Hiring Report
                $hiring_report = \View::make('adminlte::partials.client_hiring_report', ['data' => $value,'page' => 'AM','source' => '']);
                $report = $hiring_report->render();
                $action .= $report;
            }
            if($all_perm || $value['client_owner']) {

                $days_array = ClientTimeline::getTimelineDetailsByClientId($value['id']);

                $timeline_view = \View::make('adminlte::partials.client_timeline_view', ['data' => $value,'days_array' => $days_array]);
                $timeline = $timeline_view->render();
                $action .= $timeline;
            }

            $company_name = '<a style="white-space: pre-wrap; word-wrap: break-word; color:black; text-decoration:none;">'.$value['name'].'</a>';
            $contact_point = '<a style="white-space: pre-wrap; word-wrap: break-word; color:black; text-decoration:none;">'.$value['hr_name'].'</a>';
            $industry_name = '<a style="white-space: pre-wrap; word-wrap: break-word; color:black; text-decoration:none;">'.$value['industry_name'].'</a>';

            if($value['status'] == 'Active')
                $client_status = '<span class="label label-sm label-success">'.$value['status'].'</span></td>';
            else if($value['status'] == 'Passive')
                $client_status = '<span class="label label-sm label-danger">'.$value['status'].'</span></td>';
            else if($value['status'] == 'Leaders')
                $client_status = '<span class="label label-sm label-primary">'.$value['status'].'</span></td>';
            else if($value['status'] == 'Forbid')
                $client_status = '<span class="label label-sm label-default">'.$value['status'].'</span>';
            else if($value['status'] == 'Left')
                $client_status = '<span class="label label-sm label-info">'.$value['status'].'</span>';

            $client_category = $value['category'];

            if(isset($value['second_line_am_name']) && $value['second_line_am_name'] != '') {

                $am_name = $value['am_name']." | ".$value['second_line_am_name'];
            }
            else {
                $am_name = $value['am_name'];
            }

            if($category_perm) {
                $data = array(++$j,$action,$am_name,$company_name,$contact_point,$client_category,$client_status,$value['address'],$industry_name);
            }
            else {
                $data = array(++$j,$action,$am_name,$company_name,$contact_point,$client_status,$value['address'],$industry_name);
            }

            $clients[$i] = $data;
            $i++;
        }

        $json_data = array(
            'draw' => intval($draw),
            'recordsTotal' => intval($count),
            'recordsFiltered' => intval($count),
            "data" => $clients
        );

        echo json_encode($json_data);exit;
    }

    public function importExport() {

        return view('adminlte::client.import');
    }

    public function importExcel(Request $request) {

        if($request->hasFile('import_file')) {

            $path = $request->file('import_file')->getRealPath();
            $data = Excel::load($path, function ($reader) {})->get();
            $messages = array();

            if (!empty($data) && $data->count()) {

                foreach ($data->toArray() as $key => $value) {

                    if(!empty($value)) {
                        foreach ($value as $v) {

                            $sr_no = $v['sr_no'];
                            $managed_by = $v['managed_by'];
                            $name = $v['client_company'];
                            $coordinator_name = $v['hrcoordinator_name'];
                            $location = $v['location'];
                            $mobile_number = $v['contact_details'];
                            $email = $v['e_mail'];

                            // first check email already exist or not , if exist doesnot update data
                            $client_cnt = ClientBasicinfo::checkClientByEmail($email);

                            if($client_cnt>0) {
                                $messages[] = "Record $sr_no already present ";
                            }
                            else {

                                // get user id from managed_by (i.e. username)
                                $acc_mngr_id = User::getUserIdByName($managed_by);

                                if($acc_mngr_id > 0) {

                                    // Insert new client
                                    $client_basic_info = new ClientBasicinfo();
                                    $client_basic_info->name = $name;
                                    $client_basic_info->mail = $email;
                                    $client_basic_info->mobile = $mobile_number;
                                    $client_basic_info->coordinator_name = $coordinator_name;
                                    $client_basic_info->account_manager_id = $acc_mngr_id;

                                    if($client_basic_info->save()) {

                                        $client_id = $client_basic_info->id;
                                        $input['client_id'] = $client_id;
                                        $input['billing_city'] = $location;
                                        $input['shipping_city'] = $location;
                                        ClientAddress::create($input);

                                        if ($client_id > 0) {
                                            $messages[] = "Record $sr_no inserted successfully";
                                        }
                                    }
                                    else {
                                        $messages[] = "Error while inserting record $sr_no ";
                                    }
                                }
                                else {
                                    $messages[] = "Error while inserting record $sr_no ";
                                }
                            }
                        }
                    }
                    else {
                        $messages[] = "No Data in file";
                    }
                }
            }
            return view('adminlte::client.import',compact('messages'));
        }
    }

    public function remarks($id) {

        $user_id = \Auth::user()->id;
        $client_id = $id;

        $super_admin_userid = getenv('SUPERADMINUSERID');
        $manager_user_id = env('MANAGERUSERID');

        $client = ClientBasicinfo::find($client_id);
        $client_location = ClientBasicinfo::getBillingCityOfClientByID($client_id);
        $post = $client->post()->orderBy('created_at', 'desc')->get();

        $days_array = ClientTimeline::getTimelineDetailsByClientId($id);

        /*Remove Remarks List

        $client_remarks = ClientRemarks::getAllClientRemarksData();
        $client_remarks['other'] = 'Others';
        if($user_id == $super_admin_userid || $user_id == $manager_user_id) {

            $client_remarks['other'] = 'Others';
        }
        
        $client_remarks_edit = ClientRemarks::getAllClientRemarksData();*/

        $client_remarks = array();
        $client_remarks_edit = array();

        return view('adminlte::client.remarks',compact('user_id','client_id','post','client','client_location','super_admin_userid','manager_user_id','days_array','client_remarks','client_remarks_edit'));
    }

    public function writePost(Request $request, $client_id) {

        $input = $request->all();
        $user_id = $input['user_id'];
        $client_id = $input['client_id'];
        $content = $input['content'];
        $super_admin_userid = $input['super_admin_userid'];
        $manager_user_id = $input['manager_user_id'];

        if(isset($user_id) && $user_id > 0) {

            // If remarks not added then add that only by superadmin
           /* if ($user_id == $super_admin_userid || $user_id == $manager_user_id) {
                // Check remark found or not
                $client_remark_check = ClientRemarks::checkClientRemark($content);
                if (isset($client_remark_check) && $client_remark_check != '' ) {

                }
                else {
                    $client_remarks = new ClientRemarks();
                    $client_remarks->remarks = $content;
                    $client_remarks->save();
                }
            }*/

            $post = new Post();
            $post->content = $content;
            $post->user_id = $user_id;
            $post->client_id = $client_id;
            $post->approved = 0;
            $post->approved_by = 0;
            $post->created_at = time();
            $post->updated_at = time();
            $post->save();

            // Update in Client Basicinfo Table
            $get_latest_remarks = ClientBasicinfo::getLatestRemarks($client_id);

            $client_info = ClientBasicinfo::find($client_id);
            $client_info->latest_remarks = $get_latest_remarks;
            $client_info->save();
        }
        return redirect()->route('client.remarks',[$client_id]);
    }

    public function writeComment(Request $request,$post_id) {

        $input = $request->all();
        $client_id = $input['client_id'];
        $super_admin_userid = $input['super_admin_userid'];

        $user_id = \Auth::user()->id;

        // If remarks not added then add that only by superadmin
        if ($user_id == $super_admin_userid) {

            // Check remark found or not
            $client_remark_check = ClientRemarks::checkClientRemark($input["content"]);
            if (isset($client_remark_check) && $client_remark_check != '') {

            }
            else {
                $client_remarks = new ClientRemarks();
                $client_remarks->remarks = $input["content"];
                $client_remarks->save();
            }
        }

        $currentUser = User::find($user_id);
        $post = Post::find($post_id);

        $comment = $post->comment([
            "title" => $input["content"],
            "body" => $input["content"]
        ], $currentUser);

        $returnValue["success"] = true;
        $returnValue["message"] = "Commment recorded";
        $returnValue["id"] = $comment->id;

        // Update in Client Basicinfo Table
        $get_latest_remarks = ClientBasicinfo::getLatestRemarks($client_id);

        $client_info = ClientBasicinfo::find($client_id);
        $client_info->latest_remarks = $get_latest_remarks;
        $client_info->save();

        return redirect()->route('client.remarks',[$client_id]);
    }

    public function updateClientRemarks(Request $request, $client_id,$post_id) {

        $input = $request->all();
        $user_id = $input['user_id'];
        $client_id = $input['client_id'];
        $super_admin_userid = $input['super_admin_userid'];

        // If remarks not added then add that only by superadmin
        if ($user_id == $super_admin_userid) {

            // Check remark found or not
            $client_remark_check = ClientRemarks::checkClientRemark($input["content"]);
            if (isset($client_remark_check) && $client_remark_check != '') {

            }
            else {
                $client_remarks = new ClientRemarks();
                $client_remarks->remarks = $input["content"];
                $client_remarks->save();
            }
        }

        $response = Post::updatePost($post_id,$input["content"]);
        $returnValue["success"] = true;
        $returnValue["message"] = "Remarks Updated";
        $returnValue["id"] = $post_id;

        // Update in Client Basicinfo Table
        $get_latest_remarks = ClientBasicinfo::getLatestRemarks($client_id);

        $client_info = ClientBasicinfo::find($client_id);
        $client_info->latest_remarks = $get_latest_remarks;
        $client_info->save();

       return redirect()->route('client.remarks',[$client_id]);
    }

    public function updateComment() {

        $client_id = $_POST['client_id'];
        $id = $_POST['id'];
        $content = $_POST['content'];
        $super_admin_userid = $_POST['super_admin_userid'];
        $user_id = \Auth::user()->id;

        // If remarks not added then add that only by superadmin
        if ($user_id == $super_admin_userid) {

            // Check remark found or not
            $client_remark_check = ClientRemarks::checkClientRemark($content);
            if (isset($client_remark_check) && $client_remark_check != '') {

            }
            else {
                $client_remarks = new ClientRemarks();
                $client_remarks->remarks = $content;
                $client_remarks->save();
            }
        }

        $response['returnvalue'] = 'invalid';

        $res = Comments::updateComment($id,$content);

        if($res) {
            $response['returnvalue'] = 'valid';
        }

        // Update in Client Basicinfo Table
        $get_latest_remarks = ClientBasicinfo::getLatestRemarks($client_id);

        $client_info = ClientBasicinfo::find($client_id);
        $client_info->latest_remarks = $get_latest_remarks;
        $client_info->save();

        return json_encode($response);exit;
    }

    public function commentDestroy($id) {

        $response['returnvalue'] = 'invalid';
        $res = Comments::deleteComment($id);

        if($res) {
            $response['returnvalue'] = 'valid';
        }
        $client_id = $_POST['client_id'];

        // Update in Client Basicinfo Table
        $get_latest_remarks = ClientBasicinfo::getLatestRemarks($client_id);

        $client_info = ClientBasicinfo::find($client_id);
        $client_info->latest_remarks = $get_latest_remarks;
        $client_info->save();

        return json_encode($response);exit;
    }

    public function postDestroy($id) {

        $response['returnvalue'] = 'invalid';
        $res = Post::deletePost($id);

        if($res) {
            \DB::table('comments')->where('commentable_id', '=', $id)->delete();
            $response['returnvalue'] = 'valid';
        }

        $client_id = $_POST['client_id'];

        // Update in Client Basicinfo Table
        $get_latest_remarks = ClientBasicinfo::getLatestRemarks($client_id);

        $client_info = ClientBasicinfo::find($client_id);
        $client_info->latest_remarks = $get_latest_remarks;
        $client_info->save();

        return json_encode($response);exit;
    }

    public function postClientStatus() {

        $status_id = $_POST['status_id'];

        $client_ids = $_POST['status_client_ids'];
        $client_ids_array = explode(",",$client_ids);

        $source = $_POST['status_source'];

        foreach($client_ids_array as $key => $value) {

            // Update New Status
            $client = ClientBasicinfo::find($value);
            $client->status = $status_id;
            $client->save();
        }

        if(isset($source) && $source != '') {

            return redirect('/client-list/'.$source)->with('success', 'Status Changed Successfully.');
        }
        else {
            return redirect()->route('client.index')->with('success', 'Status Changed Successfully.');
        }
    }

    public function masterSearch() {

        $user =  \Auth::user();
        $user_id =  $user->id;

        $all_perm = $user->can('display-client');
        $userwise_perm = $user->can('display-account-manager-wise-client');

        if($all_perm) {
            $client_array = ClientBasicinfo::getAllClients(1,$user_id,0,0,0,0,'','','','','','','','');
            $count = sizeof($client_array);
        }
        else if($userwise_perm) {
            $client_array = ClientBasicinfo::getAllClients(0,$user_id,0,0,0,0,'','','','','','','','');
            $count = sizeof($client_array);
        }

        $i = 0;
        $active = 0;
        $passive = 0;
        $leaders = 0;
        $forbid = 0;
        $left = 0;
        $para_cat = 0;
        $mode_cat = 0;
        $std_cat = 0;

        foreach($client_array as $client) {

            if($client['status'] == 'Active') {
                $active++;
            }
            else if ($client['status'] == 'Passive') {
                $passive++;
            }
            else if($client['status'] == 'Leaders') {
                $leaders++;
            }
            else if($client['status'] == 'Forbid') {
                $forbid++;
            }
            else if($client['status'] == 'Left') {
                $left++;
            }

            if($client['category'] == 'Paramount') {
                $para_cat++;
            }
            else if($client['category'] == 'Moderate') {
                $mode_cat++;
            }
            else if($client['category'] == 'Standard') {
                $std_cat++;
            }
        }

        $users_array = User::getAllUsers(NULL,'Yes');
        $all_account_manager = array();
        
        if(isset($users_array) && sizeof($users_array) > 0) {

            foreach ($users_array as $k1 => $v1) {
                               
                $user_details = User::getAllDetailsByUserID($k1);

                if($user_details->type == '2') {
                    if($user_details->hr_adv_recruitemnt == 'Yes') {
                        $all_account_manager[$k1] = $v1;
                    }
                }
                else {
                    $all_account_manager[$k1] = $v1;
                }    
            }
        }

        $all_account_manager[0] = 'Yet to Assign';

        $email_template_names = EmailTemplate::getAllEmailTemplateNames($user_id);

        // Get clients for popup of add information
        $client_name_string = ClientBasicinfo::getBefore7daysClientDetails($user_id);

        // For not display superadmin popup
        $superadmin = getenv('SUPERADMINUSERID');
        $manager = getenv('MANAGERUSERID');

        // Get Client Status
        $status = ClientBasicinfo::getAllStatus();
        $status_id = 1;

        // Get Master Search Field List
        $field_list = ClientBasicinfo::getFieldsList();

        // Get Managed By Person List
        $users = array();
        
        if(isset($users_array) && sizeof($users_array) > 0) {

            $users[''] = 'Select User';

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

        // Get Category List
        $category_list = ClientBasicinfo::getCategory();

        // Get Status List
        $all_status[''] = 'Select Status';
        $all_status[1] = 'Active';
        $all_status[0] = 'Passive';
        $all_status[2] = 'Leaders';
        $all_status[4] = 'Left';

        // Get Client Industry

        $industry_res = Industry::orderBy('name','ASC')->get();
        $industry = array();

        if(sizeof($industry_res) > 0) {

            $industry[''] = 'Select Industry';

            foreach($industry_res as $r) {
                $industry[$r->id] = $r->name;
            }
        }

        return view('adminlte::client.searchindex',compact('count','active','passive','leaders','forbid','left','para_cat','mode_cat','std_cat','all_account_manager','email_template_names','client_name_string','user_id','superadmin','manager','status','status_id','field_list','users','category_list','all_status','industry'));
    }

    public function getAllClientsDetailsBySearch() {

        $draw = $_GET['draw'];
        $limit = $_GET['length'];
        $offset = $_GET['start'];
        $search = $_GET['search']['value'];
        $order = $_GET['order'][0]['column'];
        $type = $_GET['order'][0]['dir'];

        $client_owner = $_GET['client_owner'];
        $client_company = $_GET['client_company'];
        $client_contact_point = $_GET['client_contact_point'];
        $client_cat = $_GET['client_cat'];
        $client_status = $_GET['client_status'];
        $client_city = $_GET['client_city'];
        $client_industry = $_GET['client_industry'];
        
        $user =  \Auth::user();
        $all_perm = $user->can('display-client');
        $userwise_perm = $user->can('display-account-manager-wise-client');
        $edit_perm = $user->can('client-edit');
        $delete_perm = $user->can('client-delete');
        $category_perm = $user->can('display-client-category-in-client-list');

        if($all_perm) {

            $order_column_name = self::getOrderColumnName($order);
            $client_res = ClientBasicinfo::getAllClients(1,$user->id,$limit,$offset,$search,$order_column_name,$type,$client_owner,$client_company,$client_contact_point,$client_cat,$client_status,$client_city,$client_industry);
            
            $count = ClientBasicinfo::getAllClientsCount(1,$user->id,$search,$client_owner,$client_company,$client_contact_point,$client_cat,$client_status,$client_city,$client_industry);

            $client_res_array = ClientBasicinfo::getAllClients(1,$user->id,0,0,'',$order_column_name,$type,$client_owner,$client_company,$client_contact_point,$client_cat,$client_status,$client_city,$client_industry);
        }
        else if($userwise_perm) {

            $order_column_name = self::getOrderColumnName($order);
            $client_res = ClientBasicinfo::getAllClients(0,$user->id,$limit,$offset,$search,$order_column_name,$type,$client_owner,$client_company,$client_contact_point,$client_cat,$client_status,$client_city,$client_industry);
            $count = ClientBasicinfo::getAllClientsCount(0,$user->id,$search,$client_owner,$client_company,$client_contact_point,$client_cat,$client_status,$client_city,$client_industry);

            $client_res_array = ClientBasicinfo::getAllClients(0,$user->id,0,0,'',$order_column_name,$type,$client_owner,$client_company,$client_contact_point,$client_cat,$client_status,$client_city,$client_industry);
        }
        
        $recruitment = getenv('RECRUITMENT');
        $hr_advisory = getenv('HRADVISORY');
        $management = getenv('MANAGEMENT');
        $type_array = array($recruitment,$hr_advisory,$management);

        $users_array = User::getAllUsers(NULL,'Yes');
        $account_manager = array();

        if(isset($users_array) && sizeof($users_array) > 0) {

            foreach ($users_array as $k1 => $v1) {
                               
                $user_details = User::getAllDetailsByUserID($k1);

                if($user_details->type == '2') {
                    if($user_details->hr_adv_recruitemnt == 'Yes') {
                        $account_manager[$k1] = $v1;
                    }
                }
                else {
                    $account_manager[$k1] = $v1;
                }    
            }
        }

        $account_manager[0] = 'Yet to Assign';

        $clients = array();
        $i = 0;$j = 0;

        foreach ($client_res as $key => $value) {

            $action = '';
            $action .= '<a title="Show" class="fa fa-circle"  href="'.route('client.show',$value['id']).'" style="margin:2px;"></a>'; 
        
            if($edit_perm) {

                $action .= '<a title="Edit" class="fa fa-edit" href="'.route('client.edit',$value['id']).'" style="margin:2px;"></a>';
            }
            if($delete_perm) {

                $delete_view = \View::make('adminlte::partials.deleteModalNew', ['data' => $value, 'name' => 'client','display_name'=>'Client']);
                $delete = $delete_view->render();
                $action .= $delete;

                if(isset($value['url']) && $value['url'] != '') {
                    $action .= '<a target="_blank" href="'.$value['url'].'"><i  class="fa fa-fw fa-download"></i></a>';
                }
            }
            if($all_perm) {

                $account_manager_view = \View::make('adminlte::partials.client_account_manager', ['data' => $value, 'name' => 'client', 'account_manager' => $account_manager, 'source' => '']);
                $account = $account_manager_view->render();
                $action .= $account;
            }

            if($userwise_perm) {

                $secondline_account_manager_view = \View::make('adminlte::partials.secondline_account_manager', ['data' => $value, 'name' => 'client', 'account_manager' => $account_manager, 'source' => '']);
                $secondline_account = $secondline_account_manager_view->render();
                $action .= $secondline_account;

            }
            if($all_perm || $value['client_owner']) {

                $action .= '<a title="Remarks" class="fa fa-plus"  href="'.route('client.remarks',$value['id']).'" style="margin:2px;"></a>';

                $days_array = ClientTimeline::getTimelineDetailsByClientId($value['id']);

                $timeline_view = \View::make('adminlte::partials.client_timeline_view', ['data' => $value,'days_array' => $days_array]);
                $timeline = $timeline_view->render();
                $action .= $timeline;
            }

            $checkbox = '<input type=checkbox name=client value='.$value['id'].' class=others_client id='.$value['id'].'/>';
            $company_name = '<a style="white-space: pre-wrap; word-wrap: break-word; color:black; text-decoration:none;">'.$value['name'].'</a>';
            $contact_point = '<a style="white-space: pre-wrap; word-wrap: break-word; color:black; text-decoration:none;">'.$value['hr_name'].'</a>';
            $industry_name = '<a style="white-space: pre-wrap; word-wrap: break-word; color:black; text-decoration:none;">'.$value['industry_name'].'</a>';

            if($value['status'] == 'Active')
                $client_status = '<span class="label label-sm label-success">'.$value['status'].'</span></td>';
            else if($value['status'] == 'Passive')
                $client_status = '<span class="label label-sm label-danger">'.$value['status'].'</span></td>';
            else if($value['status'] == 'Leaders')
                $client_status = '<span class="label label-sm label-primary">'.$value['status'].'</span></td>';
            else if($value['status'] == 'Forbid')
                $client_status = '<span class="label label-sm label-default">'.$value['status'].'</span>';
            else if($value['status'] == 'Left')
                $client_status = '<span class="label label-sm label-info">'.$value['status'].'</span>';

            $client_category = $value['category'];

            if(isset($value['second_line_am_name']) && $value['second_line_am_name'] != '') {

                $am_name = $value['am_name']." | ".$value['second_line_am_name'];
            }
            else {
                $am_name = $value['am_name'];
            }

            if($category_perm) {

                $data = array(++$j,$checkbox,$action,$am_name,$company_name,$contact_point,$client_category,$client_status,$value['address'],$industry_name,$value['second_line_am']);
            }
            else {

                $data = array(++$j,$checkbox,$action,$am_name,$company_name,$contact_point,$client_status,$value['address'],$industry_name,$value['second_line_am']);
            }

            $clients[$i] = $data;
            $i++;
        }

        $active = 0;
        $passive = 0;
        $leaders = 0;
        $left = 0;
        $para_cat = 0;
        $mode_cat = 0;
        $std_cat = 0;

        foreach($client_res_array as $client) {

            if($client['status'] == 'Active') {
                $active++;
            }
            else if ($client['status'] == 'Passive') {
                $passive++;
            }
            else if($client['status'] == 'Leaders') {
                $leaders++;
            }
            else if($client['status'] == 'Left') {
                $left++;
            }

            if($client['category'] == 'Paramount') {
                $para_cat++;
            }
            else if($client['category'] == 'Moderate') {
                $mode_cat++;
            }
            else if($client['category'] == 'Standard') {
                $std_cat++;
            }
        }

        $json_data = array(
            'draw' => intval($draw),
            'recordsTotal' => intval($count),
            'recordsFiltered' => intval($count),
            "data" => $clients,
            "active_count" => $active,
            "passive_count" => $passive,
            "leaders_count" => $leaders,
            "left_count" => $left,
            "paramount_count" => $para_cat,
            "moderate_count" => $mode_cat,
            "standard_count" => $std_cat
        );

        echo json_encode($json_data);exit;
    }

    public function masterSearchByType($source) {

        $user =  \Auth::user();
        $all_perm = $user->can('display-client');
        $userwise_perm = $user->can('display-account-manager-wise-client');

        if($all_perm) {
            $clients = ClientBasicinfo::getAllClients(1,$user->id,0,0,0,0,'','','','','','','','');
            $count = '0';
        }
        else if($userwise_perm) {
            $clients = ClientBasicinfo::getAllClients(0,$user->id,0,0,0,0,'','','','','','','','');
            $count = '0';
        }

        $i = 0;
        $active = 0;
        $passive = 0;
        $leaders = 0;
        $forbid = 0;
        $left = 0;
        $para_cat = 0;
        $mode_cat = 0;
        $std_cat = 0;

        foreach($clients as $client) {

            if($client['status'] == 'Active' ) {
                $active++;
            }
            else if ($client['status'] == 'Passive') {
                $passive++;
            }
            else if($client['status'] == 'Leaders' ) {
                $leaders++;
            }
            else if($client['status'] == 'Forbid' ) {
                $forbid++;
            }
            else if($client['status'] == 'Left' ) {
                $left++;
            }

            if($client['category'] == 'Paramount') {
                $para_cat++;
            }
            else if($client['category'] == 'Moderate') {
                $mode_cat++;
            }
            else if($client['category'] == 'Standard') {
                $std_cat++;
            }
        }

        $email_template_names = EmailTemplate::getAllEmailTemplateNames($user->id);

        $recruitment = getenv('RECRUITMENT');
        $hr_advisory = getenv('HRADVISORY');
        $management = getenv('MANAGEMENT');
        $type_array = array($recruitment,$hr_advisory,$management);

        $users_array = User::getAllUsers(NULL,'Yes');
        $all_account_manager = array();

        if(isset($users_array) && sizeof($users_array) > 0) {

            foreach ($users_array as $k1 => $v1) {
                               
                $user_details = User::getAllDetailsByUserID($k1);

                if($user_details->type == '2') {
                    if($user_details->hr_adv_recruitemnt == 'Yes') {
                        $all_account_manager[$k1] = $v1;
                    }
                }
                else {
                    $all_account_manager[$k1] = $v1;
                }    
            }
        }

        $all_account_manager[0] = 'Yet to Assign';

        // Get Client Status

        $status = ClientBasicinfo::getAllStatus();
        $status_id = 1;

        // Get Master Search Field List
        $field_list = ClientBasicinfo::getFieldsList();

        // Get Managed By Person List
        $users = array();
        
        if(isset($users_array) && sizeof($users_array) > 0) {

            $users[''] = 'Select User';

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

        // Get Category List
        $category_list = ClientBasicinfo::getCategory();

        // Get Status List
        $all_status[''] = 'Select Status';
        $all_status[1] = 'Active';
        $all_status[0] = 'Passive';
        $all_status[2] = 'Leaders';
        $all_status[4] = 'Left';

        // Get Client Industry

        $industry_res = Industry::orderBy('name','ASC')->get();
        $industry = array();

        if(sizeof($industry_res) > 0) {

            $industry[''] = 'Select Industry';

            foreach($industry_res as $r) {
                $industry[$r->id] = $r->name;
            }
        }

        return view('adminlte::client.searchclienttypeindex',compact('active','passive','leaders','forbid','left','para_cat','mode_cat','std_cat','source','count','email_template_names','all_account_manager','status','status_id','field_list','users','category_list','all_status','industry'));
    }

    public function sendHiringReport() {

        if(isset($_POST['to_date']) && $_POST['to_date'] != '') {

            $to_date = date('Y-m-d',strtotime($_POST['to_date']));
        }
        else {
            
            $to_date = date('Y-m-d');
        }

        if(isset($_POST['from_date']) && $_POST['from_date'] != '') {

            $from_date = date('Y-m-d',strtotime($_POST['from_date']));
        }
        else {

            $from_date = date('Y-m-d',strtotime("$to_date -15days"));
        }

        $client_id = $_POST['client_id'];
        $page_nm = $_POST['page_nm'];
        $source = $_POST['source'];

        $user =  \Auth::user();
        $user_id = $user->id;
        $today_date = date('d-m-Y');
        $client_res = ClientBasicinfo::getClientDetailsById($client_id);

        $client_jobs = JobOpen::getAllJobsByCLient($client_id);

        if(isset($client_jobs) && sizeof($client_jobs) > 0) {

            $list_array = array();
            $j = 0;

            foreach ($client_jobs as $client_jobs_key => $client_jobs_value) {

                $associate_candidates = JobAssociateCandidates::getAssociatedCandidatesByWeek($client_jobs_value['id'],$from_date,$to_date);

                $list_array[$j]['associate_candidates'] = $associate_candidates;

                $shortlisted_candidates = JobAssociateCandidates::getShortlistedCandidatesByWeek($client_jobs_value['id'],$from_date,$to_date);

                $list_array[$j]['shortlisted_candidates'] = $shortlisted_candidates;

                $attended_interviews = Interview::getAttendedInterviewsByWeek($client_jobs_value['id'],$from_date,$to_date);

                $list_array[$j]['attended_interviews'] = $attended_interviews;

                $job_details = JobOpen::getJobById($client_jobs_value['id']);
                $list_array[$j]['posting_title'] = $job_details['posting_title'] . " - " . $job_details['city'];

                $j++;
            }
        }

        if(isset($list_array) && sizeof($list_array) > 0) {

            $input = array();

            $from_name = getenv('FROM_NAME');
            $from_address = getenv('FROM_ADDRESS');

            $client_name = $client_res['coordinator_name'];
            $company_name = $client_res['name'];

            $to = User::getUserEmailById($user_id);
            $subject = "Adler : Hiring Report_".$today_date." | ".$company_name;
                    
            $input['from_name'] = $from_name;
            $input['from_address'] = $from_address;
            $input['to'] = $to;
            $input['subject'] = $subject;
            $input['list_array'] = $list_array;
            $input['client_name'] = $client_name;

            \Mail::send('adminlte::emails.clientautogeneratereportemail', $input, function ($message) use($input) {

                $message->from($input['from_address'], $input['from_name']);
                $message->to($input['to'])->subject($input['subject']);
            });


            if($page_nm == 'AM') {

                return redirect()->route('clientlist.amwise')->with('success','Hiring Report Sent Successfully.');
            }
            else if($page_nm == 'Type') {

                return redirect('/client-list/'.$source)->with('success', 'Hiring Report Sent Successfully.');
            }
            else {

                return redirect()->route('client.index')->with('success','Hiring Report Sent Successfully.');
            }
        }

        else {

            if($page_nm == 'AM') {

                return redirect()->route('clientlist.amwise')->with('error','There are no active positions.');
            }
            else if($page_nm == 'Type') {

                return redirect('/client-list/'.$source)->with('error', 'There are no active positions.');
            }
            else {
                
                return redirect()->route('client.index')->with('error','There are no active positions.');
            }
        }
    }
}