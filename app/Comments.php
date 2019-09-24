<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comments extends Model
{
    public $table = "comments";

    protected $fillable = [
        'body'
    ];

    public static function updateComment($id,$data)
    {
        // $res = Comments::find($id)->update(['body' => $content,'title' => $content]);
        $response = static::find($id)->update(['body' => $data]);
        return $response;
    }

    public static function deleteComment($id)
    {
        return static::find($id)->delete();
    }

    public static function getClientLatestComments($id)
    {
        $query = Comments::query();
        
        $query = $query->leftjoin('post','post.id','=','comments.commentable_id');
        $query = $query->leftjoin('client_basicinfo', 'client_basicinfo.id', '=', 'post.client_id');
        $query = $query->where('client_basicinfo.id','=',$id);
        $query = $query->orderBy('comments.updated_at','DESC');
        $query = $query->select('comments.body as comment_body','comments.updated_at as comments_updated_date');
        $response = $query->first();

        return $response;
    }
}
