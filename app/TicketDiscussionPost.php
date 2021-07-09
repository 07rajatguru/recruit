<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Commentable\HasCommentsTrait;

class TicketDiscussionPost extends Model
{
    public $table = "ticket_discussion_post";

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
        return $this->belongsTo('App\TicketsDiscussion');
    }

    public function user() {
        return $this->belongsTo('App\User');
    }

    public static function deletePost($id) {
        return static::find($id)->delete();
    }

    public static function updatePost($id,$data) {
        $response = static::find($id)->update(['content' => $data]);
        return $response;
    }

    public static function getTicketPostDetailsById($post_id) {

        $query = TicketDiscussionPost::query();
        $query = $query->leftjoin('users','users.id','=','ticket_discussion_post.user_id');
        $query = $query->select('ticket_discussion_post.*','users.name as added_by');
        $query = $query->where('ticket_discussion_post.id','=',$post_id);
        $response = $query->first();

        $ticket_post_res = array();

        $ticket_post_res['id'] = $response->id;
        $ticket_post_res['content'] = $response->content;
        $ticket_post_res['added_by'] = $response->added_by;
        $ticket_post_res['tickets_discussion_id'] = $response->tickets_discussion_id;
    
        return $ticket_post_res;
    }
}