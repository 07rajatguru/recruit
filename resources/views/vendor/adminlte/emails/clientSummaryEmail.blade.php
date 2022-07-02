<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <title>Adler Talent Solution Pvt. Ltd.</title>
        @yield('style')
    </head>

    <body style="margin: 0; padding-top: 30px; background-color: #f5f5f5;">
        <table border="0" cellpadding="0" cellspacing="0" width="600" style="font-family:Helvetica,Arial,sans-serif; border-collapse: collapse; color: #444444;" align="center">
            <tr>
                <td width="600">
                    <table width="100%" cellpadding="0" cellspacing="0" style="border:0; background-color: #ffffff; padding: 50px 54px;">
                        <tr style="font-family:Cambria, serif;font-size: 11.0pt;">
                            <td style="font-family:Cambria, serif;font-size: 11.0pt;">
                                <b><p style="margin-top: 0px; margin-bottom: 14px;">Dear Team,</p></b>
                                <i><p>Greetings !</p></i>
                                <p><u>Please find details as below :</u></p>

                                @if(isset($client_details) && sizeof($client_details) > 0)
                                    <table width="600" cellpadding="3" cellspacing="0" border="1" border-color="#000000">
                                        <tr style="background-color: #7598d9;font-family:Cambria, serif;font-size: 11.0pt;">
                                            <td align="center"><b>Sr.No.</b></td>
                                            <td align="center"><b>Company Name</b></td>
                                            <td align="center"><b>Contact Point</b></td>
                                            <td align="center"><b>Location</b></td>
                                            <td align="center"><b>Client Owner</b></td>
                                        </tr>
                                        <?php $i=0; ?>
                                        @foreach($client_details as $key => $value)
                                            <tr style="font-family:Cambria, serif;font-size: 11.0pt;">
                                                <td align="center">{{ ++$i }}</td>
                                                <td align="center">{{ $value['name'] }}</td>
                                                <td align="center">{{ $value['coordinator_name'] }}</td>
                                                <td align="center">{{ $value['billing_city'] }}</td>
                                                <td align="center">{{ $value['am_name'] }}</td>
                                            </tr>
                                        @endforeach
                                    </table>
                                @endif

                                @if(isset($job_details) && sizeof($job_details) > 0)
                                    <table width="600" cellpadding="3" cellspacing="0" border="1" border-color="#000000">
                                        <tr style="background-color: #7598d9;font-family:Cambria, serif;font-size: 11.0pt;">
                                            <td align="center"><b>Sr.No.</b></td>
                                            <td align="center"><b>Company Name</b></td>
                                            <td align="center"><b>Contact Point</b></td>
                                            <td align="center"><b>Posting Title</b></td>
                                            <td align="center"><b>Hiring Manager</b></td>
                                        </tr>
                                        <?php $i=0; ?>
                                        @foreach($job_details as $key => $value)
                                            <tr style="font-family:Cambria, serif;font-size: 11.0pt;">
                                                <td align="center">{{ ++$i }}</td>
                                                <td align="center">{{ $value['company_name'] }}</td>
                                                <td align="center">{{ $value['contact_person'] }}</td>
                                                <td align="center">{{ $value['posting_title'] }}</td>
                                                <td align="center">{{ $value['user_name'] }}</td>
                                            </tr>
                                        @endforeach
                                    </table>
                                @endif

                                <p>Thanks.</p>
                                <p>Easy2Hire Team</p>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </body>
</html>