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
                    <b>
                        <p style="margin-top: 0px; margin-bottom: 14px;">Dear {{ $user_name }},</p>
                    </b>

                    @if(isset($module) && $module == 'Work Planning Delay')
                        <p style="text-align: left;">
                            This is to inform you that your Work Planning for {{ $added_date }} has been delayed. You are requested to make a note that your login time for the day is {{ $loggedin_time }} and your work planning report time is {{ $work_planning_time }} which is more than one hour as per policy.
                        </p>
                    @endif

                    @if(isset($module) && $module == 'Work Planning Status Delay')
                        <p style="text-align: left;">
                            This is to inform you that your Work Planning status for {{ $added_date }} has been delayed. You are requested to make a note that your logout time for the day was {{ $loggedout_time }} and your work planning status has not been submitted on time i.e. 11 AM of the next day.
                        </p>
                    @endif
                                
                    <p style="text-align: left;">
                        As per policy, an employee can avail a benefit of 3 such delayed emails in login/logout report. More than 3 delays will lead to half-day by system.
                    </p>
                                
                    <p style="text-align: left;">
                        Hence, it is highly recommended to send your reports as per policy.
                    </p>
                                
                    <p>
                        For any query/discussion, feel free to connect with your Reporting Manager.
                    </p>
                    <p>Thanks.</p>
                </td>
            </tr>
        </table>
    </body>
</html>