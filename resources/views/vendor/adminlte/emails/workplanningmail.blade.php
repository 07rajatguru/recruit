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
                <b><p style="text-align: left;">Dear Sir,</p></b>
                <i><p style="text-align: left;">Greetings !</p></i>
                <p style="text-align: left;"><u>Please find my Work Planning Sheet for the day :</u>
                </p>
                    <table width="600" cellpadding="3" cellspacing="0" border="1" border-color="#000000">
                        <tr style="background-color: #ffff00;font-family:Cambria, serif;font-size: 11.0pt;">

                            <td align="center"><b>Date.</b></td>
                            <td align="center"><b>Sr. No.</b></td>
                            <td align="center"><b>Task / Positions</b></td>
                            <td align="center"><b>Projected Time</b></td>
                            <td align="center"><b>Actual Time</b></td>
                            <td align="center"><b>Remarks</b></td>
                        </tr>

                        <?php $i=0; ?>
                        @foreach($work_planning_list as $key => $value)
                            <tr style="font-family:Cambria, serif;font-size: 11.0pt;">
                                
                                @if($i==0)
                                    <td align="center">{{ $today_date }}</td>
                                @else
                                    <td></td>
                                @endif

                                <td align="center">{{ ++$i }}</td>                                
                                <td align="left">{{ $value['description']}}</td>
                                <td align="center">{{ $value['projected_time']}}</td>
                                <td align="center">{{ $value['actual_time']}}</td>
                                <td align="left">{{ $value['remarks']}}</td>
                            </tr>
                        @endforeach
                    </table>
                <p style="font-family:Cambria, serif;font-size: 11.0pt;text-align: left;">Thanks.</p>
            </td>
        </tr>
        <tr>
            <td width="600">
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