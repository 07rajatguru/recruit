<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ClientHeirarchy extends Model
{
    public function index(){

        $modules = Module::getAllModules();

        return view('adminlte::module.index',compact('modules'));
    }


}
