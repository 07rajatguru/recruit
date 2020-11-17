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
        <td width="100%">
            <table  cellpadding="0" cellspacing="0" style = "border:0; background-color: #ffffff; color:black; width:620px;">
                <tr style="background-color:white;">
                    <td align="center" width="600">
                        <div class="site-branding col-md-2 col-sm-6 col-xs-12">
                            <a href="http://adlertalent.com/" title="Adler Talent Solutions Pvt. Ltd." style="font-size: 22px;text-decoration:none">
                                <img class="site-logo" src="{{$app_url}}/images/adler-logo-2.jpg" alt="Adler Talent Solutions Pvt. Ltd." style="height: 90px;width:350px;;padding-top: 16px; vertical-align: middle;">
                            </a>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td><br/></td>
                </tr>
                <tr>
                    <td align="center">
                        <p style="display: block;border-top: solid 5.0px #c024e4!important;margin: 0.0px auto;width: 100.0%;"><br/></p>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td width="600"  style="background-color: green; !important;">
            <table width="100%" cellpadding="0" cellspacing="0" style="border:0; background-color: #ffffff;padding: 15px 15px;">
                <tr style="font-family:Cambria, serif;font-size: 11.0pt;">
                    <td style="font-family:Cambria, serif;font-size: 11.0pt;">
                        <b><p style="margin-top: 0px; margin-bottom: 14px;">
                        Dear {{ $candidate_name }},</p></b>
                        <p style="text-align: justify;">We at Adler, would like to take this opportunity to thank you for connecting with our team in the past while planning your career shift.</p>

                        <p style="text-align: justify;color: black;background-color:yellow;"><b>Let’s stay connected for future openings of your interest in Adler and new industry trends via social media platforms:</b></p>

                        <p style="text-align: justify;">

                            <ul style="list-style-type:none;">
                                <li>Follow us on LinkedIn: <br>
                                    <a href="https://www.linkedin.com/company/adler-talent-solutions-pvt-ltd/" target="_blank">https://www.linkedin.com/company/adler-talent-solutions-pvt-ltd/</a>
                                </li>

                                <li>Like us on Facebook: <br>
                                    <a href="https://www.facebook.com/adlertalentsolutions/" target="_blank">https://www.facebook.com/adlertalentsolutions/</a>
                                </li>

                                <li>Share Google Reviews: <br>
                                    <a href="bit.ly/2W8Qcbf" target="_blank">bit.ly/2W8Qcbf</a>
                                </li>
                            </ul>

                            <br>We would appreciate your ratings and reviews on Google based on your experience with our team of professionals which will help us improve.

                            <br><br>For any concern/queries/grievances, feel free to email us on 
                            <a href="mailto:{{ $owner_email }}">{{ $owner_email }}</a>
                        </p>
                        <p><b>Thanks.</b></p>
                    </td>
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