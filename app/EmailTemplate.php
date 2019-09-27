<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EmailTemplate extends Model
{
    public $table = "email_template";

    public static function getAllEmailTemplates(){

    	$query = EmailTemplate::query();
    	$query = $query->select('email_template.*');
    	$query = $query->orderBy('id','desc');
    	$res = $query->get();

    	$email_template = array();
    	$i = 0;
    	foreach ($res as $key => $value) {
    		$email_template[$i]['id'] = $value->id;
    		$email_template[$i]['name'] = $value->name;
    		$email_template[$i]['subject'] = $value->subject;
    		$i++;
    	}

    	return $email_template;
    }
}
