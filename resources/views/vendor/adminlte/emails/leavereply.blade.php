<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <title>Adler Talent</title>
        @yield('style')
    </head>

    <body style="margin: 0; padding-top: 30px;">
        <!-- <table border="0" cellpadding="0" cellspacing="0" width="600" style="font-family:Helvetica,Arial,sans-serif; border-collapse: collapse; color: #444444;">
            <tr>
                <td width="600">
                    <table cellpadding="0" cellspacing="0" style="border:0; background-color: #ffffff;padding: 15px 15px;">

                        <tr style="font-family:Cambria, serif;font-size: 11.0pt;">
                            <td>{!! $leave_message !!}</td>
                        </tr>

                        @if(isset($remarks) && $remarks != '')
                            <tr style="font-family:Cambria, serif;font-size: 11.0pt;">
                                <td><br/><b>Remarks :</b></td>
                            </tr>

                            <tr tyle="font-family:Cambria, serif;font-size: 11.0pt;">
                                <td><br/>{!! $remarks !!}</td>
                            </tr>
                        @endif

                        <tr><td>{!! $signature !!}</td></tr>
                    </table>
                </td>
            </tr>
        </table> -->

        @if(isset($module) && $module == 'Late In Early Go Reply')
             <table border="0" cellpadding="0" cellspacing="0" width="600" style="font-family:Helvetica,Arial,sans-serif;" align="left">
                <tr>
                    <td width="600" style="font-family:Cambria, serif;font-size: 11.0pt;">

                        <b><p style="text-align: left;">Dear {{ $user_name }},</p></b>

                        <p style="text-align: left;">Greetings from Easy2Hire!</p>

                        <p style="text-align: left;">This is to inform you that your request has been approved for the date of {{ $date }}</p>

                        <p style="text-align: left;">In case of any changes, please connect with your Reporting Manager.</p>

                        <p style="text-align: left;">Thanks.<br/>Easy2Hire Team</p>
                    </td>
                </tr>
            </table>
        @else

            <table border="0" cellpadding="0" cellspacing="0" width="600" style="font-family:Helvetica,Arial,sans-serif;" align="left">
                <tr>
                    <td width="600" style="font-family:Cambria, serif;font-size: 11.0pt;">

                        <b><p style="text-align: left;">Dear {{ $user_name }},</p></b>

                        <p style="text-align: left;">Greetings from Easy2Hire!</p>

                        @if($days == '1' && $status == '1' && $type_of_leave == 'Full Day')

                            <p style="text-align: left;">This is to inform you that your leave has been approved for the date of {{ $from_date }}</p>

                            <p style="text-align: left;">You are hereby requested to co-ordinate with your Reporting Manager and plan the delegation of ongoing tasks within the team to avoid any further delay or communication gap.</p>

                            <p style="text-align: left;">In case of any changes, please connect with your Reporting Manager.</p>

                        @elseif($days >= '2' && $status == '1' && $type_of_leave == 'Full Day')

                            <p style="text-align: left;">This is to inform you that your leave has been approved from {{ $from_date }} to {{ $to_date }}.</p>

                            <p style="text-align: left;">You are hereby requested to co-ordinate with your Reporting Manager and plan the delegation of ongoing tasks within the team to avoid any further delay or communication gap.</p>

                            <p style="text-align: left;">In case of any changes, please connect with your Reporting Manager.</p>

                        @elseif($days == '0.5' && $status == '1' && $type_of_leave == 'Half Day')

                            @if(isset($half_leave_type) && $half_leave_type != '') 
                                <p style="text-align: left;">This is to inform you that your leave has been approved for the {{ $half_leave_type }} of {{ $from_date }}.</p>
                            @else
                                <p style="text-align: left;">This is to inform you that your leave has been approved for the (First half/Second half) of {{ $from_date }}.</p>
                            @endif

                            <p style="text-align: left;">You are hereby requested to co-ordinate with your Reporting Manager and plan the delegation of ongoing tasks within the team to avoid any further delay or communication gap.</p>

                            <p style="text-align: left;">In case of any changes, please connect with your Reporting Manager.</p>

                        @elseif($days >= '1' && $status == '1' && $type_of_leave == 'Half Day')

                            @if(isset($half_leave_type) && $half_leave_type != '') 
                                <p style="text-align: left;">This is to inform you that your leave has been approved for the {{ $half_leave_type }} from {{ $from_date }} to {{ $to_date }}.</p>
                            @else
                                <p style="text-align: left;">This is to inform you that your leave has been approved for the (First half/Second half) from {{ $from_date }} to {{ $to_date }}.</p>
                            @endif

                            <p style="text-align: left;">You are hereby requested to co-ordinate with your Reporting Manager and plan the delegation of ongoing tasks within the team to avoid any further delay or communication gap.</p>

                            <p style="text-align: left;">In case of any changes, please connect with your Reporting Manager.</p>

                        @elseif($days == '0.5' && $status == '2' && $type_of_leave == 'Half Day')

                            @if(isset($half_leave_type) && $half_leave_type != '')
                                <p style="text-align: left;">This is to inform you that your leave has been rejected for the {{ $half_leave_type }} of {{ $from_date }}.</p>
                            @else 
                                <p style="text-align: left;">This is to inform you that your leave has been rejected for the (First half/Second half) of {{ $from_date }}.</p>
                            @endif

                            <p style="text-align: left;">You are hereby requested to understand the reason of rejection as per the remarks mentioned in E2H.</p>

                            <p style="text-align: left;">In case of any queries/discussion, feel free to connect with your Reporting Manager.</p>

                        @elseif($days >= '1' && $status == '2' && $type_of_leave == 'Half Day')

                            @if(isset($half_leave_type) && $half_leave_type != '')
                                <p style="text-align: left;">This is to inform you that your leave has been rejected for the {{ $half_leave_type }} from {{ $from_date }} to {{ $to_date }}.</p>
                            @else
                                <p style="text-align: left;">This is to inform you that your leave has been rejected for the (First half/Second half) from {{ $from_date }} to {{ $to_date }}.</p>
                            @endif

                            <p style="text-align: left;">You are hereby requested to understand the reason of rejection as per the remarks mentioned in E2H.</p>

                            <p style="text-align: left;">In case of any queries/discussion, feel free to connect with your Reporting Manager.</p>

                        @elseif($days == '1' && $status == '2' && $type_of_leave == 'Full Day')

                            <p style="text-align: left;">This is to inform you that your leave has been rejected for the {{ $from_date }}.</p>

                            <p style="text-align: left;">You are hereby requested to understand the reason of rejection as per the remarks mentioned in E2H.</p>

                            <p style="text-align: left;">In case of any queries/discussion, feel free to connect with your Reporting Manager.</p>

                        @elseif($days >= '2' && $status == '2' && $type_of_leave == 'Full Day')

                            <p style="text-align: left;">This is to inform you that your leave has been rejected from {{ $from_date }} to {{ $to_date }}.</p>

                            <p style="text-align: left;">You are hereby requested to understand the reason of rejection as per the remarks mentioned in E2H.</p>

                            <p style="text-align: left;">In case of any queries/discussion, feel free to connect with your Reporting Manager.</p>
                        @endif
                        
                        <p style="text-align: left;">Thanks.<br/>Easy2Hire Team</p>
                    </td>
                </tr>
            </table>
        @endif
    </body>
</html>