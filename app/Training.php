<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Training extends Model
{
    public $table = "training";

    public static function getAlltraining($all=0,$user_id,$limit=0,$offset=0,$search=0,$order=0,$type='asc'){

             
        $training_open_query = Training::query();
        $training_open_query = $training_open_query->select('training.*','training_doc.file as url');

        if ($all==0) {
            $training_open_query = $training_open_query->join('training_visible_users','training_visible_users.training_id','=','training.id');
            $training_open_query = $training_open_query->where('user_id','=',$user_id);
            if (isset($search) && $search != '') {
                $training_open_query = $training_open_query->where(function($training_open_query) use ($search){
                    $training_open_query = $training_open_query->where('training.title','like',"%$search%");
                });
            }
        }
        else {
            if (isset($search) && $search != '') {
                $training_open_query = $training_open_query->where(function($training_open_query) use ($search){
                    $training_open_query = $training_open_query->where('training.title','like',"%$search%");
                });
            }
        }
        $training_open_query = $training_open_query->leftjoin('training_doc','training_doc.training_id','=','training.id');

        if (isset($limit) && $limit > 0) {
            $training_open_query = $training_open_query->limit($limit);
        }
        if (isset($offset) && $offset > 0) {
            $training_open_query = $training_open_query->offset($offset);
        }
        if (isset($order) && $order !='') {
            $training_open_query = $training_open_query->orderBy($order,$type);
        }
        //$training_open_query = $training_open_query->orderBy('id','asc');
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

    public static function getAlltrainingCount($all=0,$user_id,$search=0){

             
        $training_open_query = Training::query();
        $training_open_query = $training_open_query->select('training.*','training_doc.file as url');

        if ($all==0) {
            $training_open_query = $training_open_query->join('training_visible_users','training_visible_users.training_id','=','training.id');
            $training_open_query = $training_open_query->where('user_id','=',$user_id);
            if (isset($search) && $search != '') {
                $training_open_query = $training_open_query->where(function($training_open_query) use ($search){
                    $training_open_query = $training_open_query->where('training.title','like',"%$search%");
                });
            }
        }
        else {
            if (isset($search) && $search != '') {
                $training_open_query = $training_open_query->where(function($training_open_query) use ($search){
                    $training_open_query = $training_open_query->where('training.title','like',"%$search%");
                });
            }
        }
        $training_open_query = $training_open_query->leftjoin('training_doc','training_doc.training_id','=','training.id');
        $training_open_query = $training_open_query->groupBy('training.id');
        $training_res = $training_open_query->get();
        $training_count = sizeof($training_res);
        
        return $training_count;
    }

    public static function getAlltrainingIds($select_all=0){

        $query = Training::query();
        $query = $query->select('training.id');
        if ($select_all > 0) {
            $query = $query->where('training.select_all',$select_all);
        }
        $res = $query->get();

        $training_id = array();
        $i = 0;
        if (isset($res) && $res != '') {
            foreach ($res as $key => $value) {
                $training_id[$i] = $value->id;
                $i++;
            }
        }

        return $training_id;
    }

}
