<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Date 
{
    public function changeDMYtoYMD($date){
		return date('Y-m-d', strtotime($date));
    }

    public function changeYMDtoDMY($date){
    	if($date!=''){
			return date('d-m-Y', strtotime($date));
    	}
		return $date;
    }
    public function changeDMYHMStoYMDHMS($date){
		return date('Y-m-d H:i:s', strtotime($date));
    }

    public function changeYMDHMStoDMYHMS($date){
    	if($date!=''){
			return date('d-m-Y H:i:s', strtotime($date));
    	}
		return $date;
    }
    public function getCurrentTimeZone(){
        return date_default_timezone_get();
    }
    public function converttime($time){
        if(date_default_timezone_get()=="UTC" || date_default_timezone_get()=='GMT') {
            $local_time = strtotime($time .'330 minutes');
            return $local_time;
        }
        return strtotime($time);
    }
}
