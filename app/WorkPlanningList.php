<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WorkPlanningList extends Model
{
    public $table = "work_planning_list";

    public static function getWorkPlanningList($work_planning_id) {

        $query = WorkPlanningList::query();
        $query = $query->where('work_planning_list.work_planning_id',$work_planning_id);
        $query = $query->select('work_planning_list.*');
        $response = $query->get();

        $work_planning_list = array();
        $i = 0;

        foreach ($response as $k=>$v) {

            $work_planning_list[$i]['work_planning_list_id'] = $v->id;
            $work_planning_list[$i]['work_planning_id'] = $v->work_planning_id;
            $work_planning_list[$i]['task'] = $v->task;
            $work_planning_list[$i]['projected_time'] = $v->projected_time;
            $work_planning_list[$i]['actual_time'] = $v->actual_time;
            $work_planning_list[$i]['remarks'] = $v->remarks;
            $work_planning_list[$i]['rm_hr_remarks'] = $v->rm_hr_remarks;

            $i++;
        }
        return $work_planning_list;
    }

    public static function getTaskById($task_id) {

        $query = WorkPlanningList::query();
        $query = $query->where('work_planning_list.id',$task_id);
        $query = $query->select('work_planning_list.*');
        $response = $query->first();

        $work_planning_task = array();
        
        if(isset($response) && $response != '') {

            $work_planning_task['work_planning_id'] = $response->work_planning_id;
            $work_planning_task['task'] = $response->task;
            $work_planning_task['projected_time'] = $response->projected_time;
            $work_planning_task['actual_time'] = $response->actual_time;
            $work_planning_task['remarks'] = strip_tags($response->remarks);
            $work_planning_task['rm_hr_remarks'] = strip_tags($response->rm_hr_remarks);
        }

        return $work_planning_task;
    }
}