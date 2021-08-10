<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>Adler Talent Solution Pvt. Ltd.</title>
    @yield('style')
</head>

<body>
<table border="0" cellpadding="0" cellspacing="0" width="600" style="font-family:Helvetica,Arial,sans-serif; border-collapse: collapse; color: #444444;">
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
            <table width="100%" cellpadding="0" cellspacing="0" style="border:0; background-color: #ffffff;padding: 15px 15px;">
                <tr style="font-family:Cambria, serif;font-size: 11.0pt;">
                    <td style="font-family:Cambria, serif;font-size: 11.0pt;">
                        <b><p style="margin-top: 0px; margin-bottom: 14px;">
                        Dear {{ $candidate_job_details['full_name'] }},</p></b>
                        <i><p style="text-align: left;">Greetings from Adler Talent Solutions !</p></i>
                        <p style="text-align: justify;">It has been wonderful being a part of your journey as you move one step forward towards your career goal. We are grateful for your valuable support and cooperation during the process.</p>

                        <p style="text-align: justify;">Owing to your successful interviews and subsequent shortlisting for the {{ $candidate_job_details['posting_title'] }} in {{ $candidate_job_details['company_name'] }}, we would request you to fill this form and share it with us as a part of process.</p>

                        <p style="text-align: justify;">For any query/discussion, feel free to connect with us.</p>

                        <p style="text-align: justify;">We would be happy / delighted if you can review us on <a href="bit.ly/2W8Qcbf" target="_blank">bit.ly/2W8Qcbf</a> with your experience with us and follow us on <br/>

                            <ul style="list-style-type:none;">
                                <li>LinkedIn: <br>
                                    <a href="https://www.linkedin.com/company/adler-talent-solutions-pvt-ltd/" target="_blank">https://www.linkedin.com/company/adler-talent-solutions-pvt-ltd/</a>
                                </li>

                                <li>Facebook: <br>
                                    <a href="https://www.facebook.com/adlertalentsolutions/" target="_blank">https://www.facebook.com/adlertalentsolutions/</a>
                                </li>

                                <li>Instagram: <br>
                                    <a href="https://www.instagram.com/adlertalent/" target="_blank">https://www.instagram.com/adlertalent/</a>
                                </li>
                            </ul>

                            for more updates on hiring.
                        </p>

                        <p><b>Thanks.</b></p>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td width="600">
            <table width="100%" cellpadding="0" cellspacing="0" style="border:0;">
                <tr>
                    <td style="padding: 20px;">{!! $owner_signature !!}</td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td width="600">
            <table width="100%" cellpadding="0" cellspacing="0" style="border:0;height: 50px;">
                <tr style="height: 30px; background-color: #dddddd;">
                    <td style="text-align: center; font-size: 11px; color: #888888; font-family: arial;">Copyright Adler Talent <?php echo date('Y'); ?>. All Rights Reserved.</td>
                </tr>
            </table>
        </td>
    </tr>
</table>
</body>
</html>