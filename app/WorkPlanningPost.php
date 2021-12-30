<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Date;

class WorkPlanningPost extends Model
{
    public $table = "work_planning_post";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'content'
    ];

    public function post() {
        return $this->belongsTo('App\WorkPlanning');
    }

    public function user() {
        return $this->belongsTo('App\User');
    }

    public static function updatePost($id,$data) {
        $response = static::find($id)->update(['content' => $data]);
        return $response;
    }

    public static function deletePost($id) {
        return static::find($id)->delete();
    }

    public static function getWorkPlanningPostList($work_planning_id) {

        $query = WorkPlanningPost::query();
        $query = $query->leftjoin('users','users.id','=','work_planning_post.user_id');
        $query = $query->where('work_planning_post.wp_id',$work_planning_id);
        $query = $query->select('work_planning_post.*','users.name as u_name');
        $response = $query->get();

        $work_planning_post_list = array();
        $i = 0;

        if(isset($response) && $response != '') {

            foreach ($response as $key => $value) {

                $work_planning_post_list[$i]['work_planning_post_id'] = $value->id;
                $work_planning_post_list[$i]['user_id'] = $value->user_id;
                $work_planning_post_list[$i]['added_by'] = $value->u_name;
                $work_planning_post_list[$i]['work_planning_id'] = $work_planning_id;
                $work_planning_post_list[$i]['content'] = $value->content;

                $post_time = explode(" ", $value->updated_at);
                $time = Date::converttime($post_time[1]);
                $post_date = date('d-m-Y' ,strtotime($value->updated_at)) . ' at '. date('h:i A' ,$time);

                $work_planning_post_list[$i]['post_date'] = $post_date;

                $i++;
            }
        }
        return $work_planning_post_list;
    }
}