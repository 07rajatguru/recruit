<?php

namespace App;
use DateTime;
use DateInterval;
use Illuminate\Database\Eloquent\Model;

class Date 
{
    public function changeDMYtoYMD($date) {
		return date('Y-m-d', strtotime($date));
    }

    public function changeYMDtoDMY($date) {
    	if($date!=''){
			return date('d-m-Y', strtotime($date));
    	}
		return $date;
    }

    public function changeDMYHMStoYMDHMS($date) {
		return date('Y-m-d H:i:s', strtotime($date));
    }

    public function changeYMDHMStoDMYHMS($date) {
    	if($date!=''){
			return date('d-m-Y H:i:s', strtotime($date));
    	}
		return $date;
    }

    public function getCurrentTimeZone() {
        return date_default_timezone_get();
    }

    public static function converttime($time) {
        if(date_default_timezone_get()=="UTC" || date_default_timezone_get()=='GMT') {
            $local_time = strtotime($time .'330 minutes');
            return $local_time;
        }
        return strtotime($time);
    }

    public static function getWeeksInMonth($year, $month, $lastDayOfWeek) {

        $aWeeksOfMonth = [];
        $date = new DateTime("{$year}-{$month}-01");
        $iDaysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);
        $aOneWeek = [$date->format('Y-m-d')];
        $weekNumber = 1;

        for ($i = 1; $i <= $iDaysInMonth; $i++) {

            if ($lastDayOfWeek == $date->format('N') || $i == $iDaysInMonth) {

                $aOneWeek[] = $date->format('Y-m-d');
                $aWeeksOfMonth[$weekNumber++] = $aOneWeek;
                $date->add(new DateInterval('P1D'));
                $aOneWeek = [$date->format('Y-m-d')];
                $i++;
            }
            $date->add(new DateInterval('P1D'));
        }
        return $aWeeksOfMonth;
    }
}