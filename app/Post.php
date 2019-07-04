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


}
