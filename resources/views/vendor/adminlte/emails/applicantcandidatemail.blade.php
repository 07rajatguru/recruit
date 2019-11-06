<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>Adler Talent</title>

    @yield('style')
</head>

<body>
<table border="0" cellpadding="0" cellspacing="0" width="600" style="font-family:Helvetica,Arial,sans-serif; border-collapse: collapse; color: #444444;" align="center">
    <tr>
        <td width="600" >
            <table cellpadding="0" cellspacing="0" style="border:0;height: 70px;">
                <tr style="background-color:white;">
                    <td align="center">
                        <div class="site-branding col-md-2 col-sm-6 col-xs-12" >
                            <a href="http://adlertalent.com/" title="Adler Talent Solutions Pvt. Ltd." style="font-size: 22px;text-decoration:none">
                                <img width="600" class="site-logo"  src="{{$app_url}}/images/Adler-Header.jpg" alt="Adler Talent Solutions Pvt. Ltd." style="height: 90px;padding-top: 16px; vertical-align: middle;">
                            </a>
                        </div>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td>
            <table width="100%" cellpadding="0" cellspacing="0" style="border:0; background-color: #ffffff; padding: 0px 50px 34px;">
                <td colspan="7">
                    <h3>Basic Information</h3>
                </td>
                <tr>
                    <th align="center" style="border-top: black 1px solid;padding: 8px;border-left: black 1px solid;">Candidate Name</th>
                    <td align="center" style="border-top: black 1px solid;padding: 8px;border-left: black 1px solid;border-right: black 1px solid;">
                        {{ $candidate_details['full_name'] }}</td>
                    <th align="center" style="border-top: black 1px solid;padding: 8px;">Candidate Email</th>
                    <td align="center" style="border-top: black 1px solid;padding: 8px;border-left: black 1px solid;border-right: black 1px solid;">
                        {{ $candidate_details['email'] }}</td>
                </tr>
                <tr>
                    <th align="center" style="border-top: black 1px solid;padding: 8px;border-left: black 1px solid;">Gender</th>
                    <td align="center" style="border-top: black 1px solid;padding: 8px;border-left: black 1px solid;border-right: black 1px solid;">
                        {{ $candidate_details['gender'] }}</td>
                    <th align="center" style="border-top: black 1px solid;padding: 8px;">Marital Status</th>
                    <td align="center" style="border-top: black 1px solid;padding: 8px;border-left: black 1px solid;border-right: black 1px solid;">
                        {{ $candidate_details['marital_status'] }}</td>
                </tr>
                <tr>
                    <th align="center" style="border-top: black 1px solid;padding: 8px;border-left: black 1px solid;border-bottom: black 1px solid;">Mobile Number</th>
                    <td align="center" style="border-top: black 1px solid;padding: 8px;border-left: black 1px solid;border-right: black 1px solid;border-bottom: black 1px solid;">
                        {{ $candidate_details['mobile'] }}</td>
                    <th align="center" style="border-top: black 1px solid;padding: 8px;border-bottom: black 1px solid;">Phone</th>
                    <td align="center" style="border-top: black 1px solid;padding: 8px;border-left: black 1px solid;border-right: black 1px solid;border-bottom: black 1px solid;">
                        {{ $candidate_details['phone'] }}</td>
                </tr>
            </table>

            <table width="100%" cellpadding="0" cellspacing="0" style="border:0; background-color: #ffffff; padding: 0px 50px 34px;">
                <td colspan="7">
                    <h3>Address Information</h3>
                </td>
                <tr>
                    <th align="center" style="border-top: black 1px solid;padding: 8px;border-left: black 1px solid;">Street 1</th>
                    <td align="center" style="border-top: black 1px solid;padding: 8px;border-left: black 1px solid;border-right: black 1px solid;">
                        {{ $candidate_details['street1'] }}</td>
                    <th align="center" style="border-top: black 1px solid;padding: 8px;">Street 2</th>
                    <td align="center" style="border-top: black 1px solid;padding: 8px;border-left: black 1px solid;border-right: black 1px solid;">
                        {{ $candidate_details['street2'] }}</td>
                </tr>
                <tr>
                    <th align="center" style="border-top: black 1px solid;padding: 8px;border-left: black 1px solid;">Country</th>
                    <td align="center" style="border-top: black 1px solid;padding: 8px;border-left: black 1px solid;border-right: black 1px solid;">
                        {{ $candidate_details['country'] }}</td>
                    <th align="center" style="border-top: black 1px solid;padding: 8px;">State</th>
                    <td align="center" style="border-top: black 1px solid;padding: 8px;border-left: black 1px solid;border-right: black 1px solid;">
                        {{ $candidate_details['state'] }}</td>
                </tr>
                <tr>
                    <th align="center" style="border-top: black 1px solid;padding: 8px;border-left: black 1px solid;border-bottom: black 1px solid;">City</th>
                    <td align="center" style="border-top: black 1px solid;padding: 8px;border-left: black 1px solid;border-right: black 1px solid;border-bottom: black 1px solid;">
                        {{ $candidate_details['city'] }}</td>
                    <th align="center" style="border-top: black 1px solid;padding: 8px;border-bottom: black 1px solid;">Zip Code</th>
                    <td align="center" style="border-top: black 1px solid;padding: 8px;border-left: black 1px solid;border-right: black 1px solid;border-bottom: black 1px solid;">
                        {{ $candidate_details['zipcode'] }}</td>
                </tr>
            </table>

            <table width="100%" cellpadding="0" cellspacing="0" style="border:0; background-color: #ffffff; padding: 0px 50px 34px;">
                <td colspan="7">
                    <h3>Education and Professional Information</h3>
                </td>
                <tr>
                    <th align="center" style="border-top: black 1px solid;padding: 8px;border-left: black 1px solid;">Current Employer</th>
                    <td align="center" style="border-top: black 1px solid;padding: 8px;border-left: black 1px solid;border-right: black 1px solid;">
                        {{ $candidate_details['current_employer'] }}</td>
                    <th align="center" style="border-top: black 1px solid;padding: 8px;">Current Job Title</th>
                    <td align="center" style="border-top: black 1px solid;padding: 8px;border-left: black 1px solid;border-right: black 1px solid;">
                        {{ $candidate_details['current_job_title'] }}</td>
                </tr>
                <tr>
                    <th align="center" style="border-top: black 1px solid;padding: 8px;border-left: black 1px solid;">Key Skills</th>
                    <td align="center" style="border-top: black 1px solid;padding: 8px;border-left: black 1px solid;border-right: black 1px solid;">
                        {{ $candidate_details['skill'] }}</td>
                    <th align="center" style="border-top: black 1px solid;padding: 8px;">   Functional Roles</th>
                    <td align="center" style="border-top: black 1px solid;padding: 8px;border-left: black 1px solid;border-right: black 1px solid;">
                        {{ $candidate_details['functional_roles_name'] }}</td>
                </tr>
                <tr>
                    <th align="center" style="border-top: black 1px solid;padding: 8px;border-left: black 1px solid;">Education Qualification</th>
                    <td align="center" style="border-top: black 1px solid;padding: 8px;border-left: black 1px solid;border-right: black 1px solid;">
                        {{ $candidate_details['eduction_qualification'] }}</td>
                    <th align="center" style="border-top: black 1px solid;padding: 8px;">Specialization</th>
                    <td align="center" style="border-top: black 1px solid;padding: 8px;border-left: black 1px solid;border-right: black 1px solid;">
                        {{ $candidate_details['education_specialization'] }}</td>
                </tr>
                <tr>
                    <th align="center" style="border-top: black 1px solid;padding: 8px;border-left: black 1px solid;">Experience years</th>
                    <td align="center" style="border-top: black 1px solid;padding: 8px;border-left: black 1px solid;border-right: black 1px solid;">
                        {{ $candidate_details['experience_years'] }}</td>
                    <th align="center" style="border-top: black 1px solid;padding: 8px;">Experience Months</th>
                    <td align="center" style="border-top: black 1px solid;padding: 8px;border-left: black 1px solid;border-right: black 1px solid;">
                        {{ $candidate_details['experience_months'] }}</td>
                </tr>
                <tr>
                    <th align="center" style="border-top: black 1px solid;padding: 8px;border-left: black 1px solid;">Current Salary</th>
                    <td align="center" style="border-top: black 1px solid;padding: 8px;border-left: black 1px solid;border-right: black 1px solid;">
                        {{ $candidate_details['current_salary'] }}</td>
                    <th align="center" style="border-top: black 1px solid;padding: 8px;">Expected Salary</th>
                    <td align="center" style="border-top: black 1px solid;padding: 8px;border-left: black 1px solid;border-right: black 1px solid;">
                        {{ $candidate_details['expected_salary'] }}</td>
                </tr>
                <tr>
                    <th align="center" style="border-top: black 1px solid;padding: 8px;border-left: black 1px solid;border-bottom: black 1px solid;">       Skype Id</th>
                    <td align="center" style="border-top: black 1px solid;padding: 8px;border-left: black 1px solid;border-right: black 1px solid;border-bottom: black 1px solid;" colspan="4">
                        {{ $candidate_details['skype_id'] }}</td>
                </tr>
            </table>
        </td>
    </tr>

    <tr width="600" style="height: 45px; background-color: #dddddd;">
        <td style="text-align: center; font-size: 11px; color: #888888; font-family: arial;">Copyright Adler Talent <?php echo date('Y'); ?>. All rights reserved</td>
    </tr>
</table>
</body>
</html>