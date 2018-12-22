<?php

namespace App\Http\Controllers;

use App\Utils;
use Illuminate\Http\Request;
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

class ClientController extends Controller
{
    public function index(Request $request){
        $utils = new Utils();
        $user =  \Auth::user();

        // get logged in user company id
        $company_id = $user->company_id;

        // get role of logged in user
        $userRole = $user->roles->pluck('id','id')->toArray();
        $role_id = key($userRole);

        $user_obj = new User();
        $isAdmin = $user_obj::isAdmin($role_id);
        $isSuperAdmin = $user_obj::isSuperAdmin($role_id);
        $isStrategy = $user_obj::isStrategyCoordination($role_id);
        $isAccountant = $user_obj::isAccountant($role_id);

        $rolePermissions = \DB::table("permission_role")->where("permission_role.role_id",key($userRole))
            ->pluck('permission_role.permission_id','permission_role.permission_id')->toArray();

        if($isSuperAdmin || $isAdmin || $isStrategy || $isAccountant){
            $client_array = ClientBasicinfo::getAllClients(1,$user->id,$rolePermissions);
            $count = sizeof($client_array);
        }
        else{
            $client_array = ClientBasicinfo::getAllClients(0,$user->id,$rolePermissions);
            $count = sizeof($client_array);
        }
        //print_r($client_array);exit;


        $account_manager=User::getAllUsers('recruiter');


        // if Super Admin get clients of all companies
        /*if($isSuperAdmin || $isAdmin || $isStrategy){
            $clients = \DB::table('client_basicinfo')
                ->join('client_address','client_address.client_id','=','client_basicinfo.id')
                ->join('users', 'users.id', '=', 'client_basicinfo.account_manager_id')
                ->leftJoin('client_doc',function($join){
                    $join->on('client_doc.client_id', '=', 'client_basicinfo.id');
                    $join->where('client_doc.category','=','Client Contract');
                })
                ->select('client_basicinfo.*', 'users.name as am_name','users.id as am_id','client_doc.file','client_address.billing_street2 as area','client_address.billing_city as city')
                ->orderBy('client_basicinfo.id','desc')
                ->groupBy('client_basicinfo.id')
                ->get();
        }
        else{
            $clients = \DB::table('client_basicinfo')
                ->join('client_address','client_address.client_id','=','client_basicinfo.id')
                ->join('users', 'users.id', '=', 'client_basicinfo.account_manager_id')
                ->select('client_basicinfo.*', 'users.name as am_name','users.id as am_id','client_address.billing_street2 as area','client_address.billing_city as city')
                ->where('account_manager_id',$user->id)
                ->orderBy('client_basicinfo.id','desc')
                ->groupBy('client_basicinfo.id')
                ->get();
        }

        $count = sizeof($clients);

        $rolePermissions = \DB::table("permission_role")->where("permission_role.role_id",key($userRole))
            ->pluck('permission_role.permission_id','permission_role.permission_id')->toArray();

        // if logged in user has CLIENTVISIBILITY permission he can access all clients of his compnay
        $client_visibility = false;
        $client_visibility_id = env('CLIENTVISIBILITY');
        if(isset($client_visibility_id) && in_array($client_visibility_id,$rolePermissions)){
            $client_visibility = true;
        }

        $client_array = array();*/
        $i = 0;
        $active = 0;
        $passive = 0;
        $para_cat = 0;
        $mode_cat = 0;
        $std_cat = 0;
        foreach($client_array as $client){

           /* $client_array[$i]['id'] = $client->id;
            $client_array[$i]['name'] = $client->name;
            $client_array[$i]['am_name'] = $client->am_name;
            $client_array[$i]['category']=$client->category;
            $client_array[$i]['status']=$client->status;
            $client_array[$i]['account_mangr_id']=$client->account_manager_id;*/

            
            if($client['status'] == 'Active' ){
                $active++;
            }
            else if ($client['status'] == 'Passive'){
                $passive++;
            }

            if($client['category'] == 'Paramount')
            {
                $para_cat++;
            }
            else if($client['category'] == 'Moderate')
            {
                $mode_cat++;
            }
            else if($client['category'] == 'Standard')
            {
                $std_cat++;
            }

            /*if(isset($client_array[$i]['status']))
            {
                if($client_array[$i]['status']== '1')
                {
                  $client_array[$i]['status']='Active';
                }
                else
                {
                  $client_array[$i]['status']='Passive';
                }
            }*/
            
            /*$client_array[$i]['mobile']= $client->mobile;
            $client_array[$i]['hr_name'] = $client->coordinator_prefix . " " . $client->coordinator_name;

            $address ='';
            if($client->area!=''){
                $address .= $client->area;
            }
            if($client->city!=''){
                if($address=='')
                    $address .= $client->city;
                else
                    $address .= ", ".$client->city;
            }

            $client_array[$i]['address'] = $address;
           // $client_array[$i]['convert_client'] = $client->convert_client;

            if($client->am_id==$user->id){
                $client_visibility_val = true;
                $client_array[$i]['client_owner'] = true;
            }
            else {
                $client_visibility_val = $client_visibility;
                $client_array[$i]['client_owner'] = false;
            }

            if($client_visibility_val)
                $client_array[$i]['mail'] = $client->mail;
            else
                $client_array[$i]['mail'] = '';//$utils->mask_email($client->mail,'X',80);

            $client_array[$i]['client_visibility'] = $client_visibility_val;

            if($isSuperAdmin || $isAdmin){
                $client_array[$i]['url'] = $client->file;
            }
            else{
                $client_array[$i]['url'] = '';
            }
            $i++;*/
        }



       /* $selected_acc_mnger=$request->get('account_manager');

        if(isset($selected_acc_mnger))
        {
            echo $selected_acc_mnger;
            exit;
        }*/

        

        /*$active_client=\DB::table('client_basicinfo')
                ->select('client_basicinfo.*')
                ->where('status','=','1')
                ->get();

        $active=sizeof($active_client);*/


       /* $paramount_client=\DB::table('client_basicinfo')
                ->select('client_basicinfo.category')
                ->where('category','=','Standard')
                ->get();

        $passive=sizeof($paramount_client);
        */
        return view('adminlte::client.index',compact('client_array','isAdmin','isSuperAdmin','count','active','passive','isStrategy','account_manager','para_cat','mode_cat','std_cat'));
    }

