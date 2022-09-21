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
                <i><p style="text-align: left;">Greetings from Adler Talent Solutions !</p></i>
                <p style="text-align: left;"><u>Please find applicant candidates report for your reference:</u></p>
                    <table width="600" cellpadding="3" cellspacing="0" border="1" border-color="#000000">

                        <tr style="background-color: #7598d9;font-family:Cambria, serif;font-size: 11.0pt;">
                            <td align="left"><b>Sr. No.</b></td>
                            <td align="left"><b>Posting Title</b></td>
                            <td align="left"><b>City</b></td>
                            <td align="left"><b>Applicant<br/>Date</b></td>
                            <td align="left"><b>Candidate<br/>Name</b></td>
                            <td align="left"><b>Candidate<br/>Email</b></td>
                            <td align="left"><b>Mobile<br/>Number</b></td>
                            <td align="left"><b>Functional<br/>Roles</b></td>
                            <td align="left"><b>Last<br/>Employer</b></td>
                            <td align="left"><b>Last<br/>Job Title</b></td>
                            <td align="left"><b>Current<br/>Salary</b></td>
                            <td align="left"><b>Expected<br/>Salary</b></td>
                        </tr>

                        @if(isset($applicant_data) && sizeof($applicant_data) > 0)
                            @foreach($applicant_data as $k => $v)
                                @if(isset($v['applicant_candidates']) && sizeof($v['applicant_candidates']) > 0)
                                    @foreach($v['applicant_candidates'] as $key => $value)
                                        <tr style="font-family:Cambria, serif;font-size: 11.0pt;">
                                            <td align="left">{{ ++$key }}</td>
                                            <td align="left">{{ $v['posting_title'] }}</td>
                                            <td align="left">{{ $v['city'] }}</td>
                                            <td align="left">{{ $value['applicant_date'] }}</td>
                                            <td align="left">{{ $value['full_name'] }}</td>
                                            <td align="left">{{ $value['email'] }}</td>
                                            <td align="left">{{ $value['mobile'] }}</td>
                                            <td align="left">{{ $value['functional_roles_name'] }}</td>
                                            <td align="left">{{ $value['current_employer'] }}</td>
                                            <td align="left">{{ $value['current_job_title'] }}</td>
                                            <td align="left">{{ $value['current_salary'] }}</td>
                                            <td align="left">{{ $value['expected_salary'] }}</td>
                                        </tr>
                                    @endforeach
                                @endif
                            @endforeach
                        @endif
                    </table>
                <p style="font-family:Cambria, serif;font-size: 11.0pt;text-align: left;">Hope this works.</p>
                <p style="font-family:Cambria, serif;font-size: 11.0pt;text-align: left;">Thanks.</p>
            </td>
        </tr>
    </table>
</body>
</html>