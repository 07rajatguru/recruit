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

    public static function checkClientByEmail($email){

        $client_query = ClientBasicinfo::query();
        $client_query = $client_query->where('mail','like',$email);
        $client_cnt = $client_query->count();

        return $client_cnt;

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
}
