<?php

namespace App;

use LaravelArdent\Ardent\Ardent;

class Team extends Ardent
{
    public $table = "team";

    protected $fillable = [
        'team_name'
    ];

    public static $rules
        = array(
            'team_name' => 'required|unique:team,team_name,{id}',
        );

    public function messages()
    {
        return [
            'team_name.required' => 'Name is required field',
            'team_name.unique' => 'Name is unique field',
        ];
    }

    public function beforeValidate ()
    {
        if (isset ($this->id) && $this->id > 0)
        {
            Team::$rules['team_name'] = str_replace ('{id}', $this->id, Team::$rules['team_name']);
        }
        else
        {
            Team::$rules['team_name'] = str_replace ('{id}', "NULL", Team::$rules['team_name']);
        }

        return true;
    }

}
