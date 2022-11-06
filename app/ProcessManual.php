<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Department;

class ProcessManual extends Model
{
    public $table = "process_manual";

    public static function getAllprocess($all=0,$user_id,$limit=0,$offset=0,$search=0,$order=0,$type='asc') {
  
    $process_open_query = ProcessManual::query();
    $process_open_query = $process_open_query->select('process_manual.*','process_doc.file as url');
    // assign jobs to logged in user
    if($all==0) {

      $process_open_query = $process_open_query->join('process_visible_users','process_visible_users.process_id','=','process_manual.id');
      $process_open_query = $process_open_query->where('user_id','=',$user_id);

      if (isset($search) && $search != '') {

          $process_open_query = $process_open_query->where(function($process_open_query) use ($search) {
            $process_open_query = $process_open_query->where('process_manual.title','like',"%$search%");
          });
      }
    }
    else {

      if (isset($search) && $search != '') {

          $process_open_query = $process_open_query->where(function($process_open_query) use ($search) {
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

    foreach ($process_response as $key=>$value) {

        $process_list[$i]['id'] = $value->id;
        $process_list[$i]['title'] = $value->title;
        // $process_list[$i]['url'] = $value->url;
        $dep_ids = explode(",", $value->department_id);$d_name = '';

        if (isset($dep_ids) && sizeof($dep_ids) == 4) {
            $process_list[$i]['department'] = 'All';
        }
            
        else if (isset($dep_ids) && sizeof($dep_ids)>0) {
          foreach ($dep_ids as $kd => $vd) {
            if (isset($d_name) && $d_name != '') {
              $d_name .= ", " . Department::getDepartmentNameById($vd);
            } else { 
              $d_name .= Department::getDepartmentNameById($vd);
            }
          }
          $process_list[$i]['department'] = $d_name;
        }
        
        // Admin/super admin have access to all details
        if($all==1) {
            $process_list[$i]['access'] = '1';
        }
        else {

          if (isset($value->owner_id) && $value->owner_id == $user_id) {
              $process_list[$i]['access'] = '1';
          }
          else {
              $process_list[$i]['access'] = '0';
          }
        }

        $doc_count = ProcessManual::getProcessManualsDocCount($value['id']);
        if (isset($doc_count) && $doc_count == 1) {
            $process_list[$i]['show_doc'] = 'Y';
            $process_list[$i]['file_url'] = $value->url;
        } else {
            $process_list[$i]['show_doc'] = 'N';
        }
        $i++;
    }
    return $process_list;
  }

  public static function getProcessManualsDocCount($process_id) {

      $query = ProcessDoc::query();
      $query = $query->where('process_id',$process_id);
      $response = $query->count();

      return $response;
  }

  public static function getAllprocessCount($all=0,$user_id,$search=0) {
  
    $process_open_query = ProcessManual::query();
    $process_open_query = $process_open_query->select('process_manual.*','process_doc.file as url');
    // assign jobs to logged in user
    if($all==0) {

      $process_open_query = $process_open_query->join('process_visible_users','process_visible_users.process_id','=','process_manual.id');
      $process_open_query = $process_open_query->where('user_id','=',$user_id);

      if (isset($search) && $search != '') {

          $process_open_query = $process_open_query->where(function($process_open_query) use ($search) {
            $process_open_query = $process_open_query->where('process_manual.title','like',"%$search%");
          });
      }
    }

    else {

      if (isset($search) && $search != '') {

          $process_open_query = $process_open_query->where(function($process_open_query) use ($search) {
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

  public static function getAllprocessmanualIds($select_all=0,$is_default_open=0) {

    $query = ProcessManual::query();
    $query = $query->select('process_manual.id');
    
    if ($select_all > 0) {
      $query = $query->where('process_manual.select_all',$select_all);
    }
    if ($is_default_open == 1) {
      $query = $query->where('process_manual.is_default_open','1');
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

  public static function getTodaysProcessManual($start,$end) {

    $query = ProcessManual::query();
    $query = $query->select('process_manual.id','process_manual.title');
    $query = $query->whereBetween('process_manual.created_at', [$start, $end]);
    $res = $query->get();

    $process = array(); $i = 0;
    if (isset($res) && $res != '') {
        foreach ($res as $key => $value) {
            $process[$i]['id'] = $value->id;
            $process[$i]['title'] = $value->title;
            $i++;
        }
    }
    return $process;
  }

  public static function getProcessManualByProcessId($process_id) {
      
    $query = ProcessManual::query();
    $query = $query->select('process_manual.id','process_manual.title');
    $query = $query->where('process_manual.id', $process_id);
    $res = $query->first();

    $process_manual = array();
    if (isset($res) && $res != '') {
        $process_manual['id'] = $res->id;
        $process_manual['title'] = $res->title;
    }
    return $process_manual;
  }
}