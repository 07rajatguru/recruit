<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProcessManual extends Model
{
   public $table = "process_manual";

  public static function getAllprocess($all=0,$user_id,$limit=0,$offset=0,$search=0,$order=0,$type='asc'){
  
    $process_open_query = ProcessManual::query();
    $process_open_query = $process_open_query->select('process_manual.*','process_doc.file as url');
    // assign jobs to logged in user
    if($all==0){
      $process_open_query = $process_open_query->join('process_visible_users','process_visible_users.process_id','=','process_manual.id');
      $process_open_query = $process_open_query->where('user_id','=',$user_id);
      if (isset($search) && $search != '') {
        $process_open_query = $process_open_query->where(function($process_open_query) use ($search){
          $process_open_query = $process_open_query->where('process_manual.title','like',"%$search%");
        });
      }
    }
    else {
      if (isset($search) && $search != '') {
        $process_open_query = $process_open_query->where(function($process_open_query) use ($search){
          $process_open_query = $process_open_query->where('process_manual.title','like',"%$search%");
        });
      }
    }
    $process_open_query = $process_open_query->leftjoin('process_doc','process_doc.process_id','=','process_manual.id');
    if (isset($limit) && $limit > 0) {
      $process_open_query = $process_open_query->limit($limit);
    }
    if (isset($offset) && $offset > 0) {
      $process_open_query = $process_open_query->offset($offset);
    }
    if (isset($order) && $order !='') {
      $process_open_query = $process_open_query->orderBy($order,$type);
    }
    
    $process_open_query = $process_open_query->orderBy('process_manual.position','asc');
    $process_open_query = $process_open_query->groupBy('process_manual.id');
    $process_response = $process_open_query->get();

    $process_list = array();
    $i = 0;
    foreach ($process_response as $key=>$value){
        $process_list[$i]['id'] = $value->id;
        $process_list[$i]['title'] = $value->title;
        $process_list[$i]['url'] = $value->url;

        // Admin/super admin have access to all details
        if($all==1){
            $process_list[$i]['access'] = '1';
        }
        else{
          if (isset($value->owner_id) && $value->owner_id == $user_id){
              $process_list[$i]['access'] = '1';
          }
          else{
              $process_list[$i]['access'] = '0';
          }
        }
        $i++;
    }

    return $process_list;
  }

  public static function getProcessManualsDocCount($process_id){

      $query = ProcessDoc::query();
      $query = $query->where('process_id',$process_id);
      $response = $query->count();

      return $response;
  }

  public static function getAllprocessCount($all=0,$user_id,$search=0){
  
    $process_open_query = ProcessManual::query();
    $process_open_query = $process_open_query->select('process_manual.*','process_doc.file as url');
    // assign jobs to logged in user
    if($all==0){
      $process_open_query = $process_open_query->join('process_visible_users','process_visible_users.process_id','=','process_manual.id');
      $process_open_query = $process_open_query->where('user_id','=',$user_id);
      if (isset($search) && $search != '') {
        $process_open_query = $process_open_query->where(function($process_open_query) use ($search){
          $process_open_query = $process_open_query->where('process_manual.title','like',"%$search%");
        });
      }
    }
    else {
      if (isset($search) && $search != '') {
        $process_open_query = $process_open_query->where(function($process_open_query) use ($search){
          $process_open_query = $process_open_query->where('process_manual.title','like',"%$search%");
        });
      }
    }
    $process_open_query = $process_open_query->leftjoin('process_doc','process_doc.process_id','=','process_manual.id');
    $process_open_query = $process_open_query->groupBy('process_manual.id');
    $process_response = $process_open_query->get();
    $process_count = sizeof($process_response);

    return $process_count;
  }

    public static function getAllprocessmanualIds($select_all=0){

        $query = ProcessManual::query();
        $query = $query->select('process_manual.id');
        if ($select_all > 0) {
            $query = $query->where('process_manual.select_all',$select_all);
        }
        $res = $query->get();

        $process_manual_id = array();
        $i = 0;
        if (isset($res) && $res != '') {
            foreach ($res as $key => $value) {
                $process_manual_id[$i] = $value->id;
                $i++;
            }
        }

        return $process_manual_id;
    }
}
