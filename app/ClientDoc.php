<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ClientDoc extends Model
{
    public $table = "client_doc";


    public static function recursiveRemoveDirectory($directory)
    {
        foreach(glob("{$directory}/*") as $file)
        {
            if(is_dir($file)) {
                recursiveRemoveDirectory($file);
            } else {
                unlink($file);
            }
        }
        rmdir($directory);
    }
}
