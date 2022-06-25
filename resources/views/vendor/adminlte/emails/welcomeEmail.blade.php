<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>Adler Talent</title>

    @yield('style')
</head>

    <body style="margin: 0; padding-top: 30px; background-color: white;">
        <table border="0" cellpadding="0" cellspacing="0" width="600" style="font-family:Helvetica,Arial,sans-serif; border-collapse: collapse; color: #444444;" align="center">
            <tr>
                <td width="600">
                    <table width="100%" cellpadding="0" cellspacing="0" style="border:0;height: 70px;">
                        <tr style="background-color:white;">
                            <td colspan="2"></td>
                            <td align="center">
                                <div class="site-branding col-md-2 col-sm-6 col-xs-12" >
                                    <a href="https://adlertalent.com/" title="Adler Talent Solutions Pvt. Ltd." style="font-size: 22px;text-decoration:none">
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
                <td width="600" style="font-family:Cambria, serif;font-size: 11.0pt;">
                   
                   <b><p style="text-align: justify;">Dear {{ $user_name }},</p></b>

                   <p style="text-align: justify;">Greetings from Easy2Hire!</p>

                   <p style="text-align: justify;">At Adler, we care about giving our employees everything they need to perform their best. As you will soon see, we have prepared your workstation with all the necessary equipment, system & software.</p>

                    <p style="text-align: justify;">We’ve planned your first day to help you settle in properly. You can find more details in the enclosed agenda. As you will see, you’ll have plenty of time to read and complete your employment paperwork (HR will be there to help you during this process!) You will also meet with your <b>{{ $reporting_manager_name }}</b> to discuss your first steps. For your first week, we have planned a few training videos/sessions to give you a better understanding of our company and operations.</p>

                    <u><p style="text-align: justify;">The action points for the coming 3 days are as follows:</p></u>

                    <p style="text-align: justify;">
                        <ul style="list-style-type:none;">
                            <b><li>1. Induction and Joining Formalities</li></b>
                        </ul>
                    </p>

                    <p style="text-align: justify;">HR will get in touch to brief you about the company policies, people and culture.</p>

                    <p style="text-align: justify;">
                        <ul style="list-style-type:none;">
                            <b><li>2. Self-Onboarding</li></b>
                        </ul>
                        <p style="text-align: justify;">
                            <ul style="list-style-type:none;margin-left: 15px;">
                                <li>a. Google Form - <a href="https://docs.google.com/forms/d/e/1FAIpQLSfUGT1-Z9ex5tzACTppPBs813GoosM0gxko19q8bMYuy_hhbw/viewform?usp=sf_link - Google Form link">https://docs.google.com/forms/d/e/1FAIpQLSfUGT1-Z9ex5tzACTppPBs813GoosM0gxko19q8bMYuy_hhbw/viewform?usp=sf_link - Google Form link</a></li>
                                <li>b. E2H - <a href="{{getenv('APP_URL').'/users/myprofile/'.$module_id }}">{{getenv('APP_URL').'/users/myprofile/'.$module_id }}</a></li>
                            </ul>
                        </p>
                    </p>

                    <b><p style="text-align: justify;">Note: You are expected to fill in relevant data and submit required documents in both forms and share them.</p></b>

                    <p style="text-align: justify;">
                        <ul style="list-style-type:none;">
                            <b><li>3. Company Policies</li></b>
                        </ul>
                    </p>

                    <p style="text-align: justify;">You can freely access the company policies as and when required using this link: <a href="https://adler.easy2hire.in/process">https://adler.easy2hire.in/process</a>. After going through the same, you will be required to send an acknowledgement email to HR (hr@adlertalent.com) marking a copy to Vibhuti (vibhuti@adlertalent.com) and Raj (rajlalwani@adlertalent.com) stating you have gone through the policies and will abide by them.</p>

                    <p style="text-align: justify;">
                        <ul style="list-style-type:none;">
                            <b><li>4. KRA Process</li></b>
                        </ul>
                    </p>

                    <p style="text-align: justify;">Keeping Transparency as our Core value in mind, we have devised a KRA system wherein not only the company but the individual also gets clarity on what is expected out of them on a monthly basis. Your KRA implementation will be closed during this week by your respective Reporting Manager along with the HR. (Achieving higher KRAs will lead to better growth opportunities!)</p>

                    <b><p style="text-align: justify;">Note:In case of delay in the KRA implementation discussion, you can reach out to your Reporting Manager via email marking CC to HR, Vibhuti and Raj ensuring you understand your role and its expectations.</p></b>

                    <p style="text-align: justify;">
                        <ul style="list-style-type:none;">
                            <b><li>5. Training Videos</li></b>
                        </ul>
                    </p>

                    <p style="text-align: justify;">You can access relevant training material from Easy2Hire using the following link: <a href="https://adler.easy2hire.in/training">https://adler.easy2hire.in/training</a>. After every training material you go through, we expect a <b>‘take-away’ email</b> from your end enlisting what you understood and/or any queries. You can consolidate your take-aways in one email if going through multiple contents at the same time.</p>

                    <p style="text-align: justify;">
                        <ul style="list-style-type:none;">
                            <b><li>6. Communication Channel</li></b>
                        </ul>
                    </p>

                    <p style="text-align: justify;">We encourage our team members to converse internally via <b>Slack</b>, allowing everyone to interact and know the team better. Post completion of 30 days from your DOJ, HR will be adding you to our official WhatsApp group.</p>

                    <p style="text-align: justify;">While we have tried our best to make your initial agendas very crisp and clear, in case of further doubts</p>

                    <p style="text-align: justify;">
                        <ul style="list-style-type:none;">
                            <b><li>a. Admin</li></b>
                        </ul>
                        <p style="text-align: justify;">
                            <ul style="list-style-type:none;margin-left: 15px;">
                                <li>i. Contact Details - 7048543688</li>
                                <li>ii. Email ID - ops1@adlertalent.com</li>
                            </ul>
                        </p>
                    </p>

                    <p style="text-align: justify;">
                        <ul style="list-style-type:none;">
                            <b><li>b. Accounts</li></b>
                        </ul>
                        <p style="text-align: justify;">
                            <ul style="list-style-type:none;margin-left: 15px;">
                                <li>i. Contact Details - 9104300496</li>
                                <li>ii. Email ID - accounts@adlertalent.com</li>
                            </ul>
                        </p>
                    </p>

                    <p style="text-align: justify;">Our team is excited to meet you and looks forward to introducing themselves during lunchtime!</p>

                    <i><p style="text-align: justify;color:#a726c5;font-weight:bold;"><b>Welcome aboard the Adler Team!</b></p></i>

                    <p style="text-align: justify;">Thanks.<br/>Easy2Hire Team</p>
                </td>
            </tr>
        </table>
    </body>
</html>