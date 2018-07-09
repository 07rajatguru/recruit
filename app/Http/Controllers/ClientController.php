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

        // if Super Admin get clients of all companies
        if($isSuperAdmin || $isAdmin){
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

        $client_array = array();
        $i = 0;
        foreach($clients as $client){
            $client_array[$i]['id'] = $client->id;
            $client_array[$i]['name'] = $client->name;
            $client_array[$i]['am_name'] = $client->am_name;
            $client_array[$i]['mobile']= $client->mobile;
            $client_array[$i]['hr_name'] = $client->coordinator_name;

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
            $i++;
        }
         
       
        /*$client_doc = \DB::table('client_doc')
                    ->leftjoin('client_basicinfo','client_basicinfo.id','=','client_doc.client_id')       
                    ->select('client_doc.id','client_basicinfo.id','client_doc.file','client_doc.category')
                   // ->where('client_doc.client_id',$client->id)
                    ->get();
                    //print_r($client_doc);exit;

        $i= 1;
        $clientdoc = array();
        foreach ($client_doc as $key=>$value){
            $clientdoc[$i]['id'] = $value->id;
            $clientdoc[$i]['url'] = "../".$value->file ;
            //print_r($clientdoc);exit;
            $i++;
            
        }*/
        

        return view('adminlte::client.index',compact('client_array','isAdmin','isSuperAdmin','count'));
    }

    public function create()
    {
        $generate_lead = '1';
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

        $industry_id = '';

        $action = "add" ;
        return view('adminlte::client.create',compact('action','industry','users','isSuperAdmin','user_id','isAdmin','generate_lead','industry_id'));
    }

    public function edit($id)
    {

        $generate_lead = '1';

        $user = \Auth::user();
        $userRole = $user->roles->pluck('id','id')->toArray();
        $role_id = key($userRole);

        $user_obj = new User();
        $isAdmin = $user_obj::isAdmin($role_id);
        $isSuperAdmin = $user_obj::isSuperAdmin($role_id);

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
            ->select('client_basicinfo.*', 'users.name as am_name', 'industry.name as ind_name')
            ->where('client_basicinfo.id','=',$id)
            ->get();

        foreach ($client_basicinfo as $key=>$value){
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
            $client['tds'] = $value->tds;
            $client['coordinator_name'] = $value->coordinator_name;
            $client['tan'] = $value->tan;

            $user_id = $value->account_manager_id;
            $industry_id = $value->industry_id;
        }
        $client['id'] = $id;

        $client_address = \DB::table('client_address')
            ->where('client_id','=',$id)
            ->get();

        foreach ($client_address as $key=>$value){
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
         $users = User::getAllUsers();

        $action = "edit" ;
        return view('adminlte::client.edit',compact('action','industry','client','users','user_id','isSuperAdmin','isAdmin','generate_lead','industry_id'));
    }

    public function store(Request $request){

        $user_id = \Auth::user()->id;
        $user_name = \Auth::user()->name;
        $user_email = \Auth::user()->email;
        $input = $request->all();

        $client_basic_info = new ClientBasicinfo();
        $client_basic_info->name = $input['name'];
        $client_basic_info->display_name = $input['display_name'];
        $client_basic_info->mail = $input['mail'];
        $client_basic_info->s_email = $input['s_email'];
        $client_basic_info->description = $input['description'];
        $client_basic_info->mobile = $input['mobile'];
        $client_basic_info->other_number = $input['other_number'];
        //$client_basic_info->fax = $input['fax'];
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

            /*// Email Notification : data store in datebase
            $module = "Client";
            $sender_name = $user_id;
            $to = $user_email;
            $subject = "Client - ".$client_name;
            $message = "<tr><td>" . $user_name . " added new Client </td></tr>";
            $module_id = $client_id;

            event(new NotificationMail($module,$sender_name,$to,$subject,$message,'$module_id'));*/

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
        $user_id = $user->id;

        $client_basicinfo_model = new ClientBasicinfo();
        $client_upload_type = $client_basicinfo_model->client_upload_type;

        $client = array();
        $client_basicinfo  = \DB::table('client_basicinfo')
            ->join('users', 'users.id', '=', 'client_basicinfo.account_manager_id')
            ->leftjoin('industry', 'industry.id', '=', 'client_basicinfo.industry_id')
            ->select('client_basicinfo.*', 'users.name as am_name', 'industry.name as ind_name')
            ->where('client_basicinfo.id','=',$id)
            ->get();

        $client['id'] = $id;
        foreach ($client_basicinfo as $key=>$value){
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
            $client['tds'] = $value->tds;
            $client['coordinator_name'] = $value->coordinator_name;
            $client['tan'] = $value->tan;
        }

        $client_address = \DB::table('client_address')
                        ->where('client_id','=',$id)
                        ->get();

        foreach ($client_address as $key=>$value){
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
        foreach ($client_doc as $key=>$value){
            $client['doc'][$i]['name'] = $value->name ;
            $client['doc'][$i]['id'] = $value->id;
            $client['doc'][$i]['url'] = "../".$value->file ;//$utils->getUrlAttribute($value->file);
            $client['doc'][$i]['category'] = $value->category ;
            $client['doc'][$i]['uploaded_by'] = $value->upload_name ;
            $client['doc'][$i]['size'] = $utils->formatSizeUnits($value->size);
            $i++;
            if (array_search($value->category, $client_upload_type)) {
                unset($client_upload_type[array_search($value->category, $client_upload_type)]);
            }
        }
        $client_upload_type['Others'] = 'Others';

        //print_r($client);exit;
        return view('adminlte::client.show',compact('client','client_upload_type','isSuperAdmin','isAdmin'));
    }

    public function attachmentsDestroy($docid){

        $clientDocDelete = ClientDoc::where('id',$docid)->delete();

        $clientid = $_POST['clientid'];

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
        //$client_basicinfo->fax = $input->fax;
        $client_basicinfo->industry_id = $input->industry_id;
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
        $client_basicinfo->account_manager_id = $input->account_manager;

        if(isset($input->gst_no) && $input->gst_no!='')
            $client_basicinfo->gst_no = $input->gst_no;
        else
            $client_basicinfo->gst_no = '';
        if(isset($input->tds) && $input->tds!='')
            $client_basicinfo->tds = $input->tds;
        else
            $client_basicinfo->tds = '';
        if(isset($input->tan) && $input->tan!='')
            $client_basicinfo->tan = $input->tan;
        else
            $client_basicinfo->tan = '';

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
