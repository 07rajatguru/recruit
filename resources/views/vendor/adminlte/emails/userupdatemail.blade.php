<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>Adler Talent</title>
    @yield('style')
</head>

<body style="margin: 0; padding-top: 30px;">
    <table border="0" cellpadding="0" cellspacing="0" width="700" style="font-family:Helvetica,Arial,sans-serif; border-collapse: collapse; color: #444444;" align="center">
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
                                            <td align="left">{{ $old_value_array['designation'] }} - With Recruitment</td>
                                        @else
                                            <td align="left">{{ $old_value_array['designation'] }} - Without Recruitment</td>
                                        @endif
                                    @else
                                        <td align="left">{{ $old_value_array['designation'] }}</td>
                                    @endif

                                    @if($new_value_array['department_name'] == 'HR Advisory')
                                        @if($new_value_array['hr_adv_recruitemnt'] == 'Yes')
                                            <td align="left">{{ $new_value_array['designation'] }} - With Recruitment</td>
                                        @else
                                            <td align="left">{{ $new_value_array['designation'] }} - Without Recruitment</td>
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
                                    <td align="left"><b>Working Hours</b></td>
                                    <td align="left">{{ $old_value_array['working_hours'] }}
                                    </td>
                                    <td align="left">{{ $new_value_array['working_hours'] }}
                                    </td>
                                </tr>

                                <tr style="font-family:Cambria, serif;font-size: 11.0pt;">
                                    <td align="left"><b>Half Day Working Hours</b></td>
                                    <td align="left">
                                        {{ $old_value_array['half_day_working_hours'] }}</td>
                                    <td align="left">
                                        {{ $new_value_array['half_day_working_hours'] }}</td>
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
    </table>
</body>
</html>