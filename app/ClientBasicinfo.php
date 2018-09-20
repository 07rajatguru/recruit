<?php

namespace App;

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
    public static $rules
        = array(
            'name' => 'required',
            'mail' => 'unique:client_basicinfo,mail,{id}',
            'mobile'  => 'required',
           // 'industry_id' => 'required',
            'coordinator_name' => 'required'
        );


    public function messages()
    {
        return [
            'name.required' => 'Name is required field',
            //'name.unique' => 'Name is unique field',
            'mail.required' => 'Mail is required field',
            'mail.unique' => 'Mail is unique field',
            'mobile'  => 'Mobile is required field',
            //'industry_id' => 'Industry is required field',
        ];
    }

    public static function getAllClients($all=0,$user_id,$rolePermissions,$limit=0,$offset=0,$search=0,$order=0,$type=NULL){

        $client_visibility = false;
        $client_visibility_id = env('CLIENTVISIBILITY');
        if(isset($client_visibility_id) && in_array($client_visibility_id,$rolePermissions)){
            $client_visibility = true;
        }

        $query = ClientBasicinfo::query();
        $query = $query->join('client_address','client_address.client_id','=','client_basicinfo.id');
        $query = $query->join('users', 'users.id', '=', 'client_basicinfo.account_manager_id');
        if ($all == 1) {
            $query = $query->leftJoin('client_doc',function($join){
                                $join->on('client_doc.client_id', '=', 'client_basicinfo.id');
                                $join->where('client_doc.category','=','Client Contract');
                            });
            $query = $query->select('client_basicinfo.*', 'users.name as am_name','users.id as am_id','client_doc.file','client_address.billing_street2 as area','client_address.billing_city as city');
        }
        else if ($all == 0){
            $query = $query->select('client_basicinfo.*', 'users.name as am_name','users.id as am_id','client_address.billing_street2 as area','client_address.billing_city as city');
            $query = $query->where('account_manager_id',$user_id);
        }
        if (isset($limit) && $limit > 0) {
            $query = $query->limit($limit);
        }
        if (isset($offset) && $offset > 0) {
            $query = $query->offset($offset);
        }
        if (isset($search) && $search != '') {
            $query = $query->where('users.name','like',"%$search%");
            $query = $query->orwhere('client_basicinfo.name','like',"%$search%");
            $query = $query->orwhere('client_basicinfo.coordinator_name','like',"%$search%");
            if ($search == 'Active' || $search == 'active') {
                $search = 1;
                $query = $query->orwhere('client_basicinfo.status','like',"%$search%");
            }
            if ($search == 'Passive' || $search == 'passive') {
                $search = 0;
                $query = $query->orwhere('client_basicinfo.status','like',"%$search%");
            }
            $query = $query->orwhere('client_address.billing_street2','like',"%$search%");
            $query = $query->orwhere('client_address.billing_city','like',"%$search%");
        }
        if (isset($order) && $order >= 0) {
            if (isset($type) && $type != '') {
                if ($order == 1) {
                    $query = $query->orderBy('users.name',$type);
                }
                else if ($order == 2) {
                    $query = $query->orderBy('client_basicinfo.name',$type);
                }
                else if ($order == 3) {
                    $query = $query->orderBy('client_basicinfo.coordinator_prefix',$type);
                }
                else if ($order == 4) {
                    $query = $query->orderBy('client_basicinfo.status',$type);
                }
                else if ($order == 5) {
                    $query = $query->orderBy('client_address.billing_street2',$type);
                }
            }
        }
        $query = $query->groupBy('client_basicinfo.id');
        $res = $query->get();

        $client_array = array();
        $i = 0;
        foreach ($res as $key => $value) {
            $client_array[$i]['id'] = $value->id;
            $client_array[$i]['name'] = $value->name;
            $client_array[$i]['am_name'] = $value->am_name;
            $client_array[$i]['category']=$value->category;
            $client_array[$i]['status']=$value->status;
            $client_array[$i]['account_mangr_id']=$value->account_manager_id;
            $client_array[$i]['mobile']= $value->mobile;
            $client_array[$i]['hr_name'] = $value->coordinator_prefix . " " . $value->coordinator_name;

            if(isset($client_array[$i]['status']))
            {
                if($client_array[$i]['status']== '1')
                {
                  $client_array[$i]['status']='Active';
                }
                else
                {
                  $client_array[$i]['status']='Passive';
                }
            }
            
            $address ='';
            if($value->area!=''){
                $address .= $value->area;
            }
            if($value->city!=''){
                if($address=='')
                    $address .= $value->city;
                else
                    $address .= ", ".$value->city;
            }

            $client_array[$i]['address'] = $address;
            if($value->am_id==$user_id){
                $client_visibility_val = true;
                $client_array[$i]['client_owner'] = true;
            }
            else {
                $client_visibility_val = $client_visibility;
                $client_array[$i]['client_owner'] = false;
            }

            if($client_visibility_val)
                $client_array[$i]['mail'] = $value->mail;
            else
                $client_array[$i]['mail'] = '';//$utils->mask_email($value->mail,'X',80);

            $client_array[$i]['client_visibility'] = $client_visibility_val;

            if($all == 1){
                $client_array[$i]['url'] = $value->file;
            }
            else{
                $client_array[$i]['url'] = '';
            }
            $i++;
        }

        return $client_array;
    }

    public static function getAllClientsCount($all=0,$user_id,$search=0){

        $query = ClientBasicinfo::query();
        $query = $query->join('client_address','client_address.client_id','=','client_basicinfo.id');
        $query = $query->join('users', 'users.id', '=', 'client_basicinfo.account_manager_id');
        if ($all == 1) {
            $query = $query->leftJoin('client_doc',function($join){
                                $join->on('client_doc.client_id', '=', 'client_basicinfo.id');
                                $join->where('client_doc.category','=','Client Contract');
                            });
            $query = $query->select('client_basicinfo.*', 'users.name as am_name','users.id as am_id','client_doc.file','client_address.billing_street2 as area','client_address.billing_city as city');
        }
        else if ($all == 0){
            $query = $query->select('client_basicinfo.*', 'users.name as am_name','users.id as am_id','client_address.billing_street2 as area','client_address.billing_city as city');
            $query = $query->where('account_manager_id',$user_id);
        }
        if (isset($search) && $search != '') {
            $query = $query->where('users.name','like',"%$search%");
            $query = $query->orwhere('client_basicinfo.name','like',"%$search%");
            $query = $query->orwhere('client_basicinfo.coordinator_name','like',"%$search%");
            if ($search == 'Active' || $search == 'active') {
                $search = 1;
                $query = $query->orwhere('client_basicinfo.status','like',"%$search%");
            }
            if ($search == 'Inactive' || $search == 'inactive') {
                $search = 0;
                $query = $query->orwhere('client_basicinfo.status','like',"%$search%");
            }
        }
        $query = $query->orderBy('client_basicinfo.id','desc');
        $query = $query->groupBy('client_basicinfo.id');
        $res = $query->get();

        return sizeof($res);
    }

    public static function getClientArray(){
        $clientArray = array('' => 'Select');

        $clientDetails = ClientBasicinfo::all();
        if(isset($clientDetails) && sizeof($clientDetails) > 0){
            foreach ($clientDetails as $clientDetail) {
                $clientArray[$clientDetail->id] = $clientDetail->name;
            }
        }
        return $clientArray;
    }

    public static function getLoggedInUserClients($user_id){

        $client_query = ClientBasicinfo::query();
        $client_query = $client_query->join('client_address','client_address.client_id','=','client_basicinfo.id');

        if($user_id>0)
            $client_query = $client_query->where('client_basicinfo.account_manager_id','=',$user_id);

        $client_query = $client_query->select('client_basicinfo.*','client_address.client_id','client_address.billing_city');

        $client_response = $client_query->get();

        return $client_response;
    }

    public static function getClientsByIds($user_id,$ids){

        $client_query = ClientBasicinfo::query();
        $client_query = $client_query->join('client_address','client_address.client_id','=','client_basicinfo.id');

        if($user_id>0)
            $client_query = $client_query->where('client_basicinfo.account_manager_id','=',$user_id);

        $client_query = $client_query->select('client_basicinfo.*','client_address.client_id','client_address.billing_city');

        $client_query = $client_query->whereIn('client_basicinfo.id',$ids);

        $client_response = $client_query->get();

        return $client_response;
    }

    public static function checkAssociation($id){

        $job_query = JobOpen::query();
        $job_query = $job_query->where('client_id','=',$id);
        $job_res = $job_query->first();
        
        if(isset($job_res->client_id) && $job_res->client_id==$id){
            return false;
        }
        else{ 
            return true;
      }
    }

