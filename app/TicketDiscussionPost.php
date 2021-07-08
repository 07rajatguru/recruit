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
}