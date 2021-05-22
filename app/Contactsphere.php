<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Contactsphere extends Model
{
    public $table = "contactsphere";

    public static function getAllContactsCount($all=0,$user_id,$search=NULL) {

        $convert_lead = '0';
        $hold_status = '0';
        $forbid_status = '0';

        $query = Contactsphere::query();

        $query = $query->leftjoin('users','users.id','=','contactsphere.referred_by');

        $query = $query->where('convert_lead',$convert_lead);
        $query = $query->where('hold',$hold_status);
        $query = $query->where('forbid',$forbid_status);

        $query = $query->select('contactsphere.*', 'users.name as referred_by');

        if($all == 0) {
            $query = $query->where('referred_by',$user_id);
        }
        
        if (isset($search) && $search != '') {

            $query = $query->where(function($query) use ($search) {

                $query = $query->where('contactsphere.name','like',"%$search%");
                $query = $query->orwhere('contactsphere.designation','like',"%$search%");
                $query = $query->orwhere('contactsphere.company','like',"%$search%");
                $query = $query->orwhere('contactsphere.contact_number','like',"%$search%");
                $query = $query->orwhere('contactsphere.city','like',"%$search%");
                $query = $query->orwhere('contactsphere.official_email_id','like',"%$search%");
                $query = $query->orwhere('contactsphere.personal_id','like',"%$search%");
                $query = $query->orwhere('users.name','like',"%$search%");
            });
        }
        $count = $query->count();

        return $count;
    }

    public static function getAllContacts($all=0,$user_id,$limit=0,$offset=0,$search=NULL,$order=NULL,$type='desc') {

        $convert_lead = '0';
        $hold_status = '0';
        $forbid_status = '0';

        $query = Contactsphere::query();

        $query = $query->leftjoin('users','users.id','=','contactsphere.referred_by');

        $query = $query->where('convert_lead',$convert_lead);
        $query = $query->where('hold',$hold_status);
        $query = $query->where('forbid',$forbid_status);

        $query = $query->select('contactsphere.*', 'users.name as referred_by');

        if (isset($order) && $order != '') {
            $query = $query->orderBy($order,$type);
        }
        if (isset($limit) && $limit > 0) {
            $query = $query->limit($limit);
        }
        if (isset($offset) && $offset > 0) {
            $query = $query->offset($offset);
        }

        if($all == 0) {
            $query = $query->where('referred_by',$user_id);
        }

        if (isset($search) && $search != '') {

            $query = $query->where(function($query) use ($search) {

                $query = $query->where('contactsphere.name','like',"%$search%");
                $query = $query->orwhere('contactsphere.designation','like',"%$search%");
                $query = $query->orwhere('contactsphere.company','like',"%$search%");
                $query = $query->orwhere('contactsphere.contact_number','like',"%$search%");
                $query = $query->orwhere('contactsphere.city','like',"%$search%");
                $query = $query->orwhere('contactsphere.official_email_id','like',"%$search%");
                $query = $query->orwhere('contactsphere.personal_id','like',"%$search%");
                $query = $query->orwhere('users.name','like',"%$search%");
            });
        }
        $response = $query->get();

        $i = 0;
        $contacts_array = array();

        foreach ($response as $key => $value) {

            $contacts_array[$i]['id'] = $value->id;
            $contacts_array[$i]['name'] = $value->name;            
            $contacts_array[$i]['designation'] = $value->designation;
            $contacts_array[$i]['company'] = $value->company;
            $contacts_array[$i]['contact_number'] = $value->contact_number;
            $contacts_array[$i]['city'] = $value->city;
            $contacts_array[$i]['official_email_id'] = $value->official_email_id;
            $contacts_array[$i]['personal_id'] = $value->personal_id;
            $contacts_array[$i]['referred_by'] = $value->referred_by;
            $contacts_array[$i]['convert_lead'] = $value->convert_lead;

            $i++;
        }
        return $contacts_array;
    }

    public static function getConvertedLead($all=0,$user_id) {

        $convert_lead = '1';
        $query = Contactsphere::query();
        $query = $query->select('contactsphere.*');
        $query = $query->where('convert_lead',$convert_lead);

        if($all==0) {
            $query = $query->where('referred_by',$user_id);
        }
        $count = $query->count();

        return $count;
    }

    public static function getHoldContactsCount($all=0,$user_id,$search=NULL) {

        $convert_lead = '0';
        $hold_status = '1';

        $query = Contactsphere::query();

        $query = $query->leftjoin('users','users.id','=','contactsphere.referred_by');

        $query = $query->where('convert_lead',$convert_lead);
        $query = $query->where('hold',$hold_status);

        $query = $query->select('contactsphere.*', 'users.name as referred_by');

        if($all==0) {
            $query = $query->where('referred_by',$user_id);
        }

        if (isset($search) && $search != '') {

            $query = $query->where(function($query) use ($search) {

                $query = $query->where('contactsphere.name','like',"%$search%");
                $query = $query->orwhere('contactsphere.designation','like',"%$search%");
                $query = $query->orwhere('contactsphere.company','like',"%$search%");
                $query = $query->orwhere('contactsphere.contact_number','like',"%$search%");
                $query = $query->orwhere('contactsphere.city','like',"%$search%");
                $query = $query->orwhere('contactsphere.official_email_id','like',"%$search%");
                $query = $query->orwhere('contactsphere.personal_id','like',"%$search%");
                $query = $query->orwhere('users.name','like',"%$search%");
            });
        }
        $count = $query->count();

        return $count;
    }

    public static function getHoldContacts($all=0,$user_id,$limit=0,$offset=0,$search=NULL,$order=NULL,$type='desc') {

        $convert_lead = '0';
        $hold_status = '1';

        $query = Contactsphere::query();

        $query = $query->leftjoin('users','users.id','=','contactsphere.referred_by');

        $query = $query->where('convert_lead',$convert_lead);
        $query = $query->where('hold',$hold_status);

        $query = $query->select('contactsphere.*', 'users.name as referred_by');

        if($all==0) {
            $query = $query->where('referred_by',$user_id);
        }

        if (isset($search) && $search != '') {

            $query = $query->where(function($query) use ($search) {

                $query = $query->where('contactsphere.name','like',"%$search%");
                $query = $query->orwhere('contactsphere.designation','like',"%$search%");
                $query = $query->orwhere('contactsphere.company','like',"%$search%");
                $query = $query->orwhere('contactsphere.contact_number','like',"%$search%");
                $query = $query->orwhere('contactsphere.city','like',"%$search%");
                $query = $query->orwhere('contactsphere.official_email_id','like',"%$search%");
                $query = $query->orwhere('contactsphere.personal_id','like',"%$search%");
                $query = $query->orwhere('users.name','like',"%$search%");
            });
        }

        if (isset($order) && $order != '') {
            $query = $query->orderBy($order,$type);
        }
        if (isset($limit) && $limit > 0) {
            $query = $query->limit($limit);
        }
        if (isset($offset) && $offset > 0) {
            $query = $query->offset($offset);
        }
        $response = $query->get();

        $i = 0;
        $contacts_array = array();

        foreach ($response as $key => $value) {

            $contacts_array[$i]['id'] = $value->id;
            $contacts_array[$i]['name'] = $value->name;            
            $contacts_array[$i]['designation'] = $value->designation;
            $contacts_array[$i]['company'] = $value->company;
            $contacts_array[$i]['contact_number'] = $value->contact_number;
            $contacts_array[$i]['city'] = $value->city;
            $contacts_array[$i]['official_email_id'] = $value->official_email_id;
            $contacts_array[$i]['personal_id'] = $value->personal_id;
            $contacts_array[$i]['referred_by'] = $value->referred_by;
            $contacts_array[$i]['convert_lead'] = $value->convert_lead;

            $i++;
        }
        return $contacts_array;
    }

    public static function getForbidContactsCount($all=0,$user_id,$search=NULL) {

        $convert_lead = '0';
        $forbid_status = '1';

        $query = Contactsphere::query();

        $query = $query->leftjoin('users','users.id','=','contactsphere.referred_by');

        $query = $query->where('convert_lead',$convert_lead);
        $query = $query->where('forbid',$forbid_status);

        $query = $query->select('contactsphere.*', 'users.name as referred_by');

        if($all==0) {
            $query = $query->where('referred_by',$user_id);
        }

        if (isset($search) && $search != '') {

            $query = $query->where(function($query) use ($search) {

                $query = $query->where('contactsphere.name','like',"%$search%");
                $query = $query->orwhere('contactsphere.designation','like',"%$search%");
                $query = $query->orwhere('contactsphere.company','like',"%$search%");
                $query = $query->orwhere('contactsphere.contact_number','like',"%$search%");
                $query = $query->orwhere('contactsphere.city','like',"%$search%");
                $query = $query->orwhere('contactsphere.official_email_id','like',"%$search%");
                $query = $query->orwhere('contactsphere.personal_id','like',"%$search%");
                $query = $query->orwhere('users.name','like',"%$search%");
            });
        }
        $count = $query->count();
        
        return $count;
    }

    public static function getForbidContacts($all=0,$user_id,$limit=0,$offset=0,$search=NULL,$order=NULL,$type='desc') {

        $convert_lead = '0';
        $forbid_status = '1';

        $query = Contactsphere::query();

        $query = $query->leftjoin('users','users.id','=','contactsphere.referred_by');

        $query = $query->where('convert_lead',$convert_lead);
        $query = $query->where('forbid',$forbid_status);

        $query = $query->select('contactsphere.*', 'users.name as referred_by');

        if($all==0) {
            $query = $query->where('referred_by',$user_id);
        }

        if (isset($search) && $search != '') {

            $query = $query->where(function($query) use ($search) {

                $query = $query->where('contactsphere.name','like',"%$search%");
                $query = $query->orwhere('contactsphere.designation','like',"%$search%");
                $query = $query->orwhere('contactsphere.company','like',"%$search%");
                $query = $query->orwhere('contactsphere.contact_number','like',"%$search%");
                $query = $query->orwhere('contactsphere.city','like',"%$search%");
                $query = $query->orwhere('contactsphere.official_email_id','like',"%$search%");
                $query = $query->orwhere('contactsphere.personal_id','like',"%$search%");
                $query = $query->orwhere('users.name','like',"%$search%");
            });
        }

        if (isset($order) && $order != '') {
            $query = $query->orderBy($order,$type);
        }
        if (isset($limit) && $limit > 0) {
            $query = $query->limit($limit);
        }
        if (isset($offset) && $offset > 0) {
            $query = $query->offset($offset);
        }
        $response = $query->get();

        $i = 0;
        $contacts_array = array();

        foreach ($response as $key => $value) {

            $contacts_array[$i]['id'] = $value->id;
            $contacts_array[$i]['name'] = $value->name;            
            $contacts_array[$i]['designation'] = $value->designation;
            $contacts_array[$i]['company'] = $value->company;
            $contacts_array[$i]['contact_number'] = $value->contact_number;
            $contacts_array[$i]['city'] = $value->city;
            $contacts_array[$i]['official_email_id'] = $value->official_email_id;
            $contacts_array[$i]['personal_id'] = $value->personal_id;
            $contacts_array[$i]['referred_by'] = $value->referred_by;
            $contacts_array[$i]['convert_lead'] = $value->convert_lead;

            $i++;
        }
        return $contacts_array;
    }
}