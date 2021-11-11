<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <title>Adler Talent</title>
        @yield('style')
    </head>

<body style="margin: 0; padding-top: 30px;">
    <table border="0" cellpadding="0" cellspacing="0" width="800" style="font-family:Helvetica,Arial,sans-serif;" align="left">
        <tr>
            <td width="800" style="font-family:Cambria, serif;font-size: 11.0pt;">
                <b><p style="text-align: left;">Dear Sir/Madam,</p></b>
                <i><p style="text-align: left;">Greetings !</p></i>

                @if(isset($report_delay) && $report_delay != '')
                    <p style="text-align: left;">
                        @if(isset($report_delay_content) && $report_delay_content != '')
                            <b>Reason for Delay Report</b> : {{ $report_delay_content }}
                        @else
                            <b>Reason for Delay Report</b> : {{ $report_delay }}
                        @endif  
                    </p>
                @endif

                <p style="text-align: left;">
                    <u>Please find my Work Planning Sheet for the day :</u>
                </p>
                    
                <table width="800" cellpadding="3" cellspacing="0" border="1" border-color="#000000">
                    <tr style="background-color: #ffff00;font-family:Cambria, serif;font-size: 11.0pt;">
                        <td align="center" width="13%"><b>Date</b></td>
                        <td align="center" width="7%"><b>Sr. No.</b></td>
                        <td align="center" width="20%"><b>Task / Positions</b></td>
                        <td align="center" width="10%"><b>Projected Time</b></td>
                        <td align="center" width="10%"><b>Actual Time</b></td>
                        <td align="center" width="20%"><b>Remarks</b></td>
                        <td align="center" width="20%"><b>Reporting Manager / HR Remarks</b>
                        </td>
                    </tr>

                    <?php

                        $i=0; 
                        $display_total_projected_time = '';
                        $display_total_actual_time = '';

                        $p_0 = '';$p_1 = '';$p_2 = '';
                        $a_0 = '';$a_1 = '';$a_2 = '';
                    ?>
                    @foreach($work_planning_list as $key => $value)
                        <tr style="font-family:Cambria, serif;font-size: 11.0pt;">
                                
                            @if($i==0)
                                <td align="center">{{ $today_date }}</td>
                            @else
                                <td></td>
                            @endif

                            <td align="center">{{ ++$i }}</td>              
                            <td align="left">{!! $value['task'] !!}</td>

                            <?php

                                $projected_time = array();
                                $actual_time = array();

                                if(isset($value['projected_time']) && $value['projected_time'] != '') {
                                    $projected_time = explode(':', $value['projected_time']);
                                }

                                if(isset($value['actual_time']) && $value['actual_time'] != ''){
                                    $actual_time = explode(':', $value['actual_time']);
                                }
                            ?>

                            @if(isset($projected_time)  && sizeof($projected_time) > 0)
                                @if($projected_time[0] == 0)
                                    <td align="center">{{ $projected_time[1] }} Min.</td>
                                @else
                                    <td align="center">{{ $projected_time[0] }}:{{ $projected_time[1] }} Hours</td>
                                @endif
                            @else
                                <td align="center">{{ $value['projected_time'] }}</td>
                            @endif

                            @if(isset($actual_time) && sizeof($actual_time) > 0)
                                @if($actual_time[0] == 0)
                                    <td align="center">{{ $actual_time[1] }} Min.</td>
                                @else
                                    <td align="center">{{ $actual_time[0] }}:{{ $actual_time[1] }} Hours</td>
                                @endif
                            @else
                                <td align="center">{{ $value['actual_time'] }}</td>
                            @endif

                            <td align="left">{!! $value['remarks'] !!}</td>
                            <td align="left">{!! $value['rm_hr_remarks'] !!}</td>
                        </tr>

                        <?php

                            // Set Projected Time
                            if($p_0 == '') {
                                $p_0 = $projected_time[0];
                            }
                            else {
                                $p_0 = $p_0 + $projected_time[0];
                            }

                            if($p_1 == '') {
                                $p_1 = $projected_time[1];
                            }
                            else {
                                $p_1 = $p_1 + $projected_time[1];
                            }

                            if($p_2 == '') {
                                $p_2 = $projected_time[2];
                            }
                            else {
                                $p_2 = $p_2 + $projected_time[2];
                            }

                            if(strlen($p_0) == 1) {
                                $p_0 = '0'.$p_0;
                            }
                            if(strlen($p_1) == 1) {
                                $p_1 = '0'.$p_1;
                            }
                            if(strlen($p_2) == 1) {
                                $p_2 = '0'.$p_2;
                            }

                            $display_total_projected_time = $p_0.":".$p_1.":".$p_2;

                            // Set Actual Time

                            if(isset($value['actual_time']) && $value['actual_time'] != '') {

                                if($a_0 == '') {
                                    $a_0 = $actual_time[0];
                                }
                                else {
                                    $a_0 = $a_0 + $actual_time[0];
                                }

                                if($a_1 == '') {
                                    $a_1 = $actual_time[1];
                                }
                                else {
                                    $a_1 = $a_1 + $actual_time[1];
                                }

                                if($a_2 == '') {
                                    $a_2 = $actual_time[2];
                                }
                                else {
                                    $a_2 = $a_2 + $actual_time[2];
                                }

                                if(strlen($a_0) == 1) {
                                    $a_0 = '0'.$a_0;
                                }
                                if(strlen($a_1) == 1) {
                                    $a_1 = '0'.$a_1;
                                }
                                if(strlen($a_2) == 1) {
                                    $a_2 = '0'.$a_2;
                                }

                                $display_total_actual_time = $a_0.":".$a_1.":".$a_2;
                            }
                        ?>
                    @endforeach
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td align="center"><b>{{ $display_total_projected_time }} Hours</b></td>
                        <td align="center"><b>{{ $display_total_actual_time }} Hours</b></td>
                        <td></td>
                        <td></td>
                    </tr>
                </table>

                @if(isset($link) && $link != '')
                    <p style="font-family:Cambria, serif;font-size: 11.0pt;text-align: left;">
                        Link : {{ $link }}
                    </p>
                @endif

                <p style="font-family:Cambria, serif;font-size: 11.0pt;text-align: left;">Thanks.</p>
            </td>
        </tr>
        @if(isset($module) && $module == 'Work Planning')
            <tr>
                <td width="800">
                    <table width="100%" cellpadding="0" cellspacing="0" style="border:0; background-color: #ffffff; padding: 0px 50px 54px;">
                        <tr>
                            <td align="center" style="padding: 0px;">
                                <a style="border: black; background-color: skyblue;color: white;padding: 10px 20px 10px 20px; border-radius: 50px;font-size: 15px;width: 59%;text-decoration: none;" class="btn btn-primary" formtarget="_blank" href="{{getenv('APP_URL').'/work-planning/'.$module_id.'/show'}}">Approved / Rejected 
                                </a>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        @endif
        <tr>
            <td width="800">
                <table width="100%" cellpadding="0" cellspacing="0" style="border:0;">
                    <tr>
                        <td>{!! $signature !!}</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>