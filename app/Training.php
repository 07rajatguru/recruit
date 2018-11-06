<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Training extends Model
{
    public $table = "training";

    public static function getAlltraining($all=0,$user_id){

             
        $training_open_query = Training::query();
        $training_open_query = $training_open_query->select('training.*','training_doc.file as url');

        if ($all==0) {
          $training_open_query = $training_open_query->join('training_visible_users','training_visible_users.training_id','=','training.id');
          $training_open_query = $training_open_query->where('user_id','=',$user_id);
        }
        $training_open_query = $training_open_query->leftjoin('training_doc','training_doc.training_id','=','training.id');

        $training_open_query = $training_open_query->orderBy('id','asc');
        $training_open_query = $training_open_query->groupBy('training.id');
        $training_response = $training_open_query->get();

        $training_list = array();
        
        $i = 0;
        foreach ($training_response as $key=>$value){
            $training_list[$i]['id'] = $value->id;
            $training_list[$i]['title'] = $value->title;
        	  $training_list[$i]['owner_id'] = $value->owner_id;
            if ($all==1) {
              $training_list[$i]['access'] = '1';
            }
            else{
                if (isset($value->owner_id) && $value->owner_id == $user_id){
                  $training_list[$i]['access'] = '1';
                }
                else{
                  $training_list[$i]['access'] = '0';
                }
            }
            $training_list[$i]['file_url'] = $value->url;
            $i++;
        }

       // echo '<pre>';print_r($training_list);exit;
        return $training_list;
    }

}
