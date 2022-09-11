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


        if(sizeof($aOneWeek) == 1) {

            $aOneWeek[] = $date->format('Y-m-d');
            $aWeeksOfMonth[$weekNumber++] = $aOneWeek;
            $date->add(new DateInterval('P1D'));
            $aOneWeek = [$date->format('Y-m-d')];
        }

        if(isset($aWeeksOfMonth) && sizeof($aWeeksOfMonth) > 0) {

            $new_array = array();
            $j=0;

            foreach ($aWeeksOfMonth as $key => $value) {
                
                foreach ($value as $k1 => $v1) {

                    $new_array[$j] = $value[0] . "--" . $value[1];
                    $j++;
                }
            }
        }

        $new_array = array_unique($new_array);

        if(isset($new_array) && sizeof($new_array) > 0) {

            $final_array = array();
            $kk=0;

            foreach ($new_array as $key => $value) {

                $frm_to_array = explode("--", $value);

                $m0 = date('m',strtotime($frm_to_array[0]));
                $m1 = date('m',strtotime($frm_to_array[1]));

                if($m1 == $month) {

                    $final_array[$kk]['from_date'] = $frm_to_array[0];
                    $final_array[$kk]['to_date'] = $frm_to_array[1];
                }
                else if ($m0 == $month) {

                    $final_array[$kk]['from_date'] = $frm_to_array[0];
                    $final_array[$kk]['to_date'] = $frm_to_array[0];
                }
                $kk++;
            }
        }
        return $final_array;
    }
    
    public static function getTotalMonthDays($month) {
        $month_n = date('m', strtotime($month));
        $year = date('Y', strtotime($month));

        $month_count = cal_days_in_month(CAL_GREGORIAN,$month_n,$year);
        
        return $month_count;
    }
}