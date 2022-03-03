<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <title>Adler Talent Solution Pvt. Ltd.</title>
        @yield('style')
    </head>

    <body style="margin: 0; padding-top: 30px;">
        <table border="0" cellpadding="0" cellspacing="0" width="600" style="font-family:Helvetica,Arial,sans-serif;" align="left">
            <tr>
                <td width="600" style="font-family:Cambria, serif;font-size: 11.0pt;">

                    <b><p style="text-align: left;">Dear {{ $user_name }},</p></b>

                    <p style="text-align: left;">Greetings from Easy2Hire!</p>

                    @if($status == '1')

                        @if($from_date == $to_date)

                            <p style="text-align: left;">This is to inform you that your Work from Home request has been approved for the date of {{ $from_date }}.</p>
                        @else

                            <p style="text-align: left;">This is to inform you that your Work from Home request has been approved for the date from {{ $from_date }} to {{ $to_date }}.</p>
                        @endif

                        <p style="text-align: left;">You are hereby requested to co-ordinate with your Reporting Manager and plan the delegation of ongoing tasks within the team to avoid any further delay or communication gap.</p>

                        <p style="text-align: left;">Note: In case of unavailability during work from home or unplanned personal engagement without prior written communication to the Reporting Manager will lead to full-day rejection of Work Planning and absent for the day.</p>

                        <p style="text-align: left;">In case of any changes, please connect with your Reporting Manager.</p>

                    @elseif($status == '2')

                        @if($from_date == $to_date)

                            <p style="text-align: left;">This is to inform you that your Work from Home request has been rejected for the date of {{ $from_date }}.</p>
                        @else

                            <p style="text-align: left;">This is to inform you that your Work from Home request has been rejected for the date from {{ $from_date }} to {{ $to_date }}.</p>
                        @endif

                        <p style="text-align: left;">You are hereby requested to understand the reason of rejection as per the remarks mentioned in E2H.</p>

                        <p style="text-align: left;">In case of any queries/discussion, feel free to connect with your Reporting Manager.</p>
                    @endif

                    <p style="text-align: left;">Thanks.<br/>Easy2Hire Team.</p>
                </td>
            </tr>
        </table>
    </body>
</html>