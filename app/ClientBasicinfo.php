<?php

namespace App;
use App\User;

use LaravelArdent\Ardent\Ardent;

class ClientBasicinfo extends Ardent
{
    public $table = "client_basicinfo";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'mobile', 'account_manager_id'
    ];

    public $client_upload_type = array('Client Contract'=>'Client Contract','Client Logo'=>'Client Logo');

    // create the validation rules ------------------------
    public static $rules = array(

        'name' => 'required',
        //'mail' => 'unique:client_basicinfo,mail,{id}',
        'mobile'  => 'required',
        'display_name' => 'required'
    );

    public function messages() {

        return [
            'name.required' => 'Company Name is Required Field.',
            'mail.required' => 'Eail is Required Field.',
            'mail.unique' => 'Mail is unique Field.',
            'mobile.required'  => 'Mobile is Required Field.',
            'display_name.required' => 'Display Name is Required Field.',
        ];
    }

    public function post() {
        return $this->hasMany('App\Post','client_id');
    }

    public static function getFieldsList() {

        $field_list = array();
        
        $field_list[''] = 'Select Field';
        $field_list['Client Owner'] = 'Client Owner';
        $field_list['Company Name'] = 'Company Name';
        $field_list['Contact Point'] = 'Contact Point';
        $field_list['Client Category'] = 'Client Category';
        $field_list['Client Status'] = 'Client Status';
        $field_list['Client City'] = 'Client City';

        return $field_list;
    }

    public static function getAllClients($all=0,$user_id,$limit=0,$offset=0,$search=0,$order=0,$type='asc',$client_owner='',$client_company='',$client_contact_point='',$client_cat='',$client_status='',$client_city='') {

        $status_id = '3';
        $status_id_array = array($status_id);

        $query = ClientBasicinfo::query();
        $query = $query->leftjoin('client_address','client_address.client_id','=','client_basicinfo.id');
        $query = $query->leftjoin('users', 'users.id', '=', 'client_basicinfo.account_manager_id');
        
        if ($all == 1) {

            $query = $query->leftJoin('client_doc',function($join) {

                $join->on('client_doc.client_id', '=', 'client_basicinfo.id');
                $join->where('client_doc.category','=','Client Contract');
            });

            $query = $query->select('client_basicinfo.*', 'users.name as am_name','users.id as am_id','client_doc.file','client_address.billing_street2 as area','client_address.billing_city as city');

            if (isset($search) && $search != '') {

                $query = $query->where(function($query) use ($search) {

                    $query = $query->where('users.name','=',"$search");
                    $query = $query->orwhere('users.first_name','=',"$search");
                    $query = $query->orwhere('client_basicinfo.name','like',"%$search%");
                    $query = $query->orwhere('client_basicinfo.coordinator_name','like',"%$search%");
                    $query = $query->orwhere('client_basicinfo.category','like',"%$search%");
                    //$query = $query->orwhere('client_basicinfo.latest_remarks','like',"%$search%");

                    if ($search == 'Active' || $search == 'active') {
                        $search = 1;
                        $query = $query->orwhere('client_basicinfo.status','like',"%$search%");
                    }
                    if ($search == 'Passive' || $search == 'passive') {
                        $search = 0;
                        $query = $query->orwhere('client_basicinfo.status','like',"%$search%");
                    }
                    if ($search == 'Forbid' || $search == 'forbid') {
                        $search = 3;
                        $query = $query->orwhere('client_basicinfo.status','like',"%$search%");
                    }
                    if ($search == 'Leaders' || $search == 'leaders') {
                        $search = 2;
                        $query = $query->orwhere('client_basicinfo.status','like',"%$search%");
                    }
                    if ($search == 'Left' || $search == 'left') {
                        $search = 4;
                        $query = $query->orwhere('client_basicinfo.status','like',"%$search%");
                    }
                    $query = $query->orwhere('client_address.billing_street2','like',"%$search%");
                    $query = $query->orwhere('client_address.billing_city','like',"%$search%");

                    if(($search == 'Yet') || ($search == 'Yet ') || ($search == 'yet') || ($search == 'yet ') || ($search == 'Yet to') || ($search == 'Yet to ' ) || ($search == 'Yet To') || ($search == 'Yet To ') || ($search == 'yet to') || ($search == 'yet to ') || ($search == 'yet To') || ($search == 'yet To ') || ($search == 'Yet to assign') || ($search == 'Yet To assign') || ($search == 'Yet To Assign') || ($search == 'Yet To assign') || ($search == 'Yet to Assign') || ($search == 'yet to Assign') || ($search == 'Yet to assign') || ($search == 'yet To Assign') || ($search == 'yet to assign')) {
                        $search = 0;
                        $query = $query->orwhere('client_basicinfo.account_manager_id','=',"%$search%");
                    }
                });
            }
        }

        else if ($all == 0) {

            $query = $query->select('client_basicinfo.*', 'users.name as am_name','users.id as am_id','client_address.billing_street2 as area','client_address.billing_city as city');

            /*$manager_user_id = env('MANAGERUSERID');
            $marketing_intern_user_id = env('MARKETINGINTERNUSERID');

            // visible standard and moderate clients to manager
            if($manager_user_id == $user_id || $marketing_intern_user_id == $user_id) {

                $query = $query->where(function($query) use ($user_id) {

                    $query = $query->where('account_manager_id',$user_id);
                    $query = $query->orwhere('client_basicinfo.category','like',"Moderate");
                    $query = $query->orwhere('client_basicinfo.category','like',"Standard");
                });
            }
            else {
                $query = $query->where('account_manager_id',$user_id);
            }*/

            $query = $query->where('account_manager_id',$user_id);
            
            if (isset($search) && $search != '') {

                $query = $query->where(function($query) use ($search) {

                    $query = $query->where('users.name','=',"$search");
                    $query = $query->orwhere('users.first_name','=',"$search");
                    $query = $query->orwhere('client_basicinfo.name','like',"%$search%");
                    $query = $query->orwhere('client_basicinfo.coordinator_name','like',"%$search%");
                    $query = $query->orwhere('client_basicinfo.category','like',"%$search%");
                    //$query = $query->orwhere('client_basicinfo.latest_remarks','like',"%$search%");

                    if ($search == 'Active' || $search == 'active') {
                        $search = 1;
                        $query = $query->orwhere('client_basicinfo.status','like',"%$search%");
                    }
                    if ($search == 'Passive' || $search == 'passive') {
                        $search = 0;
                        $query = $query->orwhere('client_basicinfo.status','like',"%$search%");
                    }
                    if ($search == 'Forbid' || $search == 'forbid') {
                        $search = 3;
                        $query = $query->orwhere('client_basicinfo.status','like',"%$search%");
                    }
                    if ($search == 'Leaders' || $search == 'leaders') {
                        $search = 2;
                        $query = $query->orwhere('client_basicinfo.status','like',"%$search%");
                    }
                    if ($search == 'Left' || $search == 'left') {
                        $search = 4;
                        $query = $query->orwhere('client_basicinfo.status','like',"%$search%");
                    }

                    $query = $query->orwhere('client_address.billing_street2','like',"%$search%");
                    $query = $query->orwhere('client_address.billing_city','like',"%$search%");

                    if(($search == 'Yet') || ($search == 'Yet ') || ($search == 'yet') || ($search == 'yet ') || ($search == 'Yet to') || ($search == 'Yet to ' ) || ($search == 'Yet To') || ($search == 'Yet To ') || ($search == 'yet to') || ($search == 'yet to ') || ($search == 'yet To') || ($search == 'yet To ') || ($search == 'Yet to assign') || ($search == 'Yet To assign') || ($search == 'Yet To Assign') || ($search == 'Yet To assign') || ($search == 'Yet to Assign') || ($search == 'yet to Assign') || ($search == 'Yet to assign') || ($search == 'yet To Assign') || ($search == 'yet to assign')) {
                        $search = 0;
                        $query = $query->orwhere('client_basicinfo.account_manager_id','=',"%$search%");
                    }
                });
            }
        }

        // Master Search Condidtions
        if(isset($client_owner) && $client_owner != '') {

            $query = $query->where('users.name','like',"%$client_owner%");
            $query = $query->orwhere('users.first_name','like',"%$client_owner%");
        }
        else if(isset($client_company) && $client_company != '') {

            $query = $query->where('client_basicinfo.name','like',"%$client_company%");
        }
        else if(isset($client_contact_point) && $client_contact_point != '') {

            $query = $query->where('client_basicinfo.coordinator_name','like',"%$client_contact_point%");
        }
        else if(isset($client_cat) && $client_cat != '') {

            $query = $query->where('client_basicinfo.category','like',"%$client_cat%");
        }
        else if(isset($client_status) && $client_status != '') {

            if ($client_status == 'Active' || $client_status == 'active') {

                $client_status = 1;
                $query = $query->where('client_basicinfo.status','=',"$client_status");
            }
            if ($client_status == 'Passive' || $client_status == 'passive') {

                $client_status = 0;
                $query = $query->where('client_basicinfo.status','=',"$client_status");
            }
            if ($client_status == 'Leaders' || $client_status == 'leaders') {

                $client_status = 2;
                $query = $query->where('client_basicinfo.status','=',"$client_status");
            }
            if ($client_status == 'Left' || $client_status == 'left') {

                $client_status = 4;
                $query = $query->where('client_basicinfo.status','=',"$client_status");
            }
            if ($client_status == 'Forbid' || $client_status == 'forbid') {

                $client_status = 3;
                $query = $query->where('client_basicinfo.status','=',"$client_status");
            }
        }
        else if(isset($client_city) && $client_city != '') {

            $query = $query->where('client_address.billing_city','like',"%$client_city%");
        }

        //$query = $query->toSql();

        //print_r($query);exit;

        // Not display Forbid clients
        $query = $query->whereNotIn('client_basicinfo.status',$status_id_array);

        if (isset($limit) && $limit > 0) {
            $query = $query->limit($limit);
        }
        if (isset($offset) && $offset > 0) {
            $query = $query->offset($offset);
        }
        if (isset($order) && $order !='') {
            $query = $query->orderBy($order,$type);
        }

        // Not Display Delete Client Status '1' Entry
        $query = $query->where('client_basicinfo.delete_client','=','0');

        $query = $query->groupBy('client_basicinfo.id');
        $res = $query->get();

        $client_array = array();
        $i = 0;

        foreach ($res as $key => $value) {

            $client_array[$i]['id'] = $value->id;
            $client_array[$i]['latest_remarks'] = $value->latest_remarks;

            $client_array[$i]['name'] = $value->name;

            if ($value->account_manager_id == 0) {
                $client_array[$i]['am_name'] = 'Yet to Assign';
            }
            else {
                $client_array[$i]['am_name'] = $value->am_name;
            }

            $client_array[$i]['category'] = $value->category;
            $client_array[$i]['status'] = $value->status;
            $client_array[$i]['account_mangr_id'] = $value->account_manager_id;
            $client_array[$i]['mobile'] = $value->mobile;
            $client_array[$i]['hr_name'] = $value->coordinator_prefix . " " . $value->coordinator_name;
            $client_array[$i]['full_name'] = $value->name." - ".$value->coordinator_name." - ".$value->city;

            if(isset($client_array[$i]['status'])) {

                if($client_array[$i]['status'] == '1') {
                  $client_array[$i]['status']='Active';
                }
                else if($client_array[$i]['status'] == '0') {
                  $client_array[$i]['status']='Passive';
                }
                else if($client_array[$i]['status'] == '2') {
                    $client_array[$i]['status']='Leaders';
                }
                else if($client_array[$i]['status'] == '3') {
                    $client_array[$i]['status']='Forbid';
                }
                else if($client_array[$i]['status'] == '4') {
                    $client_array[$i]['status']='Left';
                }
            }
            
            $address ='';
            if($value->city!='') {

                if($address=='')
                    $address .= $value->city;
                else
                    $address .= ", ".$value->city;
            }
            $client_array[$i]['address'] = $address;

            if($value->am_id == $user_id) {
                $client_visibility_val = true;
                $client_array[$i]['client_owner'] = true;
            }
            else {
                $client_visibility_val = false;
                $client_array[$i]['client_owner'] = false;
            }

            if($client_visibility_val)
                $client_array[$i]['mail'] = $value->mail;
            else
                $client_array[$i]['mail'] = '';//$utils->mask_email($value->mail,'X',80);

            $client_array[$i]['client_visibility'] = $client_visibility_val;

            if($all == 1) {
                $client_array[$i]['url'] = $value->file;
            }
            else {
                $client_array[$i]['url'] = '';
            }

            $client_array[$i]['second_line_am'] = $value->second_line_am;

            if(isset($value->second_line_am) && $value->second_line_am > 0) {

                $user_details = User::getAllDetailsByUserID($value->second_line_am);

                if(isset($user_details) && $user_details != '') {
                    $client_array[$i]['second_line_am_name'] = $user_details->name;
                }
                else {
                    $client_array[$i]['second_line_am_name'] = '';
                }
            }
            else {
                $client_array[$i]['second_line_am_name'] = '';
            }

            $i++;
        }
        return $client_array;
    }

    public static function getAllClientsCount($all=0,$user_id,$search=0,$client_owner='',$client_company='',$client_contact_point='',$client_cat='',$client_status='',$client_city='') {

        $query = ClientBasicinfo::query();
        $query = $query->leftjoin('client_address','client_address.client_id','=','client_basicinfo.id');
        $query = $query->leftjoin('users', 'users.id', '=', 'client_basicinfo.account_manager_id');

        if ($all == 1) {

            $query = $query->leftJoin('client_doc',function($join) {

                $join->on('client_doc.client_id', '=', 'client_basicinfo.id');
                $join->where('client_doc.category','=','Client Contract');
            });

            $query = $query->select('client_basicinfo.*', 'users.name as am_name','users.id as am_id','client_doc.file','client_address.billing_street2 as area','client_address.billing_city as city');
        }
        else if ($all == 0) {

            $query = $query->select('client_basicinfo.*', 'users.name as am_name','users.id as am_id','client_address.billing_street2 as area','client_address.billing_city as city');

            /*$manager_user_id = env('MANAGERUSERID');
            $marketing_intern_user_id = env('MARKETINGINTERNUSERID');

            // visible standard and moderate clients to manager
            if($manager_user_id == $user_id || $marketing_intern_user_id == $user_id) {

                $query = $query->where(function($query) use ($user_id) {

                    $query = $query->where('account_manager_id',$user_id);
                    $query = $query->orwhere('client_basicinfo.category','like',"Moderate");
                    $query = $query->orwhere('client_basicinfo.category','like',"Standard");
                });
            }
            else {
                $query = $query->where('account_manager_id',$user_id);
            }*/

            $query = $query->where('account_manager_id',$user_id);
        }

        if (isset($search) && $search != '') {

            $query = $query->where(function($query) use ($search) {

                $query = $query->where('users.name','=',"$search");
                $query = $query->orwhere('users.first_name','=',"$search");
                $query = $query->orwhere('client_basicinfo.name','like',"%$search%");
                $query = $query->orwhere('client_basicinfo.coordinator_name','like',"%$search%");
                $query = $query->orwhere('client_basicinfo.category','like',"%$search%");
                //$query = $query->orwhere('client_basicinfo.latest_remarks','like',"%$search%");

                if ($search == 'Active' || $search == 'active') {
                    $search = 1;
                    $query = $query->orwhere('client_basicinfo.status','like',"%$search%");
                }
                if ($search == 'Passive' || $search == 'passive') {
                    $search = 0;
                    $query = $query->orwhere('client_basicinfo.status','like',"%$search%");
                }
                if ($search == 'Forbid' || $search == 'forbid') {
                    $search = 3;
                    $query = $query->orwhere('client_basicinfo.status','like',"%$search%");
                }
                if ($search == 'Leaders' || $search == 'leaders') {
                    $search = 2;
                    $query = $query->orwhere('client_basicinfo.status','like',"%$search%");
                }
                if ($search == 'Left' || $search == 'left') {
                    $search = 4;
                    $query = $query->orwhere('client_basicinfo.status','like',"%$search%");
                }
                
                $query = $query->orwhere('client_address.billing_street2','like',"%$search%");
                $query = $query->orwhere('client_address.billing_city','like',"%$search%");

                if(($search == 'Yet') || ($search == 'Yet ') || ($search == 'yet') || ($search == 'yet ') || ($search == 'Yet to') || ($search == 'Yet to ' ) || ($search == 'Yet To') || ($search == 'Yet To ') || ($search == 'yet to') || ($search == 'yet to ') || ($search == 'yet To') || ($search == 'yet To ') || ($search == 'Yet to assign') || ($search == 'Yet To assign') || ($search == 'Yet To Assign') || ($search == 'Yet To assign') || ($search == 'Yet to Assign') || ($search == 'yet to Assign') || ($search == 'Yet to assign') || ($search == 'yet To Assign') || ($search == 'yet to assign')) {
                    
                    $search = 0;
                    $query = $query->orwhere('client_basicinfo.account_manager_id','=',"%$search%");
                }
            });
        }

        // Not Display Delete Client Status '1' Entry
        $query = $query->where('client_basicinfo.delete_client','=','0');

        // Master Search Condidtions
        if(isset($client_owner) && $client_owner != '') {

            $query = $query->where('users.name','like',"%$client_owner%");
            $query = $query->orwhere('users.first_name','like',"%$client_owner%");
        }
        else if(isset($client_company) && $client_company != '') {

            $query = $query->where('client_basicinfo.name','like',"%$client_company%");
        }
        else if(isset($client_contact_point) && $client_contact_point != '') {

            $query = $query->where('client_basicinfo.coordinator_name','like',"%$client_contact_point%");
        }
        else if(isset($client_cat) && $client_cat != '') {

            $query = $query->where('client_basicinfo.category','like',"%$client_cat%");
        }
        else if(isset($client_status) && $client_status != '') {

            if ($client_status == 'Active' || $client_status == 'active') {

                $client_status = 1;
                $query = $query->where('client_basicinfo.status','=',"$client_status");
            }
            if ($client_status == 'Passive' || $client_status == 'passive') {

                $client_status = 0;
                $query = $query->where('client_basicinfo.status','=',"$client_status");
            }
            if ($client_status == 'Leaders' || $client_status == 'leaders') {

                $client_status = 2;
                $query = $query->where('client_basicinfo.status','=',"$client_status");
            }
            if ($client_status == 'Left' || $client_status == 'left') {

                $client_status = 4;
                $query = $query->where('client_basicinfo.status','=',"$client_status");
            }
            if ($client_status == 'Forbid' || $client_status == 'forbid') {

                $client_status = 3;
                $query = $query->where('client_basicinfo.status','=',"$client_status");
            }
        }
        else if(isset($client_city) && $client_city != '') {

            $query = $query->where('client_address.billing_city','like',"%$client_city%");
        }

        // Not display Forbid clients
        $status_id = '3';
        $status_id_array = array($status_id);
        $query = $query->whereNotIn('client_basicinfo.status',$status_id_array);

        $query = $query->orderBy('client_basicinfo.id','desc');
        $query = $query->groupBy('client_basicinfo.id');
        $query = $query->get();
        $count = $query->count();

        return $count;
    }

    public static function getClientArray() {

        $clientArray = array('0' => 'Select');

        $clientDetails = ClientBasicinfo::all();
        if(isset($clientDetails) && sizeof($clientDetails) > 0){
            foreach ($clientDetails as $clientDetail) {
                $clientArray[$clientDetail->id] = $clientDetail->name;
            }
        }
        return $clientArray;
    }

    public static function getLoggedInUserClients($user_id,$next_year=NULL) {

        $client_query = ClientBasicinfo::query();
        $client_query = $client_query->join('client_address','client_address.client_id','=','client_basicinfo.id');

        // Not Display Delete Client Status '1' Entry
        $client_query = $client_query->where('client_basicinfo.delete_client','=','0');

        if($user_id > 0) {
            $client_query = $client_query->where('client_basicinfo.account_manager_id','=',$user_id);
            $client_query = $client_query->orwhere('client_basicinfo.second_line_am','=',$user_id);
        }        

        if(isset($next_year) && $next_year != NULL) {
            $client_query = $client_query->where('client_basicinfo.created_at','<=',$next_year);
        }

        $client_query = $client_query->select('client_basicinfo.*','client_address.client_id','client_address.billing_city');

        $client_response = $client_query->get();
        return $client_response;
    }

    public static function getClientsByIds($user_id,$ids) {

        $client_query = ClientBasicinfo::query();
        $client_query = $client_query->join('client_address','client_address.client_id','=','client_basicinfo.id');

        if($user_id > 0) {
            $client_query = $client_query->where('client_basicinfo.account_manager_id','=',$user_id);
        }

        // Not Display Delete Client Status '1' Entry
        $client_query = $client_query->where('client_basicinfo.delete_client','=','0');

        $client_query = $client_query->select('client_basicinfo.*','client_address.client_id','client_address.billing_city');

        $client_query = $client_query->whereIn('client_basicinfo.id',$ids);
        $client_response = $client_query->get();

        return $client_response;
    }

    public static function checkAssociation($id) {

        $job_query = JobOpen::query();
        $job_query = $job_query->where('client_id','=',$id);
        $job_res = $job_query->first();
        
        if(isset($job_res->client_id) && $job_res->client_id == $id)
            return false;
        else
            return true;
    }

    public static function checkClientByEmail($email) {

        $client_query = ClientBasicinfo::query();
        $client_query = $client_query->where('mail','like',$email);
        $client_cnt = $client_query->count();

        return $client_cnt;
    }

    public static function getClientEmailByID($id) {

        $client_email = '';
        $client_query = ClientBasicinfo::query();
        $client_query = $client_query->where('id','=',$id);
        $client_query = $client_query->first();

        if(isset($client_query)) {
            $client_email = $client_query->mail;
        }
        return $client_email;
     }

    public static function getClientNameByID($id) {

        $client_name = '';
        $client_query = ClientBasicinfo::query();
        $client_query = $client_query->where('id','=',$id);
        $client_query = $client_query->first();

        if(isset($client_query)) {
            $client_name = $client_query->coordinator_prefix." " .$client_query->coordinator_name;
        }
        return $client_name;
    }

    public static function getCompanyOfClientByID($id) {

        $client_company = '';
        $client_query = ClientBasicinfo::query();
        $client_query = $client_query->where('id','=',$id);
        $client_query = $client_query->first();

        if(isset($client_query)) {
            $client_company = $client_query->name;
        }
        return $client_company;
     }

    public static function getBillingCityOfClientByID($id) {

        $client_city = '';
        $client_query = ClientBasicinfo::query();
        $client_query = $client_query->join('client_address','client_address.client_id','=','client_basicinfo.id');
        $client_query = $client_query->select('client_address.billing_city as city');
        $client_query = $client_query->where('client_basicinfo.id','=',$id);
        $client_query = $client_query->first();

        if(isset($client_query)) {
            $client_city = $client_query->city;
        }
        return $client_city;
     }

    public static function getMonthWiseClientByUserId($user_id,$all=0,$month=NULL,$year=NULL,$department_id=0) {

        $query = ClientBasicinfo::query();
        $query = $query->leftjoin('client_address','client_address.client_id','=','client_basicinfo.id');
        $query = $query->leftJoin('users','users.id','=','client_basicinfo.account_manager_id');
        $query = $query->select('client_basicinfo.*','users.name as am_name','users.id as am_id','client_address.billing_city as city');
        $query = $query->whereRaw('MONTH(client_basicinfo.created_at) = ?',[$month]);
        $query = $query->whereRaw('YEAR(client_basicinfo.created_at) = ?',[$year]);

        if($all==0) {
            $query = $query->where(function($query) use ($user_id) {
                $query = $query->where('client_basicinfo.account_manager_id',$user_id);
            });
        }

        // For Department
        if (isset($department_id) && $department_id > 0) {
            $query = $query->where('client_basicinfo.department_id','=',$department_id);
        }
        
        // Not Display Delete Client Status '1' Entry
        $query = $query->where('client_basicinfo.delete_client','=','0');

        $query = $query->orderBy('client_basicinfo.id','desc');
        $query = $query->groupBy('client_basicinfo.id');
        $response = $query->get();

        $client = array();
        $i = 0;

        foreach ($response as $key => $value) {

            $client[$i]['id'] = $value->id;
            //$client[$i]['client_owner'] = $value->am_name;
            $client[$i]['company_name'] = $value->name;
            $client[$i]['coordinator_name'] = $value->coordinator_prefix . " " . $value->coordinator_name;
            $client[$i]['client_category'] = $value->category;
            $client[$i]['status'] = $value->status;

            if(isset($client[$i]['status'])) {

                if($client[$i]['status'] == '1') {
                  $client[$i]['status']='Active';
                }
                else if($client[$i]['status'] == '0') {
                  $client[$i]['status']='Passive';
                }
                else if($client[$i]['status'] == '2') {
                  $client[$i]['status']='Leaders';
                }
                else if($client[$i]['status'] == '3') {
                  $client[$i]['status']='Forbid';
                }
                else if($client[$i]['status'] == '4') {
                  $client[$i]['status']='Left';
                }
            }

            $client[$i]['client_address'] = $value->city;

            if ($value->account_manager_id == 0) {
                $client[$i]['client_owner'] = 'Yet to Assign';
            }
            else {
                $client[$i]['client_owner'] = $value->am_name;
            }

            if(isset($value->second_line_am) && $value->second_line_am > 0) {

                $user_details = User::getAllDetailsByUserID($value->second_line_am);

                if(isset($user_details) && $user_details != '') {
                    $client[$i]['second_line_am_name'] = $user_details->name;
                }
                else {
                    $client[$i]['second_line_am_name'] = '';
                }
            }
            else {
                $client[$i]['second_line_am_name'] = '';
            }
            
            $i++;
        }
        return $client;   
    }

    public static function getClientInfoByJobId($job_id) {

        $query = JobOpen::query();
        $query = $query->join('client_basicinfo','client_basicinfo.id','=','job_openings.client_id');
        $query = $query->leftjoin('client_address','client_address.client_id','=','client_basicinfo.id');
        $query = $query->where('job_openings.id','=',$job_id);
        $query = $query->select('client_basicinfo.id as client_id','client_basicinfo.name as cname','client_basicinfo.coordinator_name','client_basicinfo.mail','client_basicinfo.mobile','client_basicinfo.account_manager_id as account_manager','client_basicinfo.percentage_charged_below','client_basicinfo.percentage_charged_above','job_openings.posting_title', 'job_openings.city','job_openings.level_id','client_basicinfo.second_line_am','client_address.billing_street2 as area','client_address.billing_city as billing_city','client_address.billing_code as billing_code','job_openings.remote_working as remote_working');
        $response = $query->first();

        $client = array();

        if(isset($response) && $response != '') {

            $client['client_id'] = $response->client_id;
            $client['cname'] = $response->cname;
            $client['coordinator_name'] = $response->coordinator_name;
            $client['mail'] = $response->mail;
            $client['mobile'] = $response->mobile;
            $client['account_manager'] = $response->account_manager;

            if (isset($response->level_name) && $response->level_name != '') {
                $client['designation'] = $response->level_name." - ".$response->posting_title;
            }
            else {
                $client['designation'] = $response->posting_title;
            }

            if($response->remote_working == '1') {

                $client['job_location'] = "Remote";
            }
            else {

                $client['job_location'] = $response->city;
            }

            // Get Percentage charged

            if(isset($response->level_id) && $response->level_id != '') {

                if($response->level_id == '2') {
                    $percentage_charged = $response->percentage_charged_above;
                }
                if($response->level_id == '1') {
                    $percentage_charged = $response->percentage_charged_below;
                }
            }
            else {
                $percentage_charged = '';
            }
            $client['percentage_charged'] = $percentage_charged;
            
            $client['second_line_am'] = $response->second_line_am;

            $address ='';
            if($response->area != '') {
                $address .= $response->area;
            }
            if($response->billing_city != '') {
                if($address == '')
                    $address .= $response->billing_city;
                else
                    $address .= ", ".$response->billing_city;
            }
            if($response->billing_code != '') {
                if($address == '')
                    $address .= $response->billing_code;
                else
                    $address .= ", ".$response->billing_code;
            }

            $client['address'] = $address;
        }

        return $client;
    }

    public static function getClientInfo($client_ids) {

        $query = \DB::table('client_basicinfo')->select('client_basicinfo.*')
        ->where('client_basicinfo.id','=',$client_ids)->get();
            
        foreach($response as $k=>$v) {
            $client['coordinator_name'] = $v->coordinator_prefix." " .$v->coordinator_name;
        }
        return $client;
    }

    public static function getClientAboutByJobId($job_id) {

        $query = JobOpen::query();
        $query = $query->join('client_basicinfo','client_basicinfo.id','=','job_openings.client_id');
        $query = $query->where('job_openings.id','=',$job_id);
        $query = $query->select('client_basicinfo.description as cabout');
        $response = $query->first();

        $client = array();
        if(isset($response) && $response != '') {
            $client['cabout'] = $response->cabout;
        }
        return $client;
    }

    public static function getcoprefix() {

        $type = array();
        $type[''] = 'Select';
        $type['Mr.'] = 'Mr.';
        $type['Mrs.'] = 'Mrs.';
        $type['Ms.'] = 'Ms.';
        return $type;
    }

    public static function getCategory() {

        $type = array();
        $type[''] = 'Select Category';
        $type['Paramount'] = 'Paramount';
        $type['Moderate'] = 'Moderate';
        $type['Standard'] = 'Standard';
        return $type;
    }

    public static function getStatus() {

        $status = array();
        $status[0] = 'Passive';
        $status[1] = 'Active';
        return $status;
    }

    public static function getAllStatus() {

        $status = array();
        $status[0] = 'Passive';
        $status[1] = 'Active';
        $status[2] = 'Leaders';
        $status[3] = 'Forbid';
        $status[4] = 'Left';
        return $status;
    }

    public static function getClientsByType($all=0,$user_id,$limit=0,$offset=0,$search=0,$order=0,$type='asc',$status,$category=NULL) {

        $query = ClientBasicinfo::query();
        $query = $query->leftjoin('client_address','client_address.client_id','=','client_basicinfo.id');
        $query = $query->leftjoin('users', 'users.id', '=', 'client_basicinfo.account_manager_id');

        if ($all == 1) {

            $query = $query->leftJoin('client_doc',function($join) {
                $join->on('client_doc.client_id', '=', 'client_basicinfo.id');
                $join->where('client_doc.category','=','Client Contract');
            });
            $query = $query->select('client_basicinfo.*', 'users.name as am_name','users.id as am_id','client_doc.file','client_address.billing_street2 as area','client_address.billing_city as city');

            if (isset($search) && $search != '') {

                $query = $query->where(function($query) use ($search) {

                    $query = $query->where('users.name','=',"$search");
                    $query = $query->orwhere('users.first_name','=',"$search");
                    $query = $query->orwhere('client_basicinfo.name','like',"%$search%");
                    $query = $query->orwhere('client_basicinfo.coordinator_name','like',"%$search%");
                    $query = $query->orwhere('client_basicinfo.category','like',"%$search%");
                    //$query = $query->orwhere('client_basicinfo.latest_remarks','like',"%$search%");
                    $query = $query->orwhere('client_address.billing_street2','like',"%$search%");
                    $query = $query->orwhere('client_address.billing_city','like',"%$search%");
                    
                    if ($search == 'Active' || $search == 'active') {
                        $search = 1;
                        $query = $query->orwhere('client_basicinfo.status','like',"%$search%");
                    }
                    if ($search == 'Passive' || $search == 'passive') {
                        $search = 0;
                        $query = $query->orwhere('client_basicinfo.status','like',"%$search%");
                    }
                    if ($search == 'Forbid' || $search == 'forbid') {
                        $search = 3;
                        $query = $query->orwhere('client_basicinfo.status','like',"%$search%");
                    }
                    if ($search == 'Leaders' || $search == 'leaders') {
                        $search = 2;
                        $query = $query->orwhere('client_basicinfo.status','like',"%$search%");
                    }
                    if ($search == 'Left' || $search == 'left') {
                        $search = 4;
                        $query = $query->orwhere('client_basicinfo.status','like',"%$search%");
                    }
                    
                    if(($search == 'Yet') || ($search == 'Yet ') || ($search == 'yet') || ($search == 'yet ') || ($search == 'Yet to') || ($search == 'Yet to ' ) || ($search == 'Yet To') || ($search == 'Yet To ') || ($search == 'yet to') || ($search == 'yet to ') || ($search == 'yet To') || ($search == 'yet To ') || ($search == 'Yet to assign') || ($search == 'Yet To assign') || ($search == 'Yet To Assign') || ($search == 'Yet To assign') || ($search == 'Yet to Assign') || ($search == 'yet to Assign') || ($search == 'Yet to assign') || ($search == 'yet To Assign') || ($search == 'yet to assign')) {
                        $search = 0;
                        $query = $query->orwhere('client_basicinfo.account_manager_id','=',"%$search%");
                    }
                });
            }
        }
        else if ($all == 0) {

            $query = $query->select('client_basicinfo.*', 'users.name as am_name','users.id as am_id','client_address.billing_street2 as area','client_address.billing_city as city');

            /*$manager_user_id = env('MANAGERUSERID');
            $marketing_intern_user_id = env('MARKETINGINTERNUSERID');

            // visible standard and moderate clients to manager
            if($manager_user_id == $user_id || $marketing_intern_user_id == $user_id) {
            
                $query = $query->where(function($query) use ($user_id) {
                    $query = $query->where('account_manager_id',$user_id);
                    $query = $query->orwhere('client_basicinfo.category','like',"Moderate");
                    $query = $query->orwhere('client_basicinfo.category','like',"Standard");
                });
            }
            else {
                $query = $query->where('account_manager_id',$user_id);
            }*/

            $query = $query->where('account_manager_id',$user_id);

            if (isset($search) && $search != '') {

                $query = $query->where(function($query) use ($search) {

                    $query = $query->where('users.name','=',"$search");
                    $query = $query->orwhere('users.first_name','=',"$search");
                    $query = $query->orwhere('client_basicinfo.name','like',"%$search%");
                    $query = $query->orwhere('client_basicinfo.coordinator_name','like',"%$search%");
                    $query = $query->orwhere('client_basicinfo.category','like',"%$search%");
                    //$query = $query->orwhere('client_basicinfo.latest_remarks','like',"%$search%");
                    $query = $query->orwhere('client_address.billing_street2','like',"%$search%");
                    $query = $query->orwhere('client_address.billing_city','like',"%$search%");

                    if(($search == 'Yet') || ($search == 'Yet ') || ($search == 'yet') || ($search == 'yet ') || ($search == 'Yet to') || ($search == 'Yet to ' ) || ($search == 'Yet To') || ($search == 'Yet To ') || ($search == 'yet to') || ($search == 'yet to ') || ($search == 'yet To') || ($search == 'yet To ') || ($search == 'Yet to assign') || ($search == 'Yet To assign') || ($search == 'Yet To Assign') || ($search == 'Yet To assign') || ($search == 'Yet to Assign') || ($search == 'yet to Assign') || ($search == 'Yet to assign') || ($search == 'yet To Assign') || ($search == 'yet to assign')) {
                        $search = 0;
                        $query = $query->orwhere('client_basicinfo.account_manager_id','=',"%$search%");
                    }
                });
            }
        }

        if (isset($status) && $status >= 0) {
            $query = $query->where('client_basicinfo.status',$status);
        }
        if (isset($category) && $category != '') {

            if ($category == 'Paramount') {
                $query = $query->where('client_basicinfo.category','=',$category);
            }
            else if ($category == 'Moderate') {
                $query = $query->where('client_basicinfo.category','=',$category);
            }
            else if ($category == 'Standard') {
                $query = $query->where('client_basicinfo.category','=',$category);
            }

            // Not display Forbid clients
            $status_id = '3';
            $status_id_array = array($status_id);
            $query = $query->whereNotIn('client_basicinfo.status',$status_id_array);
        }

        if (isset($limit) && $limit > 0) {
            $query = $query->limit($limit);
        }
        if (isset($offset) && $offset > 0) {
            $query = $query->offset($offset);
        }
        if (isset($order) && $order !='') {
            $query = $query->orderBy($order,$type);
        }

        // Not Display Delete Client Status '1' Entry
        $query = $query->where('client_basicinfo.delete_client','=','0');

        $query = $query->groupBy('client_basicinfo.id');
        $res = $query->get();

        $client_array = array();
        $i = 0;

        foreach ($res as $key => $value) {

            $client_array[$i]['id'] = $value->id;
            $client_array[$i]['name'] = $value->name;
            $client_array[$i]['category'] = $value->category;
            $client_array[$i]['status'] = $value->status;
            $client_array[$i]['account_mangr_id'] = $value->account_manager_id;

            if ($value->account_manager_id == 0) {
                $client_array[$i]['am_name'] = 'Yet to Assign';
            }
            else {
                $client_array[$i]['am_name'] = $value->am_name;
            }

            $client_array[$i]['mobile']= $value->mobile;
            $client_array[$i]['hr_name'] = $value->coordinator_prefix . " " . $value->coordinator_name;
            $client_array[$i]['full_name'] = $value->name." - ".$value->coordinator_name." - ".$value->city;
            
            if(isset($client_array[$i]['status'])) {

                if($client_array[$i]['status'] == '1') {
                  $client_array[$i]['status']='Active';
                }
                else if($client_array[$i]['status'] == '0') {
                  $client_array[$i]['status']='Passive';
                }
                else if($client_array[$i]['status'] == '2') {
                    $client_array[$i]['status']='Leaders';
                }
                else if($client_array[$i]['status'] == '3') {
                    $client_array[$i]['status']='Forbid';
                }
                else if($client_array[$i]['status'] == '4') {
                    $client_array[$i]['status']='Left';
                }
            }
            
            $address ='';
            if($value->city!='') {
                if($address=='')
                    $address .= $value->city;
                else
                    $address .= ", ".$value->city;
            }
            $client_array[$i]['address'] = $address;

            if($value->am_id == $user_id) {
                $client_visibility_val = true;
                $client_array[$i]['client_owner'] = true;
            }
            else {
                $client_visibility_val = false;
                $client_array[$i]['client_owner'] = false;
            }

            if($client_visibility_val)
                $client_array[$i]['mail'] = $value->mail;
            else
                $client_array[$i]['mail'] = '';//$utils->mask_email($value->mail,'X',80);

            $client_array[$i]['client_visibility'] = $client_visibility_val;

            if($all == 1) {
                $client_array[$i]['url'] = $value->file;
            }
            else {
                $client_array[$i]['url'] = '';
            }

            $client_array[$i]['latest_remarks'] = $value->latest_remarks;

            $client_array[$i]['second_line_am'] = $value->second_line_am;

            if(isset($value->second_line_am) && $value->second_line_am > 0) {

                $user_details = User::getAllDetailsByUserID($value->second_line_am);

                if(isset($user_details) && $user_details != '') {
                    $client_array[$i]['second_line_am_name'] = $user_details->name;
                }
                else {
                    $client_array[$i]['second_line_am_name'] = '';
                }
            }
            else {
                $client_array[$i]['second_line_am_name'] = '';
            }
            
            $i++;
        }

        return $client_array;
    }

    public static function getClientsByTypeCount($all=0,$user_id,$search=0,$status,$category=NULL) {

        $query = ClientBasicinfo::query();
        $query = $query->leftjoin('client_address','client_address.client_id','=','client_basicinfo.id');
        $query = $query->leftjoin('users', 'users.id', '=', 'client_basicinfo.account_manager_id');

        if ($all == 1) {

            $query = $query->leftJoin('client_doc',function($join) {
                $join->on('client_doc.client_id', '=', 'client_basicinfo.id');
                $join->where('client_doc.category','=','Client Contract');
            });

            $query = $query->select('client_basicinfo.*', 'users.name as am_name','users.id as am_id','client_doc.file','client_address.billing_street2 as area','client_address.billing_city as city');

            if (isset($search) && $search != '') {

                $query = $query->where(function($query) use ($search) {

                    $query = $query->where('users.name','=',"$search");
                    $query = $query->orwhere('users.first_name','=',"$search");
                    $query = $query->orwhere('client_basicinfo.name','like',"%$search%");
                    $query = $query->orwhere('client_basicinfo.coordinator_name','like',"%$search%");
                    $query = $query->orwhere('client_basicinfo.category','like',"%$search%");
                    //$query = $query->orwhere('client_basicinfo.latest_remarks','like',"%$search%");
                    $query = $query->orwhere('client_address.billing_street2','like',"%$search%");
                    $query = $query->orwhere('client_address.billing_city','like',"%$search%");
                    
                    if ($search == 'Active' || $search == 'active') {
                        $search = 1;
                        $query = $query->orwhere('client_basicinfo.status','like',"%$search%");
                    }
                    if ($search == 'Passive' || $search == 'passive') {
                        $search = 0;
                        $query = $query->orwhere('client_basicinfo.status','like',"%$search%");
                    }
                    if ($search == 'Forbid' || $search == 'forbid') {
                        $search = 3;
                        $query = $query->orwhere('client_basicinfo.status','like',"%$search%");
                    }
                    if ($search == 'Leaders' || $search == 'leaders') {
                        $search = 2;
                        $query = $query->orwhere('client_basicinfo.status','like',"%$search%");
                    }
                    if ($search == 'Left' || $search == 'left') {
                        $search = 4;
                        $query = $query->orwhere('client_basicinfo.status','like',"%$search%");
                    }
                    
                    if(($search == 'Yet') || ($search == 'Yet ') || ($search == 'yet') || ($search == 'yet ') || ($search == 'Yet to') || ($search == 'Yet to ' ) || ($search == 'Yet To') || ($search == 'Yet To ') || ($search == 'yet to') || ($search == 'yet to ') || ($search == 'yet To') || ($search == 'yet To ') || ($search == 'Yet to assign') || ($search == 'Yet To assign') || ($search == 'Yet To Assign') || ($search == 'Yet To assign') || ($search == 'Yet to Assign') || ($search == 'yet to Assign') || ($search == 'Yet to assign') || ($search == 'yet To Assign') || ($search == 'yet to assign')) {

                        $search = 0;
                        $query = $query->orwhere('client_basicinfo.account_manager_id','=',"%$search%");
                    }
                });
            }
        }
        else if ($all == 0) {

            $query = $query->select('client_basicinfo.*', 'users.name as am_name','users.id as am_id','client_address.billing_street2 as area','client_address.billing_city as city');

            /*$manager_user_id = env('MANAGERUSERID');
            $marketing_intern_user_id = env('MARKETINGINTERNUSERID');

            // visible standard and moderate clients to manager
            if($manager_user_id == $user_id || $marketing_intern_user_id == $user_id) {
            
                $query = $query->where(function($query) use ($user_id) {
                    $query = $query->where('account_manager_id',$user_id);
                    $query = $query->orwhere('client_basicinfo.category','like',"Moderate");
                    $query = $query->orwhere('client_basicinfo.category','like',"Standard");
                });
            }
            else {
                $query = $query->where('account_manager_id',$user_id);
            }*/

            $query = $query->where('account_manager_id',$user_id);

            if (isset($search) && $search != '') {

                $query = $query->where(function($query) use ($search) {

                    $query = $query->where('users.name','=',"$search");
                    $query = $query->orwhere('users.first_name','=',"$search");
                    $query = $query->orwhere('client_basicinfo.name','like',"%$search%");
                    $query = $query->orwhere('client_basicinfo.coordinator_name','like',"%$search%");
                    $query = $query->orwhere('client_basicinfo.category','like',"%$search%");
                    //$query = $query->orwhere('client_basicinfo.latest_remarks','like',"%$search%");
                    $query = $query->orwhere('client_address.billing_street2','like',"%$search%");
                    $query = $query->orwhere('client_address.billing_city','like',"%$search%");

                    if(($search == 'Yet') || ($search == 'Yet ') || ($search == 'yet') || ($search == 'yet ') || ($search == 'Yet to') || ($search == 'Yet to ' ) || ($search == 'Yet To') || ($search == 'Yet To ') || ($search == 'yet to') || ($search == 'yet to ') || ($search == 'yet To') || ($search == 'yet To ') || ($search == 'Yet to assign') || ($search == 'Yet To assign') || ($search == 'Yet To Assign') || ($search == 'Yet To assign') || ($search == 'Yet to Assign') || ($search == 'yet to Assign') || ($search == 'Yet to assign') || ($search == 'yet To Assign') || ($search == 'yet to assign')) {
                        $search = 0;
                        $query = $query->orwhere('client_basicinfo.account_manager_id','=',"%$search%");
                    }
                });
            }
        }
        if (isset($status) && $status >= 0) {
            $query = $query->where('client_basicinfo.status',$status);
        }
        if (isset($category) && $category != '') {

            if ($category == 'Paramount') {
                $query = $query->where('client_basicinfo.category','=',$category);
            }
            else if ($category == 'Moderate') {
                $query = $query->where('client_basicinfo.category','=',$category);
            }
            else if ($category == 'Standard') {
                $query = $query->where('client_basicinfo.category','=',$category);
            }

            // Not display Forbid clients
            $status_id = '3';
            $status_id_array = array($status_id);
            $query = $query->whereNotIn('client_basicinfo.status',$status_id_array);
        }

        // Not Display Delete Client Status '1' Entry
        $query = $query->where('client_basicinfo.delete_client','=','0');

        $query = $query->orderBy('client_basicinfo.id','desc');
        $query = $query->groupBy('client_basicinfo.id');
        $query = $query->get();
        $count = $query->count();

        return $count;
    }

    public static function getClientIdByName($name) {

        $query = ClientBasicinfo::query();
        $query = $query->where('name','like',"$name");
        $query = $query->select('id');
        $res = $query->first();
        
        $client_id = 0;
        if(isset($res)){
            $client_id = $res->id;
        }
        return $client_id;
     }

     public static function getClientDetailsById($id) {

        $query = ClientBasicinfo::query();
        $query = $query->leftjoin('client_address','client_address.client_id','=','client_basicinfo.id');
        $query = $query->leftjoin('industry', 'industry.id', '=', 'client_basicinfo.industry_id');
        $query = $query->leftjoin('users', 'users.id', '=', 'client_basicinfo.account_manager_id');
        $query = $query->select('client_basicinfo.*', 'client_address.*' , 'users.name as am_name', 'industry.name as ind_name', 'users.email as am_email');
        $query = $query->where('client_basicinfo.id','=',$id);
        $res = $query->first();

        $client = array();

        if (isset($res) && $res != '') {

            $client['name'] = $res->name;
            $client['mobile'] = $res->mobile;
            $client['account_manager_id'] = $res->account_manager_id;
            $client['am_name'] = $res->am_name;
            $client['am_email'] = $res->am_email;
            $client['mail'] = $res->mail;
            $client['industry_id'] = $res->industry_id;
            $client['ind_name'] = $res->ind_name;
            $client['website'] = $res->website;
            $client['description'] = $res->description;
            $client['coordinator_name'] = $res->coordinator_prefix. " " .$res->coordinator_name;
            $client['status'] = $res->status;
            $client['display_name'] = $res->display_name;

            if(isset($client['status'])) {

                if($client['status'] == '1') {
                    $client['status'] = 'Active';
                }
                else if ($client['status'] == '0') {
                    $client['status'] = 'Passive';
                }
                else if ($client['status'] == '2') {
                    $client['status'] = 'Leaders';
                }
                else if ($client['status'] == '3') {
                    $client['status'] = 'Forbid';
                }
                else if ($client['status'] == '4') {
                    $client['status'] = 'Left';
                }
            }

            $client['billing_country'] = $res->billing_country;
            $client['billing_state'] = $res->billing_state;

            if(isset($res->billing_street2) && $res->billing_street2 != '') {
                $client['billing_street'] = $res->billing_street1.", ".$res->billing_street2;
            }
            else {
                $client['billing_street'] = $res->billing_street1;
            }

            $client['billing_code'] = $res->billing_code;
            $client['billing_city'] = $res->billing_city;

            $client['shipping_country'] = $res->shipping_country;
            $client['shipping_state'] = $res->shipping_state;

            if(isset($res->shipping_street2) && $res->shipping_street2 != '') {
                $client['shipping_street'] = $res->shipping_street1.", ".$res->shipping_street2;
            }
            else {
                $client['shipping_street'] = $res->shipping_street1;
            }

            $client['shipping_code'] = $res->shipping_code;
            $client['shipping_city'] = $res->shipping_city;

            $client['percentage_charged'] = $res->percentage_charged_above;
            $client['percentage_charged_below'] = $res->percentage_charged_below;
            $client['second_line_am'] = $res->second_line_am;

            if(isset($res->second_line_am) && $res->second_line_am > 0) {

                $user_details = User::getAllDetailsByUserID($res->second_line_am);

                if(isset($user_details) && $user_details != '') {
                    $client['second_line_am_name'] = $user_details->first_name . " " . $user_details->last_name;
                }
                else {
                    $client['second_line_am_name'] = '';
                }
            }
            else {
                $client['second_line_am_name'] = '';
            }
        }
        return $client;
     }

     // Client id by client email
     public static function getClientIdByEmail($email) {

        $query = ClientBasicinfo::query();
        $query = $query->where('mail','like',"$email");
        $query = $query->select('id');
        $res = $query->first();
        
        $client_id = 0;
        if(isset($res)) {
            $client_id = $res->id;
        }
        return $client_id;
     }

    // Get Passive Clients of Current Week
    public static function getPassiveClients($user_id) {

        $date = date('Y-m-d',strtotime('last Monday'));

        $query = ClientBasicinfo::query();
        $query = $query->leftjoin('users', 'users.id', '=', 'client_basicinfo.account_manager_id');
        $query = $query->leftjoin('client_address','client_address.client_id','=','client_basicinfo.id');
        $query = $query->select('client_basicinfo.*','users.name as account_manager','client_address.billing_street2 as area','client_address.billing_city as city');
        $query = $query->where('client_basicinfo.status','=','0');
        $query = $query->where('client_basicinfo.passive_date','>=',date('Y-m-d',strtotime('last Monday')));
        $query = $query->where('client_basicinfo.passive_date','<=',date('Y-m-d',strtotime("$date +6days")));
        $query = $query->where('client_basicinfo.account_manager_id',$user_id);

        // Not Display Delete Client Status '1' Entry
        $query = $query->where('client_basicinfo.delete_client','=','0');

        $query_response = $query->get();

        $passive_clients = array();
        $i = 0;

        foreach ($query_response as $key => $value) {

            $passive_clients[$i]['account_manager'] = $value->account_manager;
            $passive_clients[$i]['name'] = $value->name;
            $passive_clients[$i]['coordinator'] = $value->coordinator_prefix . $value->coordinator_name;
            $passive_clients[$i]['category'] = $value->category;

            $address ='';
            if($value->area != '') {
                $address .= $value->area;
            }
            if($value->city != '') {
                if($address == '')
                    $address .= $value->city;
                else
                    $address .= ", ".$value->city;
            }
            $passive_clients[$i]['address'] = $address;
            $i++;
        }
        return $passive_clients;
    }

    // Get Latest Client Remarks in Listing
    public static function getLatestRemarks($id) {

        // Get Latest Remarks time
        $remarks_res = Post::getClientLatestRemarks($id);

        if(isset($remarks_res) && $remarks_res != '') {

            $remarks_time = explode(" ", $remarks_res->updated_date);
            $remarks_new_time = Date::converttime($remarks_time[1]);
            $remarks_date = date('d-m-Y' ,strtotime($remarks_res->updated_date)) . " " . date('h:i A' ,$remarks_new_time);
            $remarks_front_date = date('d/m/Y' ,strtotime($remarks_res->updated_date));
            $remarks_user_name = User::getUserNameById($remarks_res->user_id);
        }
        else {
            $remarks_date = '';
            $remarks_front_date = '';
            $remarks_user_name = '';
        }

        // Get Latest Comments time
        $comments_res = Comments::getClientLatestComments($id);
      
        if(isset($comments_res) && $comments_res != '') {

            $comments_time = explode(" ", $comments_res->comments_updated_date);
            $comments_new_time = Date::converttime($comments_time[1]);
            $comments_date = date('d-m-Y' ,strtotime($comments_res->comments_updated_date)) . " " . date('h:i A' ,$comments_new_time);
            $comments_front_date = date('d/m/Y' ,strtotime($comments_res->comments_updated_date));
            $comments_user_name = User::getUserNameById($comments_res->user_id);
        }
        else {
            $comments_date = '';
            $comments_front_date = '';
            $comments_user_name = '';
        }

        // Check Dates

        if($remarks_date > $comments_date) {
            return $remarks_res->content . ' - ' . $remarks_front_date . ' - ' . $remarks_user_name;
        }
        if($comments_date > $remarks_date) {
            return $comments_res->comment_body . ' - ' . $comments_front_date . ' - ' . $comments_user_name;
        }
        if($remarks_date == $comments_date && $remarks_date != '' && $comments_date != '') {
            return $remarks_res->content . ' - ' . $remarks_front_date . ' - ' . $remarks_user_name;
        }
    }

    public static function getAllClientDetails() {

        $query = ClientBasicinfo::query();

        // Not Display Delete Client Status '1' Entry
        $query = $query->where('client_basicinfo.delete_client','=','0');

        $query = $query->select('client_basicinfo.*');
        $response = $query->get();

        return $response;
    }

    // Get Expected Passive Clients details in next week
    public static function getExpectedPassiveClients($client_id) {

        $client_ids = explode(",", $client_id);

        $query = ClientBasicinfo::query();
        $query = $query->leftjoin('users', 'users.id', '=', 'client_basicinfo.account_manager_id');
        $query = $query->leftjoin('client_address','client_address.client_id','=','client_basicinfo.id');
        $query = $query->select('client_basicinfo.*','users.name as account_manager','client_address.billing_street2 as area','client_address.billing_city as city');
        $query = $query->whereIn('client_basicinfo.id',$client_ids);

        // Not Display Delete Client Status '1' Entry
        $query = $query->where('client_basicinfo.delete_client','=','0');

        $query_response = $query->get();

        $expected_client = array();
        $i = 0;

        foreach ($query_response as $key => $value) {

            $expected_client[$i]['account_manager'] = $value->account_manager;
            $expected_client[$i]['name'] = $value->name;
            $expected_client[$i]['coordinator'] = $value->coordinator_prefix . $value->coordinator_name;
            $expected_client[$i]['category'] = $value->category;

            $address ='';
            if($value->area != '') {
                $address .= $value->area;
            }
            if($value->city != '') {
                
                if($address == '')
                    $address .= $value->city;
                else
                    $address .= ", ".$value->city;
            }
            
            $expected_client[$i]['address'] = $address;
            $i++;
        }
        return $expected_client;
    }

    public static function getClientsCountByAM($all=0,$user_id,$search=0) {

        $query = ClientBasicinfo::query();
        $query = $query->leftjoin('client_address','client_address.client_id','=','client_basicinfo.id');
        $query = $query->leftjoin('users', 'users.id', '=', 'client_basicinfo.account_manager_id');

        if ($all == 1) {

            $query = $query->leftJoin('client_doc',function($join) {

                $join->on('client_doc.client_id', '=', 'client_basicinfo.id');
                $join->where('client_doc.category','=','Client Contract');
            });

            $query = $query->select('client_basicinfo.*', 'users.name as am_name','users.id as am_id','client_doc.file','client_address.billing_street2 as area','client_address.billing_city as city');
        }
        else if ($all == 0) {

            $query = $query->select('client_basicinfo.*', 'users.name as am_name','users.id as am_id','client_address.billing_street2 as area','client_address.billing_city as city');

            $query = $query->where('second_line_am',$user_id);
        }

        // Not display Forbid clients
        $status_id = '3';
        $status_id_array = array($status_id);
        $query = $query->whereNotIn('client_basicinfo.status',$status_id_array);

        if (isset($search) && $search != '') {

            $query = $query->where(function($query) use ($search) {

                $query = $query->where('users.name','=',"$search");
                $query = $query->orwhere('users.first_name','=',"$search");
                $query = $query->orwhere('client_basicinfo.name','like',"%$search%");
                $query = $query->orwhere('client_basicinfo.coordinator_name','like',"%$search%");
                $query = $query->orwhere('client_basicinfo.category','like',"%$search%");
                //$query = $query->orwhere('client_basicinfo.latest_remarks','like',"%$search%");

                if ($search == 'Active' || $search == 'active') {
                    $search = 1;
                    $query = $query->orwhere('client_basicinfo.status','like',"%$search%");
                }
                if ($search == 'Passive' || $search == 'passive') {
                    $search = 0;
                    $query = $query->orwhere('client_basicinfo.status','like',"%$search%");
                }
                if ($search == 'Forbid' || $search == 'forbid') {
                    $search = 3;
                    $query = $query->orwhere('client_basicinfo.status','like',"%$search%");
                }
                if ($search == 'Leaders' || $search == 'leaders') {
                    $search = 2;
                    $query = $query->orwhere('client_basicinfo.status','like',"%$search%");
                }
                if ($search == 'Left' || $search == 'left') {
                    $search = 4;
                    $query = $query->orwhere('client_basicinfo.status','like',"%$search%");
                }
                
                $query = $query->orwhere('client_address.billing_street2','like',"%$search%");
                $query = $query->orwhere('client_address.billing_city','like',"%$search%");

                if(($search == 'Yet') || ($search == 'Yet ') || ($search == 'yet') || ($search == 'yet ') || ($search == 'Yet to') || ($search == 'Yet to ' ) || ($search == 'Yet To') || ($search == 'Yet To ') || ($search == 'yet to') || ($search == 'yet to ') || ($search == 'yet To') || ($search == 'yet To ') || ($search == 'Yet to assign') || ($search == 'Yet To assign') || ($search == 'Yet To Assign') || ($search == 'Yet To assign') || ($search == 'Yet to Assign') || ($search == 'yet to Assign') || ($search == 'Yet to assign') || ($search == 'yet To Assign') || ($search == 'yet to assign')) {
                    
                    $search = 0;
                    $query = $query->orwhere('client_basicinfo.account_manager_id','=',"%$search%");
                }
            });
        }

        // Not Display Delete Client Status '1' Entry
        $query = $query->where('client_basicinfo.delete_client','=','0');

        // Display only second line am list
        $query = $query->where('client_basicinfo.second_line_am','!=','0');

        $query = $query->orderBy('client_basicinfo.id','desc');
        $query = $query->groupBy('client_basicinfo.id');
        $query = $query->get();
        $count = $query->count();

        return $count;
    }

    public static function getAllClientsByAM($all=0,$user_id,$limit=0,$offset=0,$search=0,$order=0,$type='asc') {

        $status_id = '3';
        $status_id_array = array($status_id);

        $query = ClientBasicinfo::query();
        $query = $query->leftjoin('client_address','client_address.client_id','=','client_basicinfo.id');
        $query = $query->leftjoin('users', 'users.id', '=', 'client_basicinfo.account_manager_id');

        // Not display Forbid clients
        $query = $query->whereNotIn('client_basicinfo.status',$status_id_array);
        
        if ($all == 1) {

            $query = $query->leftJoin('client_doc',function($join) {

                $join->on('client_doc.client_id', '=', 'client_basicinfo.id');
                $join->where('client_doc.category','=','Client Contract');
            });

            $query = $query->select('client_basicinfo.*', 'users.name as am_name','users.id as am_id','client_doc.file','client_address.billing_street2 as area','client_address.billing_city as city');

            if (isset($search) && $search != '') {

                $query = $query->where(function($query) use ($search) {

                    $query = $query->where('users.name','=',"$search");
                    $query = $query->orwhere('users.first_name','=',"$search");
                    $query = $query->orwhere('client_basicinfo.name','like',"%$search%");
                    $query = $query->orwhere('client_basicinfo.coordinator_name','like',"%$search%");
                    $query = $query->orwhere('client_basicinfo.category','like',"%$search%");
                    //$query = $query->orwhere('client_basicinfo.latest_remarks','like',"%$search%");

                    if ($search == 'Active' || $search == 'active') {
                        $search = 1;
                        $query = $query->orwhere('client_basicinfo.status','like',"%$search%");
                    }
                    if ($search == 'Passive' || $search == 'passive') {
                        $search = 0;
                        $query = $query->orwhere('client_basicinfo.status','like',"%$search%");
                    }
                    if ($search == 'Forbid' || $search == 'forbid') {
                        $search = 3;
                        $query = $query->orwhere('client_basicinfo.status','like',"%$search%");
                    }
                    if ($search == 'Leaders' || $search == 'leaders') {
                        $search = 2;
                        $query = $query->orwhere('client_basicinfo.status','like',"%$search%");
                    }
                    if ($search == 'Left' || $search == 'left') {
                        $search = 4;
                        $query = $query->orwhere('client_basicinfo.status','like',"%$search%");
                    }
                    $query = $query->orwhere('client_address.billing_street2','like',"%$search%");
                    $query = $query->orwhere('client_address.billing_city','like',"%$search%");

                    if(($search == 'Yet') || ($search == 'Yet ') || ($search == 'yet') || ($search == 'yet ') || ($search == 'Yet to') || ($search == 'Yet to ' ) || ($search == 'Yet To') || ($search == 'Yet To ') || ($search == 'yet to') || ($search == 'yet to ') || ($search == 'yet To') || ($search == 'yet To ') || ($search == 'Yet to assign') || ($search == 'Yet To assign') || ($search == 'Yet To Assign') || ($search == 'Yet To assign') || ($search == 'Yet to Assign') || ($search == 'yet to Assign') || ($search == 'Yet to assign') || ($search == 'yet To Assign') || ($search == 'yet to assign')) {
                        $search = 0;
                        $query = $query->orwhere('client_basicinfo.account_manager_id','=',"%$search%");
                    }
                });
            }
        }

        else if ($all == 0) {

            $query = $query->select('client_basicinfo.*', 'users.name as am_name','users.id as am_id','client_address.billing_street2 as area','client_address.billing_city as city');

            $query = $query->where('second_line_am',$user_id);
            
            if (isset($search) && $search != '') {

                $query = $query->where(function($query) use ($search) {

                    $query = $query->where('users.name','=',"$search");
                    $query = $query->orwhere('users.first_name','=',"$search");
                    $query = $query->orwhere('client_basicinfo.name','like',"%$search%");
                    $query = $query->orwhere('client_basicinfo.coordinator_name','like',"%$search%");
                    $query = $query->orwhere('client_basicinfo.category','like',"%$search%");
                    //$query = $query->orwhere('client_basicinfo.latest_remarks','like',"%$search%");

                    if ($search == 'Active' || $search == 'active') {
                        $search = 1;
                        $query = $query->orwhere('client_basicinfo.status','like',"%$search%");
                    }
                    if ($search == 'Passive' || $search == 'passive') {
                        $search = 0;
                        $query = $query->orwhere('client_basicinfo.status','like',"%$search%");
                    }
                    if ($search == 'Forbid' || $search == 'forbid') {
                        $search = 3;
                        $query = $query->orwhere('client_basicinfo.status','like',"%$search%");
                    }
                    if ($search == 'Leaders' || $search == 'leaders') {
                        $search = 2;
                        $query = $query->orwhere('client_basicinfo.status','like',"%$search%");
                    }
                    if ($search == 'Left' || $search == 'left') {
                        $search = 4;
                        $query = $query->orwhere('client_basicinfo.status','like',"%$search%");
                    }

                    $query = $query->orwhere('client_address.billing_street2','like',"%$search%");
                    $query = $query->orwhere('client_address.billing_city','like',"%$search%");

                    if(($search == 'Yet') || ($search == 'Yet ') || ($search == 'yet') || ($search == 'yet ') || ($search == 'Yet to') || ($search == 'Yet to ' ) || ($search == 'Yet To') || ($search == 'Yet To ') || ($search == 'yet to') || ($search == 'yet to ') || ($search == 'yet To') || ($search == 'yet To ') || ($search == 'Yet to assign') || ($search == 'Yet To assign') || ($search == 'Yet To Assign') || ($search == 'Yet To assign') || ($search == 'Yet to Assign') || ($search == 'yet to Assign') || ($search == 'Yet to assign') || ($search == 'yet To Assign') || ($search == 'yet to assign')) {
                        $search = 0;
                        $query = $query->orwhere('client_basicinfo.account_manager_id','=',"%$search%");
                    }
                });
            }
        }

        if (isset($limit) && $limit > 0) {
            $query = $query->limit($limit);
        }
        if (isset($offset) && $offset > 0) {
            $query = $query->offset($offset);
        }
        if (isset($order) && $order !='') {
            $query = $query->orderBy($order,$type);
        }

        // Not Display Delete Client Status '1' Entry
        $query = $query->where('client_basicinfo.delete_client','=','0');

        // Display only second line am list
        $query = $query->where('client_basicinfo.second_line_am','!=','0');

        $query = $query->groupBy('client_basicinfo.id');
        $res = $query->get();

        $client_array = array();
        $i = 0;

        foreach ($res as $key => $value) {

            $client_array[$i]['id'] = $value->id;
            $client_array[$i]['latest_remarks'] = $value->latest_remarks;

            $client_array[$i]['name'] = $value->name;

            if ($value->account_manager_id == 0) {
                $client_array[$i]['am_name'] = 'Yet to Assign';
            }
            else {
                $client_array[$i]['am_name'] = $value->am_name;
            }

            $client_array[$i]['category'] = $value->category;
            $client_array[$i]['status'] = $value->status;
            $client_array[$i]['account_mangr_id'] = $value->account_manager_id;
            $client_array[$i]['mobile'] = $value->mobile;
            $client_array[$i]['hr_name'] = $value->coordinator_prefix . " " . $value->coordinator_name;
            $client_array[$i]['full_name'] = $value->name." - ".$value->coordinator_name." - ".$value->city;

            if(isset($client_array[$i]['status'])) {

                if($client_array[$i]['status'] == '1') {
                  $client_array[$i]['status']='Active';
                }
                else if($client_array[$i]['status'] == '0') {
                  $client_array[$i]['status']='Passive';
                }
                else if($client_array[$i]['status'] == '2') {
                    $client_array[$i]['status']='Leaders';
                }
                else if($client_array[$i]['status'] == '3') {
                    $client_array[$i]['status']='Forbid';
                }
                else if($client_array[$i]['status'] == '4') {
                    $client_array[$i]['status']='Left';
                }
            }
            
            $address ='';
            if($value->city!='') {

                if($address=='')
                    $address .= $value->city;
                else
                    $address .= ", ".$value->city;
            }
            $client_array[$i]['address'] = $address;

            if($value->am_id == $user_id) {
                $client_visibility_val = true;
                $client_array[$i]['client_owner'] = true;
            }
            else {
                $client_visibility_val = false;
                $client_array[$i]['client_owner'] = false;
            }

            if($client_visibility_val)
                $client_array[$i]['mail'] = $value->mail;
            else
                $client_array[$i]['mail'] = '';//$utils->mask_email($value->mail,'X',80);

            $client_array[$i]['client_visibility'] = $client_visibility_val;

            if($all == 1) {
                $client_array[$i]['url'] = $value->file;
            }
            else {
                $client_array[$i]['url'] = '';
            }

            $client_array[$i]['second_line_am'] = $value->second_line_am;

            if(isset($value->second_line_am) && $value->second_line_am > 0) {

                $user_details = User::getAllDetailsByUserID($value->second_line_am);

                if(isset($user_details) && $user_details != '') {
                    $client_array[$i]['second_line_am_name'] = $user_details->name;
                }
                else {
                    $client_array[$i]['second_line_am_name'] = '';
                }
            }
            else {
                $client_array[$i]['second_line_am_name'] = '';
            }

            $i++;
        }
        return $client_array;
    }

    public static function getBefore7daysClientDetails($user_id) {

        $status_id = '3';
        $status_id_array = array($status_id);
        $date = date('Y-m-d h:m:s', strtotime('-7 days'));

        $query = ClientBasicinfo::query();
        $query = $query->leftjoin('client_address','client_address.client_id','=','client_basicinfo.id');

        $query = $query->where('client_basicinfo.account_manager_id',$user_id);

        // Not display Forbid clients
        $query = $query->whereNotIn('client_basicinfo.status',$status_id_array);
        
        // Not Display Delete Client Status '1' Entry
        $query = $query->where('client_basicinfo.delete_client','=','0');

        // Get before 7 days client list
        $query = $query->where('client_basicinfo.created_at','<=',$date);

        $query = $query->where(function($query) {

            $query = $query->where('client_basicinfo.description','=','');
            $query = $query->orwhere('client_address.billing_city','=',NULL);
        });

        $query = $query->select('client_basicinfo.*');

        $query = $query->groupBy('client_basicinfo.id');
        $query = $query->orderBy('client_basicinfo.id','desc');
        $response = $query->get();

        $i=0;

        if(isset($response) && sizeof($response) > 0) {

            $client_name_string = '';

            foreach ($response as $key => $value) {

                $full_name = $value->name." - ".$value->coordinator_prefix . " "  . $value->coordinator_name;

                if($full_name != '') {

                    if($client_name_string =='')
                        $client_name_string .= $full_name;
                    else
                        $client_name_string .= ", ".$full_name;
                }

                $i++;
            }
        }
        else {
            $client_name_string = '';
        }
        return $client_name_string;
    }

    public static function getClientStatusArrayByIds($ids) {

        $client_query = ClientBasicinfo::query();

        $client_query = $client_query->select('client_basicinfo.id','client_basicinfo.status');

        $client_query = $client_query->whereIn('client_basicinfo.id',$ids);
        $client_response = $client_query->get();

        $status_array = array();
        $i=0;

        if(isset($client_response) && sizeof($client_response) > 0){

            foreach ($client_response as $key => $value) {
                
                $status_array[$i] = $value->status;
                $i++;
            }
        }

        $status_array = array_unique($status_array);

        return $status_array;
    }
}