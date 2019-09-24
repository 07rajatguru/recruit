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

    public function post() {
        return $this->hasMany('App\Post','client_id');
    }

    public static function getAllClients($all=0,$user_id,$rolePermissions,$limit=0,$offset=0,$search=0,$order=0,$type='asc'){

        $client_visibility = false;
        $client_visibility_id = env('CLIENTVISIBILITY');
        if(isset($client_visibility_id) && in_array($client_visibility_id,$rolePermissions)){
            $client_visibility = true;
        }
        $status_id = '3';
        $status_id_array = array($status_id);

        $query = ClientBasicinfo::query();
        $query = $query->leftjoin('client_address','client_address.client_id','=','client_basicinfo.id');
        $query = $query->leftjoin('users', 'users.id', '=', 'client_basicinfo.account_manager_id');

        // Not display Forbid clients
        $query = $query->whereNotIn('client_basicinfo.status',$status_id_array);
        
        if ($all == 1) {
            $query = $query->leftJoin('client_doc',function($join){
                                $join->on('client_doc.client_id', '=', 'client_basicinfo.id');
                                $join->where('client_doc.category','=','Client Contract');
                            });
            $query = $query->select('client_basicinfo.*', 'users.name as am_name','users.id as am_id','client_doc.file','client_address.billing_street2 as area','client_address.billing_city as city');

            if (isset($search) && $search != '') {
                $query = $query->where(function($query) use ($search){
                    $query = $query->where('users.name','like',"%$search%");
                    $query = $query->orwhere('client_basicinfo.name','like',"%$search%");
                    $query = $query->orwhere('client_basicinfo.coordinator_name','like',"%$search%");
                    $query = $query->orwhere('client_basicinfo.category','like',"%$search%");
                    if ($search == 'Active' || $search == 'active') {
                        $search = 1;
                        $query = $query->orwhere('client_basicinfo.status','like',"%$search%");
                    }
                    if ($search == 'Passive' || $search == 'passive') {
                        $search = 0;
                        $query = $query->orwhere('client_basicinfo.status','like',"%$search%");
                    }
                   /* if ($search == 'Forbid' || $search == 'forbid') {
                        $search = 3;
                        $query = $query->orwhere('client_basicinfo.status','like',"%$search%");
                    }*/
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

                    if($search == 'Yet' || $search == 'yet') {
                        $search = 0;
                        $query = $query->orwhere('client_basicinfo.account_manager_id','like',"%$search%");
                    }
                    if($search == 'Yet to' || $search == 'yet to') {
                        $search = 0;
                        $query = $query->orwhere('client_basicinfo.account_manager_id','like',"%$search%");
                    }
                    if($search == 'Yet to Assign' || $search == 'yet to assign') {
                        $search = 0;
                        $query = $query->orwhere('client_basicinfo.account_manager_id','like',"%$search%");
                    }
                });
            }

        }
        else if ($all == 0){
            $query = $query->select('client_basicinfo.*', 'users.name as am_name','users.id as am_id','client_address.billing_street2 as area','client_address.billing_city as city');
            $manager_user_id = env('MANAGERUSERID');
            $marketing_intern_user_id = env('MARKETINGINTERNUSERID');

            // visible standard and moderate clients to manager
            if($manager_user_id == $user_id || $marketing_intern_user_id == $user_id){
                    $query = $query->where(function($query) use ($user_id){
                    $query = $query->where('account_manager_id',$user_id);
                    $query = $query->orwhere('client_basicinfo.category','like',"Moderate");
                    $query = $query->orwhere('client_basicinfo.category','like',"Standard");
                 });
            }
            else{
                $query = $query->where('account_manager_id',$user_id);
            }
            
            if (isset($search) && $search != '') {
                $query = $query->where(function($query) use ($search){
                    $query = $query->where('users.name','like',"%$search%");
                    $query = $query->orwhere('client_basicinfo.name','like',"%$search%");
                    $query = $query->orwhere('client_basicinfo.coordinator_name','like',"%$search%");
                    $query = $query->orwhere('client_basicinfo.category','like',"%$search%");
                    if ($search == 'Active' || $search == 'active') {
                        $search = 1;
                        $query = $query->orwhere('client_basicinfo.status','like',"%$search%");
                    }
                    if ($search == 'Passive' || $search == 'passive') {
                        $search = 0;
                        $query = $query->orwhere('client_basicinfo.status','like',"%$search%");
                    }

                    if($search == 'Yet' || $search == 'yet') {
                        $search = 0;
                        $query = $query->orwhere('client_basicinfo.account_manager_id','like',"%$search%");
                    }
                    if($search == 'Yet to' || $search == 'yet to') {
                        $search = 0;
                        $query = $query->orwhere('client_basicinfo.account_manager_id','like',"%$search%");
                    }
                    if($search == 'Yet to Assign' || $search == 'yet to assign') {
                        $search = 0;
                        $query = $query->orwhere('client_basicinfo.account_manager_id','like',"%$search%");
                    }
                    $query = $query->orwhere('client_address.billing_street2','like',"%$search%");
                    $query = $query->orwhere('client_address.billing_city','like',"%$search%");
                });
            }
        }
        if (isset($limit) && $limit > 0) {
            $query = $query->limit($limit);
        }
        if (isset($offset) && $offset > 0) {
            $query = $query->offset($offset);
        }

       // echo $order;exit;
        if (isset($order) && $order !='') {
            $query = $query->orderBy($order,$type);
        }


        $query = $query->groupBy('client_basicinfo.id');
        $res = $query->get();
       // print_r($query->toSql());exit;

        $client_array = array();
        $i = 0;
        foreach ($res as $key => $value) {
            $client_array[$i]['id'] = $value->id;
            $client_array[$i]['latest_remarks'] = self::getClientLatestRemarks($value->id);

            $client_array[$i]['name'] = $value->name;
            if ($value->account_manager_id == 0) {
                $client_array[$i]['am_name'] = 'Yet to Assign';
            }
            else {
                $client_array[$i]['am_name'] = $value->am_name;
            }
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
                else  if($client_array[$i]['status']== '0')
                {
                  $client_array[$i]['status']='Passive';
                }
                else  if($client_array[$i]['status']== '2')
                {
                    $client_array[$i]['status']='Leaders';
                }
                else  if($client_array[$i]['status']== '3')
                {
                    $client_array[$i]['status']='Forbid';
                }
                else  if($client_array[$i]['status']== '4')
                {
                    $client_array[$i]['status']='Left';
                }
            }
            
            $address ='';
            // if($value->area!=''){
            //     $address .= $value->area;
            // }
            // if($value->city!=''){
            //     if($address=='')
            //         $address .= $value->city;
            //     else
            //         $address .= ", ".$value->city;
            // }

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
        $query = $query->leftjoin('client_address','client_address.client_id','=','client_basicinfo.id');
        $query = $query->leftjoin('users', 'users.id', '=', 'client_basicinfo.account_manager_id');
        if ($all == 1) {
            $query = $query->leftJoin('client_doc',function($join){
                                $join->on('client_doc.client_id', '=', 'client_basicinfo.id');
                                $join->where('client_doc.category','=','Client Contract');
                            });
            $query = $query->select('client_basicinfo.*', 'users.name as am_name','users.id as am_id','client_doc.file','client_address.billing_street2 as area','client_address.billing_city as city');
        }
        else if ($all == 0){
            $query = $query->select('client_basicinfo.*', 'users.name as am_name','users.id as am_id','client_address.billing_street2 as area','client_address.billing_city as city');
            // $query = $query->where('account_manager_id',$user_id);
            $manager_user_id = env('MANAGERUSERID');
            $marketing_intern_user_id = env('MARKETINGINTERNUSERID');

            // visible standard and moderate clients to manager
            if($manager_user_id == $user_id || $marketing_intern_user_id == $user_id){
                $query = $query->where(function($query) use ($user_id){
                    $query = $query->where('account_manager_id',$user_id);
                    $query = $query->orwhere('client_basicinfo.category','like',"Moderate");
                    $query = $query->orwhere('client_basicinfo.category','like',"Standard");
                });
            }
            else{
                $query = $query->where('account_manager_id',$user_id);
            }
            
        }

        // Not display Forbid clients

        $status_id = '3';
        $status_id_array = array($status_id);
        $query = $query->whereNotIn('client_basicinfo.status',$status_id_array);

       /* if (isset($search) && $search != '') {
            $query = $query->where(function($query) use ($status_id)
            {
                $search = $status_id;
                $query = $query->whereNotIn('client_basicinfo.status','like',"%$search%");
            });
        }   */

        if (isset($search) && $search != '') {
            $query = $query->where(function($query) use ($search){
                $query = $query->where('users.name','like',"%$search%");
                $query = $query->orwhere('client_basicinfo.name','like',"%$search%");
                $query = $query->orwhere('client_basicinfo.coordinator_name','like',"%$search%");
                $query = $query->orwhere('client_basicinfo.category','like',"%$search%");
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

                if($search == 'Yet' || $search == 'yet') {
                    $search = 0;
                    $query = $query->orwhere('client_basicinfo.account_manager_id','like',"%$search%");
                }
                if($search == 'Yet to' || $search == 'yet to') {
                    $search = 0;
                    $query = $query->orwhere('client_basicinfo.account_manager_id','like',"%$search%");
                }
                if($search == 'Yet to Assign' || $search == 'yet to assign') {
                    $search = 0;
                    $query = $query->orwhere('client_basicinfo.account_manager_id','like',"%$search%");
                }
            });
        }

        $query = $query->orderBy('client_basicinfo.id','desc');
        $query = $query->groupBy('client_basicinfo.id');
        $query = $query->get();
        $res = $query->count();

        return $res;
    }

    public static function getClientArray(){
        $clientArray = array('0' => 'Select');

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
        $year = date('Y');

        $query = ClientBasicinfo::query();
        $query = $query->leftjoin('client_address','client_address.client_id','=','client_basicinfo.id');
        $query = $query->leftJoin('users','users.id','=','client_basicinfo.account_manager_id');
        $query = $query->select('client_basicinfo.*','users.name as am_name','users.id as am_id','client_address.billing_city as city');
        $query = $query->whereRaw('MONTH(client_basicinfo.created_at) = ?',[$month]);
        $query = $query->whereRaw('YEAR(client_basicinfo.created_at) = ?',[$year]);

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
                else if($client[$i]['status']== '0')
                {
                  $client[$i]['status']='Passive';
                }
                else if($client[$i]['status']== '2')
                {
                  $client[$i]['status']='Leaders';
                }
                else if($client[$i]['status']== '3')
                {
                  $client[$i]['status']='Forbid';
                }
                else if($client[$i]['status']== '4')
                {
                  $client[$i]['status']='Left';
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
        $query = $query->select('client_basicinfo.name as cname','client_basicinfo.coordinator_name','client_basicinfo.mail','client_basicinfo.mobile','client_basicinfo.account_manager_id as account_manager',
            'job_openings.posting_title','job_openings.city');
        $response = $query->get();

        $client = array();
        foreach ($response as $k=>$v){
            $client['cname'] = $v->cname;
            $client['coordinator_name'] = $v->coordinator_name;
            $client['mail'] = $v->mail;
            $client['mobile'] = $v->mobile;
            $client['account_manager'] = $v->account_manager;
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

    public static function getStatus(){
        $status = array();
        $status[0] = 'Passive';
        $status[1] = 'Active';
        $status[2] = 'Leaders';
        $status[3] = 'Forbid';
        $status[4] = 'Left';
        return $status;
    }

    public static function getClientsByType($all=0,$user_id,$rolePermissions,$status,$category=NULL){

        $client_visibility = false;
        $client_visibility_id = env('CLIENTVISIBILITY');
        if(isset($client_visibility_id) && in_array($client_visibility_id,$rolePermissions)){
            $client_visibility = true;
        }

        $query = ClientBasicinfo::query();
        $query = $query->leftjoin('client_address','client_address.client_id','=','client_basicinfo.id');
        $query = $query->leftjoin('users', 'users.id', '=', 'client_basicinfo.account_manager_id');
        if ($all == 1) {
            $query = $query->leftJoin('client_doc',function($join){
                                $join->on('client_doc.client_id', '=', 'client_basicinfo.id');
                                $join->where('client_doc.category','=','Client Contract');
                            });
            $query = $query->select('client_basicinfo.*', 'users.name as am_name','users.id as am_id','client_doc.file','client_address.billing_street2 as area','client_address.billing_city as city');
        }
        else if ($all == 0){
            $query = $query->select('client_basicinfo.*', 'users.name as am_name','users.id as am_id','client_address.billing_street2 as area','client_address.billing_city as city');

            $manager_user_id = env('MANAGERUSERID');
            $marketing_intern_user_id = env('MARKETINGINTERNUSERID');

            // visible standard and moderate clients to manager
            if($manager_user_id == $user_id || $marketing_intern_user_id == $user_id){
            
                $query = $query->where(function($query) use ($user_id){
                    $query = $query->where('account_manager_id',$user_id);
                    $query = $query->orwhere('client_basicinfo.category','like',"Moderate");
                    $query = $query->orwhere('client_basicinfo.category','like',"Standard");
                });
            }
            else{
                $query = $query->where('account_manager_id',$user_id);
            }
        }
        if (isset($status) && $status >= 0) {
            $query = $query->where('client_basicinfo.status',$status);
        }
        if (isset($category) && $category != '') {
            if ($category == 'Paramount') {
                $query = $query->where('client_basicinfo.category','=',$category);
            }
            elseif ($category == 'Moderate') {
                $query = $query->where('client_basicinfo.category','=',$category);
            }
            elseif ($category == 'Standard') {
                $query = $query->where('client_basicinfo.category','=',$category);
            }
        }

        // Not display Forbid clients

        $status_id = '3';
        $status_id_array = array($status_id);
        $query = $query->whereNotIn('client_basicinfo.status',$status_id_array);

        $query = $query->groupBy('client_basicinfo.id');
        $res = $query->get();

        $client_array = array();
        $i = 0;
        foreach ($res as $key => $value) {
            $client_array[$i]['id'] = $value->id;
            $client_array[$i]['name'] = $value->name;
            //$client_array[$i]['am_name'] = $value->am_name;
            $client_array[$i]['category']=$value->category;
            $client_array[$i]['status']=$value->status;
            $client_array[$i]['account_mangr_id']=$value->account_manager_id;

            if ($value->account_manager_id == 0) {
                $client_array[$i]['am_name'] = 'Yet to Assign';
            }
            else {
                $client_array[$i]['am_name'] = $value->am_name;
            }

            $client_array[$i]['mobile']= $value->mobile;
            $client_array[$i]['hr_name'] = $value->coordinator_prefix . " " . $value->coordinator_name;
            if(isset($client_array[$i]['status'])){
                if($client_array[$i]['status']== '1'){
                  $client_array[$i]['status']='Active';
                }
                else if($client_array[$i]['status']== '0'){
                  $client_array[$i]['status']='Passive';
                }
                else if($client_array[$i]['status']== '2'){
                    $client_array[$i]['status']='Leaders';
                }
                else if($client_array[$i]['status']== '3'){
                    $client_array[$i]['status']='Forbid';
                }
                else if($client_array[$i]['status']== '4'){
                    $client_array[$i]['status']='Left';
                }
            }
            
            $address ='';
/*            if($value->area!=''){
                $address .= $value->area;
            }
            if($value->city!=''){
                if($address=='')
                    $address .= $value->city;
                else
                    $address .= ", ".$value->city;
            }*/

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

    // Get Forbid Clients

    public static function getForbidClients($all=0,$user_id,$rolePermissions,$status){

        $client_visibility = false;
        $client_visibility_id = env('CLIENTVISIBILITY');
        if(isset($client_visibility_id) && in_array($client_visibility_id,$rolePermissions)){
            $client_visibility = true;
        }

        $query = ClientBasicinfo::query();
        $query = $query->leftjoin('client_address','client_address.client_id','=','client_basicinfo.id');
        $query = $query->leftjoin('users', 'users.id', '=', 'client_basicinfo.account_manager_id');
        if ($all == 1) {
            $query = $query->leftJoin('client_doc',function($join){
                                $join->on('client_doc.client_id', '=', 'client_basicinfo.id');
                                $join->where('client_doc.category','=','Client Contract');
                            });
            $query = $query->select('client_basicinfo.*', 'users.name as am_name','users.id as am_id','client_doc.file','client_address.billing_street2 as area','client_address.billing_city as city');
        }
        else if ($all == 0){
            $query = $query->select('client_basicinfo.*', 'users.name as am_name','users.id as am_id','client_address.billing_street2 as area','client_address.billing_city as city');

            $manager_user_id = env('MANAGERUSERID');
            $marketing_intern_user_id = env('MARKETINGINTERNUSERID');

            // visible standard and moderate clients to manager
            if($manager_user_id == $user_id || $marketing_intern_user_id == $user_id){
           
                $query = $query->where(function($query) use ($user_id){
                    $query = $query->where('account_manager_id',$user_id);
                    $query = $query->orwhere('client_basicinfo.category','like',"Moderate");
                    $query = $query->orwhere('client_basicinfo.category','like',"Standard");
                });
            }
            else{
                $query = $query->where('account_manager_id',$user_id);
            }
        }
        if (isset($status) && $status >= 0) {
            $query = $query->where('client_basicinfo.status',$status);
        }

        $query = $query->groupBy('client_basicinfo.id');
        $res = $query->get();

        $client_array = array();
        $i = 0;
        foreach ($res as $key => $value) {
            $client_array[$i]['id'] = $value->id;
            $client_array[$i]['name'] = $value->name;
            //$client_array[$i]['am_name'] = $value->am_name;
            $client_array[$i]['category']=$value->category;
            $client_array[$i]['status']=$value->status;
            $client_array[$i]['account_mangr_id']=$value->account_manager_id;

            if ($value->account_manager_id == 0) {
                $client_array[$i]['am_name'] = 'Yet to Assign';
            }
            else {
                $client_array[$i]['am_name'] = $value->am_name;
            }

            $client_array[$i]['mobile']= $value->mobile;
            $client_array[$i]['hr_name'] = $value->coordinator_prefix . " " . $value->coordinator_name;
            if(isset($client_array[$i]['status'])){
                if($client_array[$i]['status']== '1'){
                  $client_array[$i]['status']='Active';
                }
                else if($client_array[$i]['status']== '0'){
                  $client_array[$i]['status']='Passive';
                }
                else if($client_array[$i]['status']== '2'){
                    $client_array[$i]['status']='Leaders';
                }
                else if($client_array[$i]['status']== '3'){
                    $client_array[$i]['status']='Forbid';
                }
                else if($client_array[$i]['status']== '4'){
                    $client_array[$i]['status']='Left';
                }
            }
            
            $address ='';
           /* if($value->area!=''){
                $address .= $value->area;
            }
            if($value->city!=''){
                if($address=='')
                    $address .= $value->city;
                else
                    $address .= ", ".$value->city;
            }*/
           
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

    public static function getClientIdByName($name){

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

     public static function getClientDetailsById($id){

        $query = ClientBasicinfo::query();
        $query = $query->leftjoin('client_address','client_address.client_id','=','client_basicinfo.id');
        $query = $query->leftjoin('industry', 'industry.id', '=', 'client_basicinfo.industry_id');
        $query = $query->join('users', 'users.id', '=', 'client_basicinfo.account_manager_id');
        $query = $query->select('client_basicinfo.*', 'client_address.*' , 'users.name as am_name', 'industry.name as ind_name');
        $query = $query->where('client_basicinfo.id','=',$id);
        $res = $query->first();

        $client = array();
        if (isset($res) && $res != '') {
            $client['name'] = $res->name;
            $client['mobile'] = $res->mobile;
            $client['am_name'] = $res->am_name;
            $client['mail'] = $res->mail;
            $client['ind_name'] = $res->ind_name;
            $client['website'] = $res->website;
            $client['description'] = $res->description;
            $client['coordinator_name'] = $res->coordinator_prefix. " " .$res->coordinator_name;
            $client['status']=$res->status;
            $client['display_name'] = $res->display_name;
            if(isset($client['status'])){
                if($client['status'] == '1'){
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
            $client['billing_street'] = $res->billing_street1.", ".$res->billing_street2;
            $client['billing_code'] = $res->billing_code;
            $client['billing_city'] = $res->billing_city;
            $client['shipping_country'] = $res->shipping_country;
            $client['shipping_state'] = $res->shipping_state;
            $client['shipping_street'] = $res->shipping_street1.", ".$res->shipping_street2;
            $client['shipping_code'] = $res->shipping_code;
            $client['shipping_city'] = $res->shipping_city;
            $client['percentage_charged'] = $res->percentage_charged_above;
        }

        return $client;
     }

     // Client id by client email
     public static function getClientIdByEmail($email){

        $query = ClientBasicinfo::query();
        $query = $query->where('mail','like',"$email");
        $query = $query->select('id');
        $res = $query->first();
        
        $client_id = 0;
        if(isset($res)){
            $client_id = $res->id;
        }

        return $client_id;
     }

    // Get Passive Clients of Current Week
    public static function getPassiveClients(){

        $date = date('Y-m-d',strtotime('Monday this week'));

        $query = ClientBasicinfo::query();
        $query = $query->leftjoin('users', 'users.id', '=', 'client_basicinfo.account_manager_id');
        $query = $query->leftjoin('client_address','client_address.client_id','=','client_basicinfo.id');
        $query = $query->select('client_basicinfo.*','users.name as account_manager','client_address.billing_street2 as area','client_address.billing_city as city');
        $query = $query->where('client_basicinfo.status','=','0');
        $query = $query->where('client_basicinfo.passive_date','>=',date('Y-m-d',strtotime('Monday this week')));
        $query = $query->where('client_basicinfo.passive_date','<=',date('Y-m-d',strtotime("$date +6days")));
        $query_response = $query->get();

        return $query_response;
    }

    // Get Latest Client Remarks in Listing
    public static function getClientLatestRemarks($id)
    {
        $query = ClientBasicinfo::query();
        $query = $query->leftjoin('post', 'post.client_id', '=', 'client_basicinfo.id');
        $query = $query->leftjoin('comments','comments.commentable_id','=','post.id');
        $query = $query->where('client_basicinfo.id','=',$id);
        $query = $query->orderBy('post.updated_at','DESC');
        $query = $query->orderBy('comments.updated_at','DESC');
        $query = $query->select('post.content as content','comments.body as comment_body');
        $response = $query->first();

        if(isset($response->comment_body) && $response->comment_body != '')
        {
            return $response->comment_body;
        }
        else
        {
            if(isset($response->content) && $response->content != '')
            {
                return $response->content;
            }
        }
    }
}
