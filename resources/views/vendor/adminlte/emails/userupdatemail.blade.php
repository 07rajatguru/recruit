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
            <td width="700">
                <table width="100%" cellpadding="0" cellspacing="0" style="border:0; padding: 50px 54px;">
                    <tr style="font-family:Cambria, serif;font-size: 11.0pt;">
                        <td>
                            <h4 style="margin: 0;line-height:1.4;">Greetings from Adler Talent Solutions !</h4><br/>

                            <table width="700" cellpadding="3" cellspacing="0" border="1" border-color="#000000">

                                <tr style="font-family:Cambria, serif;font-size: 11.0pt;">
                                    <td align="left"><b>Field</b></td>
                                    <td align="left"><b>Actual Value</b></td>
                                    <td align="left"><b>Updated Value</b></td>
                                </tr>

                                <tr style="font-family:Cambria, serif;font-size: 11.0pt;">
                                    <td align="left"><b>User Name</b></td>
                                    <td align="left">{{ $old_value_array['first_name'] }}  {{ $old_value_array['last_name'] }}</td>
                                    <td align="left">{{ $new_value_array['first_name'] }}  {{ $new_value_array['last_name'] }}</td>
                                </tr>

                                <tr style="font-family:Cambria, serif;font-size: 11.0pt;">
                                    <td align="left"><b>Email</b></td>
                                    <td align="left">{{ $old_value_array['email'] }}</td>
                                    <td align="left">{{ $new_value_array['email'] }}</td>
                                </tr>

                                <tr style="font-family:Cambria, serif;font-size: 11.0pt;">
                                    <td align="left"><b>Secondary Gmail</b></td>
                                    <td align="left">{{ $old_value_array['semail'] }}</td>
                                    <td align="left">{{ $new_value_array['semail'] }}</td>
                                </tr>

                                <tr style="font-family:Cambria, serif;font-size: 11.0pt;">
                                    <td align="left"><b>Company Name</b></td>
                                    <td align="left">{{ $old_value_array['company_name'] }}</td>
                                    <td align="left">{{ $new_value_array['company_name'] }}</td>
                                </tr>

                                <tr style="font-family:Cambria, serif;font-size: 11.0pt;">
                                    <td align="left"><b>Department</b></td>
                                    <td align="left">{{ $old_value_array['department_name'] }}</td>
                                    <td align="left">{{ $new_value_array['department_name'] }}</td>
                                </tr>

                                <tr style="font-family:Cambria, serif;font-size: 11.0pt;">
                                    <td align="left"><b>Role</b></td>

                                    @if($old_value_array['department_name'] == 'HR Advisory')
                                        @if($old_value_array['hr_adv_recruitemnt'] == 'Yes')
                                            <td align="left">{{ $old_value_array['designation'] }} - With Recruitemnt</td>
                                        @else
                                            <td align="left">{{ $old_value_array['designation'] }} - Without Recruitemnt</td>
                                        @endif
                                    @else
                                        <td align="left">{{ $old_value_array['designation'] }}</td>
                                    @endif

                                    @if($new_value_array['department_name'] == 'HR Advisory')
                                        @if($new_value_array['hr_adv_recruitemnt'] == 'Yes')
                                            <td align="left">{{ $new_value_array['designation'] }} - With Recruitemnt</td>
                                        @else
                                            <td align="left">{{ $new_value_array['designation'] }} - Without Recruitemnt</td>
                                        @endif
                                    @else
                                        <td align="left">{{ $new_value_array['designation'] }}</td>
                                    @endif
                                </tr>

                                <tr style="font-family:Cambria, serif;font-size: 11.0pt;">
                                    <td align="left"><b>Reports To</b></td>
                                    <td align="left">{{ $old_value_array['reports_to'] }}</td>
                                    <td align="left">{{ $new_value_array['reports_to'] }}</td>
                                </tr>

                                <tr style="font-family:Cambria, serif;font-size: 11.0pt;">
                                    <td align="left"><b>Generate Report</b></td>
                                    <td align="left">{{ $old_value_array['check_report'] }}</td>
                                    <td align="left">{{ $new_value_array['check_report'] }}</td>
                                </tr>

                                <tr style="font-family:Cambria, serif;font-size: 11.0pt;">
                                    <td align="left"><b>CVs Associated</b></td>
                                    <td align="left">{{ $old_value_array['cv_report'] }}</td>
                                    <td align="left">{{ $new_value_array['cv_report'] }}</td>
                                </tr>

                                <tr style="font-family:Cambria, serif;font-size: 11.0pt;">
                                    <td align="left"><b>Interviews Scheduled</b></td>
                                    <td align="left">{{ $old_value_array['interview_report'] }}</td>
                                    <td align="left">{{ $new_value_array['interview_report'] }}</td>
                                </tr>

                                <tr style="font-family:Cambria, serif;font-size: 11.0pt;">
                                    <td align="left"><b>Leads Added</b></td>
                                    <td align="left">{{ $old_value_array['lead_report'] }}</td>
                                    <td align="left">{{ $new_value_array['lead_report'] }}</td>
                                </tr>

                                <tr style="font-family:Cambria, serif;font-size: 11.0pt;">
                                    <td align="left"><b>Status</b></td>
                                    <td align="left">{{ $old_value_array['status'] }}</td>
                                    <td align="left">{{ $new_value_array['status'] }}</td>
                                </tr>

                                <tr style="font-family:Cambria, serif;font-size: 11.0pt;">
                                    <td align="left"><b>Account Manager</b></td>
                                    <td align="left">{{ $old_value_array['account_manager'] }}</td>
                                    <td align="left">{{ $new_value_array['account_manager'] }}</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td width="600">
                <table width="100%" cellpadding="0" cellspacing="0" style="border:0;height: 60px;">
                    <tr style="height: 45px; background-color: #dddddd;">
                        <td style="text-align: center; font-size: 11px; color: #888888; font-family: arial;">Copyright Adler Talent <?php echo date('Y'); ?>. All rights reserved</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>