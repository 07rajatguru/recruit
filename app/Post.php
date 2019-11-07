<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Commentable\HasCommentsTrait;

class Post extends Model
{
    public $table = "post";

 	/**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'content'
    ];

    use HasCommentsTrait;

	public function post() {
        return $this->belongsTo('App\ClientBasicinfo');
    }

    public function user() {
        return $this->belongsTo('App\User');
    }

    public static function deletePost($id)
    {
        return static::find($id)->delete();
    }

    public static function updatePost($id,$data)
    {
        $response = static::find($id)->update(['content' => $data]);
        return $response;
    }

    public static function getClientLatestRemarks($id)
    {
        $query = Post::query();
        $query = $query->leftjoin('client_basicinfo', 'client_basicinfo.id', '=', 'post.client_id');
        $query = $query->where('post.client_id','=',$id);
        $query = $query->orderBy('post.updated_at','DESC');
        $query = $query->select('post.content as content','post.user_id as user_id','post.updated_at as updated_date');
        $response = $query->first();

        return $response;
    }
}
