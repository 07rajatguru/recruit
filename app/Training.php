<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Department;
use App\TrainingDoc;

class Training extends Model
{
    public $table = "training";

    public static function getAlltraining($all=0,$user_id,$limit=0,$offset=0,$search=0,$order=0,$type='asc') {
             
        $training_open_query = Training::query();
        $training_open_query = $training_open_query->select('training.*','training_doc.file as url');

        if ($all==0) {

            $training_open_query = $training_open_query->join('training_visible_users','training_visible_users.training_id','=','training.id');
            $training_open_query = $training_open_query->where('user_id','=',$user_id);

            if (isset($search) && $search != '') {

                $training_open_query = $training_open_query->where(function($training_open_query) use ($search) {
                    $training_open_query = $training_open_query->where('training.title','like',"%$search%");
                });
            }
        }
        else {

            if (isset($search) && $search != '') {

                $training_open_query = $training_open_query->where(function($training_open_query) use ($search) {
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

        $training_open_query = $training_open_query->orderBy('position','asc');
        $training_open_query = $training_open_query->groupBy('training.id');
        $training_response = $training_open_query->get();

        $training_list = array();
        $i = 0;

        foreach ($training_response as $key=>$value) {

            $training_list[$i]['id'] = $value->id;
            $training_list[$i]['title'] = $value->title;
        	$training_list[$i]['owner_id'] = $value->owner_id;
            //$training_list[$i]['department'] = Department::getDepartmentNameById($value->department_id);

            $dep_ids = explode(",", $value->department_id);
            $d_name = '';

            if (isset($dep_ids) && sizeof($dep_ids) == 4) {

                $training_list[$i]['department'] = 'All';
            }
            else if (isset($dep_ids) && sizeof($dep_ids) > 0) {

                foreach ($dep_ids as $kd => $vd) {
                    
                    if (isset($d_name) && $d_name != '') {
                        $d_name .= ", " . Department::getDepartmentNameById($vd);
                    }
                    else { 
                        $d_name .= Department::getDepartmentNameById($vd);
                    }
                }

                $training_list[$i]['department'] = $d_name;
            }
            
            if($all==1) {
                $training_list[$i]['access'] = '1';
            }
            else {
                if (isset($value->owner_id) && $value->owner_id == $user_id) {
                    $training_list[$i]['access'] = '1';
                }
                else {
                    $training_list[$i]['access'] = '0';
                }
            }

            $doc_count = TrainingDoc::getTrainingDocCount($value['id']);
            if (isset($doc_count) && $doc_count == 1) {

                $training_list[$i]['show_doc'] = 'Y';
                $training_list[$i]['file_url'] = $value->url;
            }
            else {
                $training_list[$i]['show_doc'] = 'N';
            }
            $i++;
        }
        return $training_list;
    }

    public static function getAlltrainingCount($all=0,$user_id,$search=0) {

        $training_open_query = Training::query();
        $training_open_query = $training_open_query->select('training.*','training_doc.file as url');

        if ($all==0) {

            $training_open_query = $training_open_query->join('training_visible_users','training_visible_users.training_id','=','training.id');
            $training_open_query = $training_open_query->where('user_id','=',$user_id);

            if (isset($search) && $search != '') {

                $training_open_query = $training_open_query->where(function($training_open_query) use ($search) {
                    $training_open_query = $training_open_query->where('training.title','like',"%$search%");
                });
            }
        }
        else {

            if (isset($search) && $search != '') {

                $training_open_query = $training_open_query->where(function($training_open_query) use ($search) {
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

    public static function getAlltrainingIds($select_all=0) {

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

    public static function getTodaysTrainingMaterial($start,$end) {
        
        $query = Training::query();
        $query = $query->select('training.id','training.title');
        $query = $query->whereBetween('training.created_at', [$start, $end]);
        $res = $query->get();

        $trainings = array(); $i = 0;
        if (isset($res) && $res != '') {
            foreach ($res as $key => $value) {
                $trainings[$i]['id'] = $value->id;
                $trainings[$i]['title'] = $value->title;
                $i++;
            }
        }
        return $trainings;
    }

    public static function getTrainingMaterialByTrainingid($training_id) {
        
        $query = Training::query();
        $query = $query->select('training.id','training.title');
        $query = $query->where('training.id', $training_id);
        $res = $query->first();

        $training = array();
        if (isset($res) && $res != '') {
            $training['id'] = $res->id;
            $training['title'] = $res->title;
        }
        return $training;
    }
}