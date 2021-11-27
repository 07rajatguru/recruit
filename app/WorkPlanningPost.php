<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

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
}