/*     public static function checkAssociatedJob($id){

        $job_query = JobOpen::query();
        $job_query = $job_query->where('client_id','=',$id);
        $job_query = $job_query->select('job_openings.*','job_openings.posting_title','job_openings.city');
        $job_res = $job_query->get();
        
        return $job_res;
    }*/

    public static function checkClientByEmail($email){

        $client_query = ClientBasicinfo::query();
        $client_query = $client_query->where('mail','like',$email);
        $client_cnt = $client_query->count();

        return $client_cnt;

    }


     public static function getClientEmailByID($id)
     {

        $client_email='';
        $client_query = ClientBasicinfo::query();
        $client_query = $client_query->where('id','=',$id);
        $client_query = $client_query->first();

        if(isset($client_query))
        {
            $client_email=$client_query->mail;
        }
        return $client_email;

     }

     


     public static function getClientNameByID($id)
     {

        $client_name='';
        $client_query = ClientBasicinfo::query();
        $client_query = $client_query->where('id','=',$id);
        $client_query = $client_query->first();

        if(isset($client_query))
        {
            $client_name=$client_query->coordinator_prefix." " .$client_query->coordinator_name;
        }
        return $client_name;

     }

    public static function getCompanyOfClientByID($id)
     {

        $client_company='';
        $client_query = ClientBasicinfo::query();
        $client_query = $client_query->where('id','=',$id);
        $client_query = $client_query->first();

        if(isset($client_query))
        {
            $client_company=$client_query->name;
        }
        return $client_company;

     }

    public static function getBillingCityOfClientByID($id)
    {

        $client_city='';
        $client_query = ClientBasicinfo::query();
        $client_query = $client_query->join('client_address','client_address.client_id','=','client_basicinfo.id');
        $client_query = $client_query->select('client_address.billing_city as city');
        $client_query = $client_query->where('client_basicinfo.id','=',$id);
        $client_query = $client_query->first();

        if(isset($client_query))
        {
            $client_city=$client_query->city;
        }
        return $client_city;
     }

    public static function getMonthWiseClientByUserId($user_id,$all=0)
    {
        $month = date('m');

        $query = ClientBasicinfo::query();
        $query = $query->leftjoin('client_address','client_address.client_id','=','client_basicinfo.id');
        $query = $query->leftJoin('users','users.id','=','client_basicinfo.account_manager_id');
        $query = $query->select('client_basicinfo.*','users.name as am_name','users.id as am_id','client_address.billing_city as city');
        $query = $query->whereRaw('MONTH(client_basicinfo.created_at) = ?',[$month]);

        if($all==0)
        {
            $query = $query->where(function($query) use ($user_id)
            {
                $query = $query->where('client_basicinfo.account_manager_id',$user_id);
            });
        }
        $query = $query->orderBy('client_basicinfo.id','desc');
        $query = $query->groupBy('client_basicinfo.id');
        $response = $query->get();

        //echo sizeof($response);
        //print_r($response);
        //exit;

        $client=array();
        $i=0;
        foreach ($response as $key => $value) 
        {
            $client[$i]['id'] = $value->id;
            $client[$i]['client_owner'] = $value->am_name;
            $client[$i]['company_name'] = $value->name;
            $client[$i]['coordinator_name'] = $value->coordinator_prefix . " " . $value->coordinator_name;
            $client[$i]['client_category'] = $value->category;
            $client[$i]['status'] = $value->status;
            if(isset($client[$i]['status']))
            {
                if($client[$i]['status']== '1')
                {
                  $client[$i]['status']='Active';
                }
                else
                {
                  $client[$i]['status']='Passive';
                }
            }

            $client[$i]['client_address'] = $value->city;
            $i++;
        }

        /*print_r($client);
        exit;*/
        return $client;   
    }
    
    public function beforeValidate ()
    {
        // In case of update, ignore current user's ID for unique check of Username and Email Address

        //print_r(Alluser::$rules);exit;
        if (isset ($this->id) && $this->id > 0)
        {
            ClientBasicinfo::$rules['name'] = str_replace ('{id}', $this->id, ClientBasicinfo::$rules['name']);
            ClientBasicinfo::$rules['mail'] = str_replace ('{id}', $this->id, ClientBasicinfo::$rules['mail']);
        }
        else
        {
            ClientBasicinfo::$rules['name'] = str_replace ('{id}', "NULL", ClientBasicinfo::$rules['name']);
            ClientBasicinfo::$rules['mail'] = str_replace ('{id}', "NULL", ClientBasicinfo::$rules['mail']);

        }

        return true;
    }

    public static function getClientInfoByJobId($job_id){

        $query = JobOpen::query();
        $query = $query->join('client_basicinfo','client_basicinfo.id','=','job_openings.client_id');
        $query = $query->where('job_openings.id','=',$job_id);
        $query = $query->select('client_basicinfo.name as cname','client_basicinfo.coordinator_name','client_basicinfo.mail','client_basicinfo.mobile',
            'job_openings.posting_title','job_openings.city');
        $response = $query->get();

        $client = array();
        foreach ($response as $k=>$v){
            $client['cname'] = $v->cname;
            $client['coordinator_name'] = $v->coordinator_name;
            $client['mail'] = $v->mail;
            $client['mobile'] = $v->mobile;
            $client['designation'] = $v->posting_title;
            $client['job_location'] = $v->city;
        }

        return $client;
    }

    public static function getClientInfo($client_ids)
    {
            $query=\DB::table('client_basicinfo')
                ->select('client_basicinfo.*')
                ->where('client_basicinfo.id','=',$client_ids)
                ->get();

            /*print_r($qyery);
            exit;*/
            
            foreach($response as $k=>$v)
            {
                $client['coordinator_name'] = $v->coordinator_prefix." " .$v->coordinator_name;
            }
            return $client;

    }

    public static function getClientAboutByJobId($job_id){

        $query = JobOpen::query();
        $query = $query->join('client_basicinfo','client_basicinfo.id','=','job_openings.client_id');
        $query = $query->where('job_openings.id','=',$job_id);
        $query = $query->select('client_basicinfo.description as cabout');
        $response = $query->get();


        $client = array();
        foreach ($response as $k=>$v){
            $client['cabout'] = strip_tags($v->cabout);
        }

        return $client;
    }

    public static function getcoprefix()
    {

        $type = array();
        $type['']='Select';
        $type['Mr.'] = 'Mr.';
        $type['Mrs.'] = 'Mrs.';
        $type['Ms.'] = 'Ms.';
        return $type;
    }

    public static function getCategory()
    {

        $type = array();
        $type['']='Select Category';
        $type['Paramount'] = 'Paramount';
        $type['Moderate'] = 'Moderate';
        $type['Standard'] = 'Standard';
        return $type;
    }
}
