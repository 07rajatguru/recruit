<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

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

	public function instates() {
        return $this->belongsTo('App\ClientBasicinfo');
    }

    public function user() {
        return $this->belongsTo('App\User');
    }


}
