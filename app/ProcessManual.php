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
        $process_open_query = $process_open_query->select('process_manual.*');
        //echo '<pre>';print_r($process_open_query);exit;
        // assign jobs to logged in user
        if($all==0){
            $process_open_query = $process_open_query->join('process_visible_users','process_visible_users.process_id','=','process_manual.id');
            $process_open_query = $process_open_query->where('user_id','=',$user_id);
        }
                
        $process_response = $process_open_query->get();

        $process_list = array();
        
        $i = 0;
        foreach ($process_response as $key=>$value){
            $process_list[$i]['id'] = $value->id;
            $process_list[$i]['title'] = $value->title;
           

            // Admin/super admin have access to all details
            if($all==1){
                $process_list[$i]['access'] = '1';
            }
            else{
                $process_list[$i]['access'] = '0';
            }

            $i++;
        }

       // echo '<pre>';print_r($process_list);exit;
        return $process_list;
    }
}
