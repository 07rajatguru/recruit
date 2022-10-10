<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <title>Adler Talent</title>

        @yield('style')
    </head>

    <body style="margin: 0; padding-top: 30px; background-color: #f5f5f5;">
        <table align="center" width="700px" cellpadding="0" cellspacing="0" style="font-family: arial; font-size: 14px; color: #444444;">
            <tr>
                <td>
                    <table width="100%" cellpadding="0" cellspacing="0"  border="1" style="background-color: #ffffff; padding: 40px 44px;">
                        <tr>
                            <th align="center">Employee Name</th>
                            <th align="center">In-time</th>
                            <th align="center">Out-time</th>
                            <th align="center">WP Delay</th>
                            <th align="center">WP Time</th>
                            <th align="center">WP Status Time</th>
                        </tr>
                        @if(isset($data) && sizeof($data)>0)
                            @foreach($data as $kd => $vd)
                                <tr>
                                    @if(isset($vd) && sizeof($vd['today_work_planning']))
                                        @foreach($vd['today_work_planning'] as $kw => $vw)
                                            <td align="center"> {{ $kd }} </td>
                                            <td align="center"> {{ $vw['loggedin_time'] }} </td>
                                            <td align="center"> {{ $vw['loggedout_time'] }} </td>
                                            <td align="center"> {{ $vw['wp_delay'] }} </td>
                                            <td align="center"> {{ $vw['work_planning_time'] }} </td>
                                            <td align="center"> {{ $vw['work_planning_status_time'] }} </td>
                                        @endforeach
                                    @endif
                                </tr>
                            @endforeach
                        @endif
                    </table>
                </td>
            </tr>
        </table>
    </body>
</html>