<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Training extends Model
{
    public $table = "training";

    public static function getAlltraining($user_id){

             
        $training_open_query = Training::query();
        $training_open_query = $training_open_query->select('training.*');       
        $training_response = $training_open_query->get();

        $training_list = array();
        
        $i = 0;
        foreach ($training_response as $key=>$value){
            $training_list[$i]['id'] = $value->id;
            $training_list[$i]['title'] = $value->title;
        	$training_list[$i]['owner_id'] = $value->owner_id;
             /* if (isset($value->owner_id) && $value->owner_id == $user_id){
                  $training_list[$i]['access'] = '1';
              }
              else{
                  $training_list[$i]['access'] = '0';
              }*/
            $i++;
        }

       // echo '<pre>';print_r($training_list);exit;
        return $training_list;
    }

}
