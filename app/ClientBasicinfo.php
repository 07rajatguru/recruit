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
