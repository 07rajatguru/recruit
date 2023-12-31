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

                @if(isset($module) && $module == 'Work Planning')
                    <p>Login time: {{ $loggedin_time }}  | WP time: {{ $work_planning_time }}  | Logout Time: {{ $loggedout_time }}  | WP Status Time : {{ $work_planning_status_time }}  |  <a style="color: blue;" formtarget="_blank" href="{{getenv('APP_URL').'/work-planning/'.$module_id.'/show'}}">Approve / Reject</a></p>
                @endif

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
                    ?>
                    @foreach($work_planning_list as $key => $value)
                        <tr style="font-family:Cambria, serif;font-size: 11.0pt;">
                                
                            @if($i==0)
                                <td align="center">{{ $today_date }}</td>
                            @else
                                <td></td>
                            @endif

                            <td align="center">{{ ++$i }}</td>              
                            <td align="left">{!! $value['task_name'] !!}</td>

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
                    @endforeach

                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>

                        @if(isset($total_projected_time) && $total_projected_time != '')
                            <td align="center"><b>{{ $total_projected_time }} Hours</b></td>
                        @else
                            <td></td>
                        @endif

                        @if(isset($total_actual_time) && $total_actual_time != '')
                            <td align="center"><b>{{ $total_actual_time }} Hours</b></td>
                        @else
                            <td></td>
                        @endif

                        <td></td>
                        <td></td>
                    </tr>
                </table>

                @if(isset($link) && $link != '')
                    <p style="font-family:Cambria, serif;font-size: 11.0pt;text-align: left;">
                        Link : {{ $link }}
                    </p>
                @endif

                @if(isset($work_planning_post) && $work_planning_post != '')
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="box box-warning col-xs-12 col-sm-12 col-md-12">
                            <div class="box-header with-border col-md-6"><h4>Comments</h4></div>
                            <table width="800" cellpadding="3" cellspacing="0">
                                @foreach($work_planning_post as $key => $value)
                                    <tr style="font-family:Cambria, serif;font-size: 11.0pt;">
                                        <?php
                                            $data = App\UsersDoc::getUserDocInfoByIDType($value['user_id'],'Photo');
                                        ?>
                                            
                                        @if(isset($data['file']) && $data['file'] != '')
                                            <td align="left">
                                                <img class="profile-avatar-pic" src="{{$app_url}}/{{ $data['file'] }}" alt="Adler Talent Solutions Pvt. Ltd." style="vertical-align: top;width: 40px;height: 40px;border-radius: 100%;overflow: hidden;">
                                                <span style="margin-left:10px;">
                                                {{ $value['added_by'] }}</span><br/>
                                                <span style="margin-left:52px;">
                                                {{ $value['content'] }}</span><br/>
                                                <span style="margin-left:52px;">
                                                {{ $value['post_date'] }}</span><br/><br/>
                                            </td>
                                        @else
                                            <td align="left">
                                                <img class="profile-avatar-pic" src="{{$app_url}}/images/default.png" alt="Adler Talent Solutions Pvt. Ltd." style="vertical-align: top;width: 40px;height: 40px;border-radius: 100%;overflow: hidden;">
                                                <span style="margin-left:10px;">
                                                {{ $value['added_by'] }}</span><br/>
                                                <span style="margin-left:52px;">
                                                {{ $value['content'] }}</span><br/>
                                                <span style="margin-left:52px;">
                                                {{ $value['post_date'] }}</span><br/><br/>
                                            </td>
                                        @endif
                                    </tr>
                                @endforeach
                            </table>  
                        </div>
                    </div>
                @endif

                <p style="font-family:Cambria, serif;font-size: 11.0pt;text-align: left;">Thanks.</p>
            </td>
        </tr>
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