    public static function getOrderColumnName($order,$admin){
        $order_column_name = '';
        if($admin){
            if (isset($order) && $order >= 0) {
                if ($order == 1) {
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
                    $order_column_name = "client_address.billing_street2";
                }
            }
        }
        else{
            if (isset($order) && $order >= 0) {
                if ($order == 1) {
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
                    $order_column_name = "client_basicinfo.status";
                }
                else if ($order == 6) {
                    $order_column_name = "client_address.billing_street2";
                }
            }
        }
        return $order_column_name;
    }

    public function getAllClientsDetails(){

        $draw = $_GET['draw'];
        $limit = $_GET['length'];
        $offset = $_GET['start'];
        $search = $_GET['search']['value'];
        $order = $_GET['order'][0]['column'];
        $type = $_GET['order'][0]['dir'];

        $user =  \Auth::user();
        $userRole = $user->roles->pluck('id','id')->toArray();
        $role_id = key($userRole);

        $user_obj = new User();
        $isAdmin = $user_obj::isAdmin($role_id);
        $isSuperAdmin = $user_obj::isSuperAdmin($role_id);
        $isStrategy = $user_obj::isStrategyCoordination($role_id);
        $isAccountant = $user_obj::isAccountant($role_id);

        $rolePermissions = \DB::table("permission_role")->where("permission_role.role_id",key($userRole))
            ->pluck('permission_role.permission_id','permission_role.permission_id')->toArray();

        if($isSuperAdmin || $isAdmin || $isStrategy || $isAccountant){
            $order_column_name = self::getOrderColumnName($order,1);
            $client_res = ClientBasicinfo::getAllClients(1,$user->id,$rolePermissions,$limit,$offset,$search,$order_column_name,$type);
            $count = ClientBasicinfo::getAllClientsCount(1,$user->id,$search);
        }
        else{

            $order_column_name = self::getOrderColumnName($order,0);
            $client_res = ClientBasicinfo::getAllClients(0,$user->id,$rolePermissions,$limit,$offset,$search,$order_column_name,$type);
            $count = ClientBasicinfo::getAllClientsCount(0,$user->id,$search);
        }
        //print_r($client_array);exit;
        $account_manager=User::getAllUsers('recruiter','Yes');

        $clients = array();
        $i = 0;
        foreach ($client_res as $key => $value) {
            $action = '';
            if($isSuperAdmin || $isAdmin || $isStrategy || $value['client_visibility'] || $isAccountant){
                $action .= '<a title="Show" class="fa fa-circle"  href="'.route('client.show',$value['id']).'" style="margin:2px;"></a>'; 
            }
            if($isSuperAdmin || $isAdmin || $isStrategy || $value['client_owner']){
                $action .= '<a title="Edit" class="fa fa-edit" href="'.route('client.edit',$value['id']).'" style="margin:2px;"></a>';
            }
            if($isSuperAdmin){
                $delete_view = \View::make('adminlte::partials.deleteModalNew', ['data' => $value, 'name' => 'client','display_name'=>'Client']);
                $delete = $delete_view->render();
                $action .= $delete;
                if(isset($value['url']) && $value['url']!=''){
                    $action .= '<a target="_blank" href="'.$value['url'].'"><i  class="fa fa-fw fa-download"></i></a>';
                }
            }
            if($isSuperAdmin || $isStrategy ){
                $account_manager_view = \View::make('adminlte::partials.client_account_manager', ['data' => $value, 'name' => 'client','display_name'=>'More Information', 'account_manager' => $account_manager]);
                $account = $account_manager_view->render();
                $action .= $account;
            }

            $checkbox = '<input type=checkbox name=client value='.$value['id'].' class=others_client id='.$value['id'].'/>';
            $company_name = '<a style="white-space: pre-wrap; word-wrap: break-word; color:black; text-decoration:none;">'.$value['name'].'</a>';
            if($isSuperAdmin || $isStrategy ){
                $client_category = $value['category'];
            }
            if($value['status']=='Active')
                $client_status = '<span class="label label-sm label-success">'.$value['status'].'</span></td>';
            else
                $client_status = '<span class="label label-sm label-danger">'.$value['status'].'</span>';
            if($isSuperAdmin || $isStrategy ){
                $data = array($checkbox,$action,$value['am_name'],$company_name,$value['hr_name'],$client_category,$client_status,$value['address']);
            }
            else{
                $data = array($checkbox,$action,$value['am_name'],$company_name,$value['hr_name'],$client_status,$value['address']);
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

    public function ActiveClient(){

        $utils = new Utils();
        $user =  \Auth::user();

        // get logged in user company id
        $company_id = $user->company_id;

        // get role of logged in user
        $userRole = $user->roles->pluck('id','id')->toArray();
        $role_id = key($userRole);

        $user_obj = new User();
        $isAdmin = $user_obj::isAdmin($role_id);
        $isSuperAdmin = $user_obj::isSuperAdmin($role_id);
        $isStrategy = $user_obj::isStrategyCoordination($role_id);
        $isAccountant = $user_obj::isAccountant($role_id);

        $rolePermissions = \DB::table("permission_role")->where("permission_role.role_id",key($userRole))
            ->pluck('permission_role.permission_id','permission_role.permission_id')->toArray();

        if($isSuperAdmin || $isAdmin || $isStrategy || $isAccountant){
            $client_array = ClientBasicinfo::getClientsByType(1,$user->id,$rolePermissions,1);
            $count = sizeof($client_array);

            $clients = ClientBasicinfo::getAllClients(1,$user->id,$rolePermissions);
        }
        else{
            $client_array = ClientBasicinfo::getClientsByType(0,$user->id,$rolePermissions,1);
            $count = sizeof($client_array);

            $clients = ClientBasicinfo::getAllClients(0,$user->id,$rolePermissions);
        }
        $i = 0;
        $active = 0;
        $passive = 0;
        $para_cat = 0;
        $mode_cat = 0;
        $std_cat = 0;
        foreach($clients as $client){
            if($client['status'] == 'Active' ){
                $active++;
            }
            else if ($client['status'] == 'Passive'){
                $passive++;
            }

            if($client['category'] == 'Paramount'){
                $para_cat++;
            }
            else if($client['category'] == 'Moderate'){
                $mode_cat++;
            }
            else if($client['category'] == 'Standard'){
                $std_cat++;
            }
        }

        $account_manager=User::getAllUsers('recruiter');

        $source = 'Active';
        return view('adminlte::client.clienttypeindex',compact('client_array','isAdmin','isSuperAdmin','count','active','passive','isStrategy','para_cat','mode_cat','std_cat','source','account_manager','isAccountant'));
    }

    public function PassiveClient(){

        $utils = new Utils();
        $user =  \Auth::user();

        // get logged in user company id
        $company_id = $user->company_id;

        // get role of logged in user
        $userRole = $user->roles->pluck('id','id')->toArray();
        $role_id = key($userRole);

        $user_obj = new User();
        $isAdmin = $user_obj::isAdmin($role_id);
        $isSuperAdmin = $user_obj::isSuperAdmin($role_id);
        $isStrategy = $user_obj::isStrategyCoordination($role_id);
        $isAccountant = $user_obj::isAccountant($role_id);

        $rolePermissions = \DB::table("permission_role")->where("permission_role.role_id",key($userRole))
            ->pluck('permission_role.permission_id','permission_role.permission_id')->toArray();

        if($isSuperAdmin || $isAdmin || $isStrategy || $isAccountant){
            $client_array = ClientBasicinfo::getClientsByType(1,$user->id,$rolePermissions,0);
            $count = sizeof($client_array);

            $clients = ClientBasicinfo::getAllClients(1,$user->id,$rolePermissions);
        }
        else{
            $client_array = ClientBasicinfo::getClientsByType(0,$user->id,$rolePermissions,0);
            $count = sizeof($client_array);

            $clients = ClientBasicinfo::getAllClients(0,$user->id,$rolePermissions);
        }
        $i = 0;
        $active = 0;
        $passive = 0;
        $para_cat = 0;
        $mode_cat = 0;
        $std_cat = 0;
        foreach($clients as $client){
            if($client['status'] == 'Active' ){
                $active++;
            }
            else if ($client['status'] == 'Passive'){
                $passive++;
            }

            if($client['category'] == 'Paramount'){
                $para_cat++;
            }
            else if($client['category'] == 'Moderate'){
                $mode_cat++;
            }
            else if($client['category'] == 'Standard'){
                $std_cat++;
            }
        }

        $account_manager=User::getAllUsers('recruiter');

        $source = 'Passive';
        return view('adminlte::client.clienttypeindex',compact('client_array','isAdmin','isSuperAdmin','count','active','passive','isStrategy','para_cat','mode_cat','std_cat','source','account_manager','isAccountant'));
    }

    public function ParamountClient(){

        $utils = new Utils();
        $user =  \Auth::user();
        $category = 'Paramount';
        // get logged in user company id
        $company_id = $user->company_id;

        // get role of logged in user
        $userRole = $user->roles->pluck('id','id')->toArray();
        $role_id = key($userRole);

        $user_obj = new User();
        $isAdmin = $user_obj::isAdmin($role_id);
        $isSuperAdmin = $user_obj::isSuperAdmin($role_id);
        $isStrategy = $user_obj::isStrategyCoordination($role_id);

        $rolePermissions = \DB::table("permission_role")->where("permission_role.role_id",key($userRole))
            ->pluck('permission_role.permission_id','permission_role.permission_id')->toArray();

        if($isSuperAdmin || $isAdmin || $isStrategy){
            $client_array = ClientBasicinfo::getClientsByType(1,$user->id,$rolePermissions,NULL,$category);
            $count = sizeof($client_array);

            $clients = ClientBasicinfo::getAllClients(1,$user->id,$rolePermissions);
        }
        else{
            $client_array = ClientBasicinfo::getClientsByType(0,$user->id,$rolePermissions,NULL,$category);
            $count = sizeof($client_array);

            $clients = ClientBasicinfo::getAllClients(0,$user->id,$rolePermissions);
        }
        $i = 0;
        $active = 0;
        $passive = 0;
        $para_cat = 0;
        $mode_cat = 0;
        $std_cat = 0;
        foreach($clients as $client){
            if($client['status'] == 'Active' ){
                $active++;
            }
            else if ($client['status'] == 'Passive'){
                $passive++;
            }

            if($client['category'] == 'Paramount'){
                $para_cat++;
            }
            else if($client['category'] == 'Moderate'){
                $mode_cat++;
            }
            else if($client['category'] == 'Standard'){
                $std_cat++;
            }
        }

        $account_manager=User::getAllUsers('recruiter');

        $source = 'Paramount';
        return view('adminlte::client.clienttypeindex',compact('client_array','isAdmin','isSuperAdmin','count','active','passive','isStrategy','para_cat','mode_cat','std_cat','source','account_manager'));
    }

    public function ModerateClient(){

        $utils = new Utils();
        $user =  \Auth::user();
        $category = 'Moderate';
        // get logged in user company id
        $company_id = $user->company_id;

        // get role of logged in user
        $userRole = $user->roles->pluck('id','id')->toArray();
        $role_id = key($userRole);

        $user_obj = new User();
        $isAdmin = $user_obj::isAdmin($role_id);
        $isSuperAdmin = $user_obj::isSuperAdmin($role_id);
        $isStrategy = $user_obj::isStrategyCoordination($role_id);

        $rolePermissions = \DB::table("permission_role")->where("permission_role.role_id",key($userRole))
            ->pluck('permission_role.permission_id','permission_role.permission_id')->toArray();

        if($isSuperAdmin || $isAdmin || $isStrategy){
            $client_array = ClientBasicinfo::getClientsByType(1,$user->id,$rolePermissions,NULL,$category);
            $count = sizeof($client_array);

            $clients = ClientBasicinfo::getAllClients(1,$user->id,$rolePermissions);
        }
        else{
            $client_array = ClientBasicinfo::getClientsByType(0,$user->id,$rolePermissions,NULL,$category);
            $count = sizeof($client_array);

            $clients = ClientBasicinfo::getAllClients(0,$user->id,$rolePermissions);
        }
        $i = 0;
        $active = 0;
        $passive = 0;
        $para_cat = 0;
        $mode_cat = 0;
        $std_cat = 0;
        foreach($clients as $client){
            if($client['status'] == 'Active' ){
                $active++;
            }
            else if ($client['status'] == 'Passive'){
                $passive++;
            }

            if($client['category'] == 'Paramount'){
                $para_cat++;
            }
            else if($client['category'] == 'Moderate'){
                $mode_cat++;
            }
            else if($client['category'] == 'Standard'){
                $std_cat++;
            }
        }

        $account_manager=User::getAllUsers('recruiter');

        $source = 'Moderate';
        return view('adminlte::client.clienttypeindex',compact('client_array','isAdmin','isSuperAdmin','count','active','passive','isStrategy','para_cat','mode_cat','std_cat','source','account_manager'));
    }

    public function StandardClient(){

        $utils = new Utils();
        $user =  \Auth::user();
        $category = 'Standard';
        // get logged in user company id
        $company_id = $user->company_id;

        // get role of logged in user
        $userRole = $user->roles->pluck('id','id')->toArray();
        $role_id = key($userRole);

        $user_obj = new User();
        $isAdmin = $user_obj::isAdmin($role_id);
        $isSuperAdmin = $user_obj::isSuperAdmin($role_id);
        $isStrategy = $user_obj::isStrategyCoordination($role_id);

        $rolePermissions = \DB::table("permission_role")->where("permission_role.role_id",key($userRole))
            ->pluck('permission_role.permission_id','permission_role.permission_id')->toArray();

        if($isSuperAdmin || $isAdmin || $isStrategy){
            $client_array = ClientBasicinfo::getClientsByType(1,$user->id,$rolePermissions,NULL,$category);
            $count = sizeof($client_array);

            $clients = ClientBasicinfo::getAllClients(1,$user->id,$rolePermissions);
        }
        else{
            $client_array = ClientBasicinfo::getClientsByType(0,$user->id,$rolePermissions,NULL,$category);
            $count = sizeof($client_array);

            $clients = ClientBasicinfo::getAllClients(0,$user->id,$rolePermissions);
        }
        $i = 0;
        $active = 0;
        $passive = 0;
        $para_cat = 0;
        $mode_cat = 0;
        $std_cat = 0;
        foreach($clients as $client){
            if($client['status'] == 'Active' ){
                $active++;
            }
            else if ($client['status'] == 'Passive'){
                $passive++;
            }

            if($client['category'] == 'Paramount'){
                $para_cat++;
            }
            else if($client['category'] == 'Moderate'){
                $mode_cat++;
            }
            else if($client['category'] == 'Standard'){
                $std_cat++;
            }
        }

        $account_manager=User::getAllUsers('recruiter');

        $source = 'Standard';
        return view('adminlte::client.clienttypeindex',compact('client_array','isAdmin','isSuperAdmin','count','active','passive','isStrategy','para_cat','mode_cat','std_cat','source','account_manager'));
    }

    public function create()
    {

        $co_prefix=ClientBasicinfo::getcoprefix();
        $co_category='';

        $client_cat=ClientBasicinfo::getCategory();
        $client_category='';

        $generate_lead = '1';
        $industry_res = Industry::orderBy('id','DESC')->get();
        $industry = array();

        $user = \Auth::user();
        $userRole = $user->roles->pluck('id','id')->toArray();
        $role_id = key($userRole);

        $user_obj = new User();
        $isAdmin = $user_obj::isAdmin($role_id);
        $isSuperAdmin = $user_obj::isSuperAdmin($role_id);
        $isStrategy = $user_obj::isStrategyCoordination($role_id);
        $user_id = $user->id;

        // For account manager
         $users = User::getAllUsers('recruiter','Yes');

        if(sizeof($industry_res)>0){
            foreach($industry_res as $r){
                $industry[$r->id]=$r->name;
            }
        }

        // User Account Manager access check
        $user_acc_manager = \Auth::user()->account_manager;
        if ($user_acc_manager == 'No') {
            return view('errors.clientpermission');
        }

        $industry_id = '';

        $action = "add" ;
        return view('adminlte::client.create',compact('action','industry','users','isSuperAdmin','user_id','isAdmin','generate_lead','industry_id','co_prefix','co_category','client_cat','client_category','isStrategy'));
    }


    public function postClientNames()
    {
        $client_ids = $_GET['client_ids'];

        $client_ids_array=explode(",",$client_ids);

        $client = ClientBasicinfo::getClientInfo($client_ids);

        echo json_encode($client);exit;

    }
    public function edit($id)
    {

        $generate_lead = '1';

        $co_prefix=ClientBasicinfo::getcoprefix();
        $client_cat=ClientBasicinfo::getCategory();

        $user = \Auth::user();
        $userRole = $user->roles->pluck('id','id')->toArray();
        $role_id = key($userRole);

        $user_obj = new User();
        $isAdmin = $user_obj::isAdmin($role_id);
        $isSuperAdmin = $user_obj::isSuperAdmin($role_id);
        $isStrategy = $user_obj::isStrategyCoordination($role_id);
        $user_id = $user->id;

        $access_roles_id = array($isAdmin,$isSuperAdmin,$isStrategy);

        $industry_res = Industry::orderBy('id','DESC')->get();
        $industry = array();

        if(sizeof($industry_res)>0){
            foreach($industry_res as $r){
                $industry[$r->id]=$r->name;
            }
        }

        $client = array();
        $client_basicinfo  = \DB::table('client_basicinfo')
            ->join('users', 'users.id', '=', 'client_basicinfo.account_manager_id')
            ->leftjoin('industry', 'industry.id', '=', 'client_basicinfo.industry_id')
            ->select('client_basicinfo.*', 'users.name as am_name','users.id as am_id','industry.name as ind_name')
            ->where('client_basicinfo.id','=',$id)
            ->get();
    foreach ($client_basicinfo as $key=>$value)
    {
        if(in_array($role_id,$access_roles_id) || ($value->am_id==$user_id))
        {
            $client['name'] = $value->name;
            $client['display_name']=$value->display_name;
            $client['source'] = $value->source;
            //$client['fax'] = $value->fax;
            $client['mobile'] = $value->mobile;
            $client['other_number'] = $value->other_number;
            $client['am_name'] = $value->am_name;
            $client['mail'] = $value->mail;
            $client['s_email'] = $value->s_email;
            $client['ind_name'] = $value->ind_name;
            $client['website'] = $value->website;
            $client['description'] = $value->description;
            $client['gst_no'] = $value->gst_no;
            /*$client['tds'] = $value->tds;*/
            $client['coordinator_name'] = $value->coordinator_name;
            $co_category=$value->coordinator_prefix;
            $client['tan'] = $value->tan;
            $client['percentage_charged_below']=$value->percentage_charged_below;

            $client['percentage_charged_above']=$value->percentage_charged_above;

            $client_status=$value->status;

            $client_category=$value->category;
            /*echo $client_status;
            exit;*/
            $user_id = $value->account_manager_id;
            $industry_id = $value->industry_id;
        }
        else
        {
            return view('errors.403');
        }
    }
        
        $client['id'] = $id;

        $client_address = \DB::table('client_address')
            ->where('client_id','=',$id)
            ->get();

        foreach ($client_address as $key=>$value)
        {
            $client['billing_country'] = $value->billing_country;
            $client['billing_state'] = $value->billing_state;
            $client['billing_street1'] = $value->billing_street1;
            $client['billing_street2'] = $value->billing_street2;
            $client['billing_code'] = $value->billing_code;
            $client['billing_city'] = $value->billing_city;
            $client['shipping_country'] = $value->shipping_country;
            $client['shipping_state'] = $value->shipping_state;
            $client['shipping_street1'] = $value->shipping_street1;
            $client['shipping_street2'] = $value->shipping_street2;
            $client['shipping_code'] = $value->shipping_code;
            $client['shipping_city'] = $value->shipping_city;
            $client['client_address_id'] = $value->id;
        }

         $client = (object)$client;
        // For account manager 
         $users = User::getAllUsersWithInactive();

        $action = "edit" ;
        return view('adminlte::client.edit',compact('action','industry','client','users','user_id','isSuperAdmin','isStrategy','isAdmin','generate_lead','industry_id','co_prefix','co_category','client_status','client_cat','client_category'));
    }

    public function store(Request $request){

        $user_id = \Auth::user()->id;
        $user_name = \Auth::user()->name;
        $user_email = \Auth::user()->email;
        $input = $request->all();

        /*$co_prefix=$input['co_category'];*/

        $client_basic_info = new ClientBasicinfo();
        $client_basic_info->name = $input['name'];
        $client_basic_info->display_name = $input['display_name'];
        $client_basic_info->mail = $input['mail'];
        $client_basic_info->s_email = $input['s_email'];
        $client_basic_info->description = $input['description'];
        $client_basic_info->mobile = $input['mobile'];
        $client_basic_info->other_number = $input['other_number'];
        $client_basic_info->website = $input['website'];

        if(isset($input['percentage_charged_below']) && $input['percentage_charged_below']!= '' )
        {
            $client_basic_info->percentage_charged_below=$input['percentage_charged_below'];
        }
        else
        {
            $client_basic_info->percentage_charged_below=NULL;
        }
        
        if(isset($input['percentage_charged_above']) && $input['percentage_charged_above']!='' )
        {
            $client_basic_info->percentage_charged_above=$input['percentage_charged_above'];
        }
        else
        {
             $client_basic_info->percentage_charged_above=NULL;
        }
        
        $status = $input['status'];

        if($status=='1')
        {
            $client_basic_info->status='1';
        }
        else
        {
            $client_basic_info->status='0';
        }

        $client_basic_info->account_manager_id = $input['account_manager'];
        $client_basic_info->industry_id = $input['industry_id'];
        //$client_basic_info->source = $input['source'];
        $client_basic_info->about = $input['description'];
        if(isset($input['source']) && $input['source']!='')
            $client_basic_info->source = $input['source'];
        else
            $client_basic_info->source = '';
        if(isset($input['gst_no']) && $input['gst_no']!='')
            $client_basic_info->gst_no = $input['gst_no'];
        else
            $client_basic_info->gst_no = '';
        /*if(isset($input['tds']) && $input['tds']!='')
            $client_basic_info->tds = $input['tds'];
        else
            $client_basic_info->tds = '';*/
        if(isset($input['tan']) && $input['tan']!='')
            $client_basic_info->tan = $input['tan'];
        else
            $client_basic_info->tan = '';

        $client_basic_info->coordinator_name = $input['coordinator_name'];

        $client_basic_info->coordinator_prefix= $input['co_category'];

        if(isset($input['client_category']))
        {
            $client_basic_info->category=$input['client_category'];
        }
        else
        {
            $client_basic_info->category='';
        }
        
        $client_basic_info->created_at = time();
        $client_basic_info->updated_at = time();

        if($client_basic_info->save()){

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

            // TODO:: Notifications : On adding new client notify Super Admin via notification
            $module_id = $client_id;
            $module = 'Client';
            $message = $user_name . " added new Client";
            $link = route('client.show',$client_id);

            $super_admin_userid = getenv('SUPERADMINUSERID');
            $user_arr = array();
            $user_arr[] = $super_admin_userid;

            event(new NotificationEvent($module_id, $module, $message, $link, $user_arr));

            // Email Notification : data store in datebase
            $strategyuserid = getenv('STRATEGYUSERID');
            $superadminemail = User::getUserEmailById($super_admin_userid);
            $strategyemail = User::getUserEmailById($strategyuserid);
            $cc_users_array = array($superadminemail,$strategyemail);

            $module = "Client";
            $sender_name = $user_id;
            $to = $user_email;
            $subject = "New Client - " . $client_name . " - " . $input['billing_city'];
            $message = "<tr><td>" . $user_name . " added new Client </td></tr>";
            $module_id = $client_id;
            $cc = implode(",",$cc_users_array);

            event(new NotificationMail($module,$sender_name,$to,$subject,$message,$module_id,$cc));

            return redirect()->route('client.index')->with('success','Client Created Successfully');
        }
        else{
            return redirect('client/create')->withInput(Input::all())->withErrors($client_basic_info->errors());
        }
    }

    public function show($id)
    {

        $user = \Auth::user();
        $userRole = $user->roles->pluck('id','id')->toArray();
        $role_id = key($userRole);

        $user_obj = new User();
        $isAdmin = $user_obj::isAdmin($role_id);
        $isSuperAdmin = $user_obj::isSuperAdmin($role_id);
        $isStrategy = $user_obj::isStrategyCoordination($role_id);
        $isAccountant = $user_obj::isAccountant($role_id);
        $user_id = $user->id;

        $access_roles_id = array($isAdmin,$isSuperAdmin,$isStrategy,$isAccountant);

        $client_basicinfo_model = new ClientBasicinfo();
        $client_upload_type = $client_basicinfo_model->client_upload_type;

        $client = array();
        $client_basicinfo  = \DB::table('client_basicinfo')
            ->join('users', 'users.id', '=', 'client_basicinfo.account_manager_id')
            ->leftjoin('industry', 'industry.id', '=', 'client_basicinfo.industry_id')
            ->select('client_basicinfo.*', 'users.name as am_name','users.id as am_id', 'industry.name as ind_name')
            ->where('client_basicinfo.id','=',$id)
            ->get();

        $client['id'] = $id;
   
    foreach ($client_basicinfo as $key=>$value)
    {
        if(in_array($role_id,$access_roles_id) || ($value->am_id==$user_id))
        {
            $client['name'] = $value->name;
            $client['source'] = $value->source;
            $client['fax'] = $value->fax;
            $client['mobile'] = $value->mobile;
            $client['am_name'] = $value->am_name;
            $client['mail'] = $value->mail;
            $client['ind_name'] = $value->ind_name;
            $client['website'] = $value->website;
            $client['description'] = $value->description;
            $client['gst_no'] = $value->gst_no;
            /*$client['tds'] = $value->tds;*/
            $client['coordinator_name'] = $value->coordinator_prefix. " " .$value->coordinator_name;
            $client['tan'] = $value->tan;
            $client['status']=$value->status;
            $client['category']=$value->category;
            $client['display_name'] = $value->display_name;

            if(isset($client['status']))
            {
                if($client['status'] == '1')
                {
                    $client['status']='Active';
                }
                else
                {
                    $client['status']='Passive';
                }
            }

            $client['percentage_charged_below']=$value->percentage_charged_below;
            $client['percentage_charged_above']=$value->percentage_charged_above;

            if($value->am_id==$user->id)
            {
                $client['client_owner'] = true;
            }
            else 
            {
                $client['client_owner'] = false;
            }
        }
        else
        {
            return view('errors.403');
        }
    }

        $client_address = \DB::table('client_address')
                        ->where('client_id','=',$id)
                        ->get();

        foreach ($client_address as $key=>$value)
        {
            $client['billing_country'] = $value->billing_country;
            $client['billing_state'] = $value->billing_state;
            $client['billing_street'] = $value->billing_street1."\n".$value->billing_street2;
            $client['billing_code'] = $value->billing_code;
            $client['billing_city'] = $value->billing_city;
            $client['shipping_country'] = $value->shipping_country;
            $client['shipping_state'] = $value->shipping_state;
            $client['shipping_street'] = $value->shipping_street1."\n".$value->shipping_street2;
            $client['shipping_code'] = $value->shipping_code;
            $client['shipping_city'] = $value->shipping_city;
        }

        $i = 0;
        $client['doc'] = array();
        $client_doc = \DB::table('client_doc')
                    ->join('users', 'users.id', '=', 'client_doc.uploaded_by')
                    ->select('client_doc.*', 'users.name as upload_name')
                    ->where('client_id','=',$id)
                    ->get();

        $utils = new Utils();
        foreach ($client_doc as $key=>$value)
        {
            $client['doc'][$i]['name'] = $value->name ;
            $client['doc'][$i]['id'] = $value->id;
            $client['doc'][$i]['url'] = "../".$value->file ;//$utils->getUrlAttribute($value->file);
            $client['doc'][$i]['category'] = $value->category ;
            $client['doc'][$i]['uploaded_by'] = $value->upload_name ;
            $client['doc'][$i]['size'] = $utils->formatSizeUnits($value->size);
            $i++;
            if(array_search($value->category, $client_upload_type)) 
            {
                unset($client_upload_type[array_search($value->category, $client_upload_type)]);
            }
        }

        $client_upload_type['Others'] = 'Others';
    
        //print_r($client);exit;
        return view('adminlte::client.show',compact('client','client_upload_type','isSuperAdmin','isAdmin','isStrategy'));
    }

        public function attachmentsDestroy($docid){

        $client_attach=\DB::table('client_doc')
        ->select('client_doc.*')
        ->where('id','=',$docid)->first();

        if(isset($client_attach))
        {
            $path="uploads/clients/".$client_attach->client_id . "/" . $client_attach->name;

            unlink($path);

            $clientid=$client_attach->client_id;
    
            $client_doc=ClientDoc::where('id','=',$docid)->delete();
        }

        return redirect()->route('client.show',[$clientid])->with('success','Attachment deleted Successfully');
    }

    public function upload(Request $request){
        $client_upload_type = $request->client_upload_type;
        $file = $request->file('file');
        $client_id = $request->id;
        $user_id = \Auth::user()->id;

        if (isset($file) && $file->isValid()) {
            $doc_name = $file->getClientOriginalName();
            $doc_filesize = filesize($file);

            $dir_name = "uploads/clients/".$client_id."/";
            $others_doc_key = "uploads/clients/".$client_id."/".$doc_name;

            if (!file_exists($dir_name)) {
                mkdir("uploads/clients/$client_id", 0777,true);
            }

            if(!$file->move($dir_name, $doc_name)){
                return false;
            }
            else{
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

        return redirect()->route('client.show',[$client_id])->with('success','Attachment uploaded successfully');
    }

    public function delete($id){

        $lead_res = \DB::table('client_basicinfo')
                    ->select('client_basicinfo.lead_id')
                    ->where('id','=',$id)
                    ->first();

        if (isset($lead_res) && $lead_res !='') {
            $lead_id = $lead_res->lead_id;
        }

        if (isset($lead_id) && $lead_id !='') {
            $lead = Lead::find($lead_id);
            //print_r($lead);exit;
            $lead->convert_client = 0;
            $lead->save();
        }


        $res = ClientBasicinfo::checkAssociation($id);

        if($res){
            // delete address info
            \DB::table('client_address')->where('client_id', '=', $id)->delete();

            // delete attachments
            \DB::table('client_doc')->where('client_id', '=', $id)->delete();

            // delete basic info
            ClientBasicinfo::where('id',$id)->delete();

            // unlink documents
            $dir_name = "uploads/clients/".$id."/";
            $client_doc = new ClientDoc();
            if(is_dir($dir_name)){
                $response = $client_doc->recursiveRemoveDirectory($dir_name);
            }

            return redirect()->route('client.index')->with('success','Client deleted Successfully');
        }else{
            return redirect()->route('client.index')->with('error','Client is associated with job.!!');
        }
        return redirect()->route('client.index'); 
    }

   /* public function deleteAssociatedJob($id){
        
        

    }*/

    public function update(Request $request, $id){
        $user_id = \Auth::user()->id;

        $input = $request->all();
        $input = (object)$input;

        //print_r($input);exit;
        $client_basicinfo = ClientBasicinfo::find($id);

        $client_basicinfo->name = $input->name;
        $client_basicinfo->display_name = $input->display_name;
        $client_basicinfo->mobile = $input->mobile;
        $client_basicinfo->other_number = $input->other_number;
        $client_basicinfo->mail = $input->mail;
        $client_basicinfo->s_email = $input->s_email;
        $client_basicinfo->description = $input->description;

        if(isset($input->percentage_charged_below) && $input->percentage_charged_below!= '' )
        {
            $client_basicinfo->percentage_charged_below=$input->percentage_charged_below;
        }
        else
        {
            $client_basicinfo->percentage_charged_below=NULL;
        }
        
        if(isset($input->percentage_charged_above) && $input->percentage_charged_above!='' )
        {
            $client_basicinfo->percentage_charged_above=$input->percentage_charged_above;
        }
        else
        {
             $client_basicinfo->percentage_charged_above=NULL;
        }
        
        //$client_basicinfo->fax = $input->fax;
        $client_basicinfo->industry_id = $input->industry_id;

        $status=$input->status;

        if($status=='1')
        {
            $client_basicinfo->status='1';
        }
        if($status=='0')
        {
            $client_basicinfo->status='0';
        }
        $client_basicinfo->website = $input->website;
        if(isset($input->source) && $input->source!=''){
            $client_basicinfo->source = $input->source;
        }
        else{
            $client_basicinfo->source = '';
        }

        //$client_basicinfo->gst_no = $input->gst_no;
        //$client_basicinfo->tds = $input->tds;
        $client_basicinfo->coordinator_name = $input->coordinator_name;

        $client_basicinfo->coordinator_prefix=$input->co_category;
        $client_basicinfo->account_manager_id = $input->account_manager;

        if(isset($input->gst_no) && $input->gst_no!='')
            $client_basicinfo->gst_no = $input->gst_no;
        else
            $client_basicinfo->gst_no = '';

       /* if(isset($input->tds) && $input->tds!='')
            $client_basicinfo->tds = $input->tds;
        else
            $client_basicinfo->tds = '';*/
        
        if(isset($input->tan) && $input->tan!='')
            $client_basicinfo->tan = $input->tan;
        else
            $client_basicinfo->tan = '';

        if(isset($input->client_category))
        {
            $client_basicinfo->category=$input->client_category;
        }
        else
        {
            $client_basicinfo->category='';
        }
        
        if($client_basicinfo->save()){

            // update client address
            $client_address = ClientAddress::find($input->client_address_id);
               if(!isset($client_address) && empty($client_address)){
                   $client_address = new ClientAddress();
                   $client_address->client_id = $id;
               }
            if(isset($input->billing_country) && $input->billing_country!=''){
                $client_address->billing_country = $input->billing_country;
            }
            if(isset($input->billing_state) && $input->billing_state!=''){
                $client_address->billing_state = $input->billing_state;
            }
            if(isset($input->billing_street1) && $input->billing_street1!=''){
                $client_address->billing_street1 = $input->billing_street1;
            }
            if(isset($input->billing_street2) && $input->billing_street2!=''){
                $client_address->billing_street2 = $input->billing_street2;
            }
            if(isset($input->billing_code) && $input->billing_code!=''){
                $client_address->billing_code = $input->billing_code;
            }
            if(isset($input->billing_city) && $input->billing_city!=''){
                $client_address->billing_city = $input->billing_city;
            }

            if(isset($input->shipping_country) && $input->shipping_country!=''){
                $client_address->shipping_country = $input->shipping_country;
            }
            if(isset($input->shipping_state) && $input->shipping_state!=''){
                $client_address->shipping_state = $input->shipping_state;
            }
            if(isset($input->shipping_street1) && $input->shipping_street1!=''){
                $client_address->shipping_street1 = $input->shipping_street1;
            }
            if(isset($input->shipping_street2) && $input->shipping_street2!=''){
                $client_address->shipping_street2 = $input->shipping_street2;
            }
            if(isset($input->shipping_code) && $input->shipping_code!=''){
                $client_address->shipping_code = $input->shipping_code;
            }
            if(isset($input->shipping_city) && $input->shipping_city!=''){
                $client_address->shipping_city = $input->shipping_city;
            }
            $client_address->updated_at = date("Y-m-d H:i:s");
            $client_address->save();
            return redirect()->route('client.index')->with('success','Client updated successfully');
        }else{
            return redirect('client/'.$client_basicinfo->id.'/edit')->withInput(Input::all())->withErrors ( $client_basicinfo->errors() );
        }

    }

   
    public function postClientEmails()
    {
        $user =  \Auth::user();

        $user_id=$user->id;

        $client_ids = $_POST['client_ids'];

        $client_ids_array=explode(",",$client_ids);



      /*  $client_info=\DB::table('client_basicinfo')
        ->select('client_basicinfo.*')
        ->where()
        ->get();*/

        foreach($client_ids_array as $key => $value)
        {
            $client_email=ClientBasicinfo::getClientEmailByID($value);

           // echo $client_email;

            $client_name=ClientBasicinfo::getClientNameByID($value);

          /// echo $client_name;

            $client_company=ClientBasicinfo::getCompanyOfClientByID($value);

           // echo $client_company;
            

            $message="Open Position List - $client_company";
            
        
            /*$email_notification= new EmailsNotifications();
            $email_notification->module='Client';
            $email_notification->sender_name=$user_id;
            $email_notification->to=$client_email;
            $email_notification->cc='rajlalwani@adlertalent.com';
            $email_notification->bcc="";
            $email_notification->subject=$message;
            $email_notification->message="";
            $email_notification->sent_date=date("Y-m-d H:i:s");
            $email_notification->status='0';
            $email_notification->module_id=$value;*/

            $module='Client';
            $sender_name=$user_id;
            $to=$client_email;
           // $cc=$client_email;
            $cc='rajlalwani@adlertalent.com';
            $subject=$message;
            $body_message="Dear $client_name, <br><br>Greetings From $client_company! <br><br> Request You to Kindly Advise on the list of Priority Positions for this Week to Focus Upon Accordingly.<br><br>Awaiting your revert to expedite accordingly.<br><br>Thanks.";
           
            $module_id=$value;
            
            event(new NotificationMail($module,$sender_name,$to,$subject,$body_message,$module_id,$cc));
            
        }

        return redirect()->route('client.index')->with('success','Successfully');
    }

    public function getMonthWiseClient()
    {

        $user =  \Auth::user();

        // get role of logged in user
        $userRole = $user->roles->pluck('id','id')->toArray();

        $role_id = key($userRole);

        $user_obj = new User();

        $isSuperAdmin = $user_obj::isSuperAdmin($role_id);
        $isStrategy = $user_obj::isStrategyCoordination($role_id);

        if($isSuperAdmin){
            $response = ClientBasicinfo::getMonthWiseClientByUserId($user->id,1);
            $count = sizeof($response);
        }
        else{
            $response = ClientBasicinfo::getMonthWiseClientByUserId($user->id,0);
            $count = sizeof($response);
        }

        return view('adminlte::client.monthwiseclient', array('clients' => $response,'count' => $count),compact('isSuperAdmin','isStrategy'));
    }

    public function getAccountManager(Request $request)
    {
        $account_manager=$request->get('account_manager');
        $id = $request->get('id');

        $act_man=ClientBasicinfo::find($id);

        $a_m='';

        if(isset($act_man)){
            $a_m=$account_manager;
        }

        $act_man->account_manager_id=$a_m;
        $act_man->save();

        if ($act_man) {
            $job_ids = JobOpen::getJobIdByClientId($id);
            foreach ($job_ids as $key => $value) {

                \DB::statement("UPDATE job_openings SET hiring_manager_id = '$a_m' where id=$value");

                $check_job_user_id = JobVisibleUsers::getCheckJobUserIdAdded($value,$a_m);

                if ($check_job_user_id == false) {
                    $job_visible_users = new JobVisibleUsers();
                    $job_visible_users->job_id = $value;
                    $job_visible_users->user_id = $a_m;
                    $job_visible_users->save();
                }
            }
        }

       return redirect()->route('client.index')->with('success', 'Client Account Manager Updated successfully');
    }
    public function importExport(){

        return view('adminlte::client.import');
    }

    public function importExcel(Request $request){
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

                            if($client_cnt>0){
                                $messages[] = "Record $sr_no already present ";
                            }
                            else{
                                // get user id from managed_by (i.e. username)
                                $acc_mngr_id = User::getUserIdByName($managed_by);

                                if($acc_mngr_id>0){
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
                                    else{
                                        $messages[] = "Error while inserting record $sr_no ";
                                    }
                                }

                                else{
                                    $messages[] = "Error while inserting record $sr_no ";
                                }


                            }


                        }
                    }
                    else{
                        $messages[] = "No Data in file";
                    }

                }
            }

            return view('adminlte::client.import',compact('messages'));
            //return redirect()->route('client.index')->with('success','Client Created Successfully');
        }
    }

}
