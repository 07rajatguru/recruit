<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProcessManual extends Model
{
   public $table = "process_manual";


  //  public static function getAllprocess($all=0,$user_id){

  //   	 $process_query = ProcessManual::query();
		// // assign Process to logged in user
  //       if($all==0){
  //           $process_query = $process_query->join('process_visible_users','process_visible_users.process_id','=','process_manual.id');
  //           $process_query = $process_query->where('user_id','=',$user_id);
  //       }

  //   }

    public static function getAllprocess($all=0,$user_id){

             
        $process_open_query = ProcessManual::query();
        $process_open_query = $process_open_query->select('process_manual.*','process_doc.file as url');
        //echo '<pre>';print_r($process_open_query);exit;
        // assign jobs to logged in user
        if($all==0){
            $process_open_query = $process_open_query->join('process_visible_users','process_visible_users.process_id','=','process_manual.id');
            $process_open_query = $process_open_query->where('user_id','=',$user_id);
        }
        $process_open_query = $process_open_query->leftjoin('process_doc','process_doc.process_id','=','process_manual.id');
        $process_open_query = $process_open_query->orderBy('process_manual.id','asc');
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

       // echo '<pre>';print_r($process_list);exit;
        return $process_list;
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
