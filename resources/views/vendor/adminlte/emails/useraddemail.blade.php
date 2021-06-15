<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>Adler Talent</title>
    @yield('style')
</head>

<style></style>
<body style="margin: 0; padding-top: 30px;">

    <table border="0" cellpadding="0" cellspacing="0" width="600" style="font-family:Helvetica,Arial,sans-serif; border-collapse: collapse; color: #444444;" align="center">
        <tr>
            <td width="600">
                <table width="100%" cellpadding="0" cellspacing="0" style="border:0;height: 70px;">
                    <tr style="background-color:white;">
                        <td colspan="2"></td>
                        <td align="center">
                            <div class="site-branding col-md-2 col-sm-6 col-xs-12" >
                                <a href="http://adlertalent.com/" title="Adler Talent Solutions Pvt. Ltd." style="font-size: 22px;text-decoration:none">
                                    <img width="600" class="site-logo" src="{{$app_url}}/images/Adler-Header.jpg" alt="Adler Talent Solutions Pvt. Ltd." style="height: 90px;padding-top: 16px; vertical-align: middle;">
                                </a>
                            </div>
                        </td>
                        <td colspan="2"></td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td width="600">
                <table width="100%" cellpadding="0" cellspacing="0" style="border:0; padding: 50px 54px;">
                    <tr style="font-family:Cambria, serif;font-size: 11.0pt;">
                        <td>
                            <h4 style="margin: 0;line-height:1.4;">Greetings from Adler Talent Solutions !</h4><br/>

                            <table width="600" cellpadding="3" cellspacing="0" border="1" border-color="#000000">
                                <tr style="font-family:Cambria, serif;font-size: 11.0pt;">
                                    <td align="left"><b>User Name</b></td>
                                    <td align="left">{{ $users_details['first_name'] }}  {{ $users_details['last_name'] }}</td>
                                </tr>

                                <tr style="font-family:Cambria, serif;font-size: 11.0pt;">
                                    <td align="left"><b>Email</b></td>
                                    <td align="left">{{ $users_details['email'] }}</td>
                                </tr>

                                <tr style="font-family:Cambria, serif;font-size: 11.0pt;">
                                    <td align="left"><b>Secondary Gmail</b></td>
                                    <td align="left">{{ $users_details['secondary_email'] }}</td>
                                </tr>

                                <tr style="font-family:Cambria, serif;font-size: 11.0pt;">
                                    <td align="left"><b>Company Name</b></td>
                                    <td align="left">{{ $users_details['company_name'] }}</td>
                                </tr>

                                <tr style="font-family:Cambria, serif;font-size: 11.0pt;">
                                    <td align="left"><b>Department</b></td>
                                    <td align="left">{{ $users_details['department_name'] }}</td>
                                </tr>

                                <tr style="font-family:Cambria, serif;font-size: 11.0pt;">
                                    <td align="left"><b>Role</b></td>

                                    @if($users_details['department_name'] == 'HR Advisory')
                                        @if($users_details['hr_adv_recruitemnt'] == 'Yes')
                                            <td align="left">{{ $users_details['designation'] }} - With Recruitemnt</td>
                                        @else
                                            <td align="left">{{ $users_details['designation'] }} - Without Recruitemnt</td>
                                        @endif
                                    @else
                                        <td align="left">{{ $users_details['designation'] }}</td>
                                    @endif
                                </tr>

                                <tr style="font-family:Cambria, serif;font-size: 11.0pt;">
                                    <td align="left"><b>Reports To</b></td>
                                    <td align="left">{{ $users_details['report_first_name'] }}  {{ $users_details['report_last_name'] }}</td>
                                </tr>

                                <tr style="font-family:Cambria, serif;font-size: 11.0pt;">
                                    <td align="left"><b>Generate Report</b></td>
                                    <td align="left">{{ $users_details['daily_report'] }}</td>
                                </tr>

                                <tr style="font-family:Cambria, serif;font-size: 11.0pt;">
                                    <td align="left"><b>CVs Associated</b></td>
                                    <td align="left">{{ $users_details['cv_report'] }}</td>
                                </tr>

                                <tr style="font-family:Cambria, serif;font-size: 11.0pt;">
                                    <td align="left"><b>Interviews Scheduled</b></td>
                                    <td align="left">{{ $users_details['interview_report'] }}</td>
                                </tr>

                                <tr style="font-family:Cambria, serif;font-size: 11.0pt;">
                                    <td align="left"><b>Leads Added</b></td>
                                    <td align="left">{{ $users_details['lead_report'] }}</td>
                                </tr>

                                <tr style="font-family:Cambria, serif;font-size: 11.0pt;">
                                    <td align="left"><b>Status</b></td>
                                    <td align="left">{{ $users_details['status'] }}</td>
                                </tr>

                                <tr style="font-family:Cambria, serif;font-size: 11.0pt;">
                                    <td align="left"><b>Account Manager</b></td>
                                    <td align="left">{{ $users_details['account_manager'] }}</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td width="600">
                <table width="100%" cellpadding="0" cellspacing="0" style="border:0;height: 70px;">
                    <tr style="height: 45px; background-color: #dddddd;">
                        <td style="text-align: center; font-size: 11px; color: #888888; font-family: arial;">Copyright Adler Talent <?php echo date('Y'); ?>. All rights reserved</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>