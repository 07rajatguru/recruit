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

                    <p style="text-align: left;">
                        This is to inform you that your Work Planning for {{ $today_date }} has been rejected. You are requested to respond back to your Reporting Manager justifying queries raised against each task.
                    </p>
                                
                    <p style="text-align: left;">
                        If required, connect with your Reporting Manager to resolve the query.
                    </p>
                                
                    <p style="text-align: left;">
                        Please note that the decision of the Reporting Manager based on task remarks/justification will be full and final.
                    </p>
                                
                    <p>
                        <i>Note: Reporting Manager is requested to mention the specific reason to the employee at the time of rejection.</i>
                    </p>
                    <p>Thanks.</p>
                </td>
            </tr>
        </table>
    </body>
</html>