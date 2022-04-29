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

    public static function getAllEmailTemplateNames($user_id) {

        $query = EmailTemplate::query();
        $query = $query->leftjoin('email_template_visible_users','email_template_visible_users.email_template_id','=','email_template.id');
        $query = $query->select('email_template.*');

        if(isset($user_id) && $user_id != 0) {
            $query = $query->where('email_template_visible_users.user_id','=',$user_id);
        }

        $query = $query->orderBy('id','desc');
        $response = $query->get();

        $template_array = array();

        if(isset($response) && sizeof($response) > 0) {

            $template_array[0] = 'Select Template';

            foreach ($response as $templates) {
                $template_array[$templates->id] = $templates->name;
            }
        }
        return $template_array;
    }

    public static function getEmailTemplateDetailsById($template_id){

        $template_query = EmailTemplate::query();
        $template_query = $template_query->where('email_template.id',$template_id);
        $template_query = $template_query->select('email_template.*');
        $response = $template_query->first();

        $template_array = array();

        $template_array['id'] = $response->id;
        $template_array['name'] = $response->name;
        $template_array['subject'] = $response->subject;
        $template_array['email_body'] = $response->email_body;

        return $template_array;
    }
}
