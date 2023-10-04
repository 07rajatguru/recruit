
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <title>Adler Talent</title>
        @yield('style')
        <style type="text/css">
            table{
                border: 1px solid black;
                border-collapse: collapse;
            }
        </style>
    </head>

    <body>
        <p style="font-family:Cambria, serif;font-size: 11.0pt;">Dear {{ $rm_name }},</p>
        <p style="font-family:Cambria, serif;font-size: 11.0pt;">Please find the Work Planning Report as below:</p>

        <table width="900" cellpadding="3" cellspacing="0" border="1" border-color="#000000">
            <thead>
                <tr>
                    <th width="7%" style="background-color: none;font-size: 9.0pt; ">Sr No</th>
                    <th width="15%" style="background-color: none;font-size: 9.0pt;">Employee Name</th>
                    <th width="15%" style="background-color: none;font-size: 9.0pt;">Log-In Time</th>
                    <th width="15%" style="background-color: none;font-size: 9.0pt;">Work Planning Time</th>
                    <th width="15%" style="background-color: none;font-size: 9.0pt;">Log-Out Time</th>
                    <th width="15%" style="background-color: none;font-size: 9.0pt;">Work Planning Actual Time</th>
                    <th width="15%" style="background-color: none;font-size: 9.0pt;">Work Planning Status Time</th>
                    <th width="15%" style="background-color: none;font-size: 9.0pt;">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php $i=0; ?>
                @if(isset($data) && sizeof($data)>0)
                    @foreach($data as $kd => $vd)
                        <tr>
                            <td align="center">{{ ++$i }}</td>
                            <td align="center">{{ $kd }}</td>
                            <td align="center" style="background-color: {{ $vd['login_bg_color']  }}">{{ $vd['loggedin_time'] }}</td>
                            <td align="center" style="background-color: {{ $vd['work_planning_time_bg_color']  }}">{{ $vd['work_planning_time'] }}</td>
                            <td align="center" style="background-color: {{ $vd['logout_bg_color']  }}">{{ $vd['loggedout_time'] }}</td>
                            <td align="center" style="background-color: {{ $vd['work_planning_actual_time_bg_color']  }}">{{ $vd['total_actual_time'] }}</td>
                            <td align="center" style="background-color: {{ $vd['work_planning_status_time_bg_color']  }}">{{ $vd['work_planning_status_time'] }}</td>         
                            <td align="center"><a style="color: blue;" formtarget="_blank" href="{{getenv('APP_URL').'/work-planning/'.$vd['module_id'].'/show'}}">Approve / Reject</a></td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>

        <p style="font-family:Cambria, serif;font-size: 11.0pt;">Request you to connect with your Team Members and plan the upcoming day's priorities accordingly.</p>
   
        <table style="width: 50%; border-collapse: collapse;">
            <tr>
                <td style="border: 1px solid black; background-color: #93ccea; color: #93ccea;"> 00</td>
                <td style="border: 1px solid black;">&nbsp;PL / LWP</td>
            </tr>
            <tr>
                <td style="border: 1px solid black; background-color: #c5a3ff; color: #c5a3ff;"> 00</td>
                <td style="border: 1px solid black;">&nbsp;HD</td>
            </tr>
            <tr>
                <td style="border: 1px solid black; background-color: lightpink; color: lightpink;"> 00</td>
                <td style="border: 1px solid black;">&nbsp;Log-In Post 10:30</td>
            </tr>
            <tr>
                <td style="border: 1px solid black; background-color: #d99594; color: #d99594;"> 00</td>
                <td style="border: 1px solid black;">&nbsp;Log-out Time < 9 hours</td>
            </tr>
            <tr>
                <td style="border: 1px solid black; background-color: #FFBB70; color: #FFBB70;"> 00</td>
                <td style="border: 1px solid black;">&nbsp;Work Planning Delayed / Status Not Updated</td>
            </tr>    
            <tr>
                <td style="border: 1px solid black; background-color: #98FB98; color: #98FB98;"> 00</td>
                <td style="border: 1px solid black;">&nbsp;More than 8 hours</td>
            </tr>
            <tr>
                <td style="border: 1px solid black; background-color: #fd5e53; color: #fd5e53;"> 00</td>
                <td style="border: 1px solid black;">&nbsp;Shortfall of hours</td>
            </tr>
        </table>
          
        <p style="font-family:Cambria, serif;font-size: 11.0pt;">Thanks.</p>
        <p style="font-family:Cambria, serif;font-size: 11.0pt;">E2H Team</p>
    </body>
</html>