<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <title>Adler Talent</title>
        @yield('style')
    </head>

<body style="margin: 0; padding-top: 30px;">
    <table border="0" cellpadding="0" cellspacing="0" width="600" style="font-family:Helvetica,Arial,sans-serif;" align="left">
        <tr>
            <td width="600" style="font-family:Cambria, serif;font-size: 11.0pt;">

                <b><p style="text-align: left;">Dear {{ $user_name }},</p></b>

                <p style="text-align: left;">Greetings from Easy2Hire!</p>

                <p style="text-align: left;">Please update the following Work Planning Status of your Team</p>

                <table width="800" cellpadding="3" cellspacing="0" border="1" border-color="#000000">

                    <tr style="background-color: #7598d9;font-family:Cambria, serif;font-size: 11.0pt;">
                        <td align="center" width="7%"><b>Sr. No.</b></td>
                        <td align="center" width="11%"><b>Date</b></td>
                        <td align="center" width="17%"><b>Username</b></td>
                        <td align="center" width="10%"><b>Work Location</b></td>
                        <td align="center" width="10%"><b>Logged-in Time</b></td>
                        <td align="center" width="10%"><b>Logged-out Time</b></td>
                        <td align="center" width="14%"><b>Work Planning Time</b></td>
                        <td align="center" width="14%"><b>Status Time</b></td>
                        <td align="center" width="7%"><b>Link</b></td>
                    </tr>

                    <?php
                        $i=0;
                    ?>
                    @foreach($work_planning_res as $key => $value)
                        <tr style="font-family:Cambria, serif;font-size: 11.0pt;">

                            <td align="center">{{ ++$i }}</td>
                            <td align="left">{!! $value['added_date'] !!}</td>
                            <td align="left">{!! $value['added_by'] !!}</td>
                            <td align="left">{!! $value['work_type'] !!}</td>

                            <td align="left">{!! $value['loggedin_time'] !!}</td>
                            <td align="left">{!! $value['loggedout_time'] !!}</td>

                            <td align="left">{!! $value['added_date'] !!} - {!! $value['work_planning_time'] !!}</td>
                            <td align="left">{!! $value['status_date'] !!} - {!! $value['work_planning_status_time'] !!}</td>

                            <td align="left">
                                <a style="border-radius: 18px;" class="btn btn-primary" formtarget="_blank" href="{{getenv('APP_URL').'/work-planning/'.$value['id'].'/show'}}">Show</a>
                            </td>
                        </tr>
                    @endforeach
                </table>
                
                <p style="text-align: left;">Thanks.<br/>Easy2Hire Team</p>
            </td>
        </tr>
    </table>
</body>
</html>