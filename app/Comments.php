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

}
