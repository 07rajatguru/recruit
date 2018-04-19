<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>Adler Talent</title>

    @yield('style')
</head>

<body style="margin: 0; padding-top: 30px; background-color: #f5f5f5;">

<table align="center" width="600px" cellpadding="0" cellspacing="0" style="font-family: arial; font-size: 12px; color: #444444;">
    <tr>
        <td>
            <table width="100%" cellpadding="0" cellspacing="0" style="border:0; background-color: rgba(157,92,172,0.9); height: 70px;">
                <tr>
                    <td align="center">
                        Adler Talent Solutions Pvt. Ltd.
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td>
            <table width="100%" cellpadding="0" cellspacing="0" style="border:0; background-color: #ffffff; padding: 50px 54px;">
                <tr>
                    <td>
                        <b><p style="margin-top: 0px; margin-bottom: 14px; font-family: arial;">Dear,{{$cname}}</p></b>
                    </td>
                </tr>
                <tr>
                    <td>
                        <i><p>Greetings from Adler Talent Solutions !! </p></i>
                    </td>
                </tr>
                <tr>
                    <td><p style="text-align: justify;">
                        Adler Talent Solutions Private Limited is one of the fastest growing company into Talent consulting business catering services to Top Corporates and MNCs.We are offering quality services into Executive Search and talent solutions across INDIA and Overseas. We cater talent solution services in industries like Infrastructure, Oil and Gas, EPC Projects, Port and SEZ, Manufacturing, Engineering, Automobile, Auto Ancillary, Construction,Real Estate, Telecom, Textile,Pharmaceutical, Logistic, Consumer Durable, FMCG, Retail, Hospitality, Media, Chemicals, Information Technology and service industries. For more details about our company, please visit : <a href="https://adlertalent.com/">www.adlertalent.com</a>
                     </p>
                        <p><u> As per our telephonic conversation please find the interview details below:</u></p>
                    </td>
                </tr>
                <tr>
                    <td>
                        <p><b>Company Name : </b>{{ $company_name }}</p>
                        <p><b>Company URL: </b>{{ $company_url }}</p>
                        <p><b>About Client : </b></p>
                        <p>{{ $client_desc }}</p>
                    </td>
                </tr>
                <tr>
                    <td>
                        <p><b>Job Designation :</b> {{ $job_designation }}</p>
                        <p><b>Job Location :</b> {{ $job_location }}</p>
                        <p><b>Job Description :</b></p>
                        <p> {{ $job_description }}</p>
                    </td>
                </tr>
                <tr>
                    <td>
                        <p><b>Interview Date/Day : </b> {{date('jS F,y',strtotime($interview_date)) }} ({{ date('l',strtotime($interview_day)) }})</p>
                        <p><b>Interview Time : </b> {{date('h:i A',strtotime($interview_time))  }}</p>
                        <p><b>Interview Venue : </b></p>
                        <p>{{ $interview_location }}</p>
                    </td>
                </tr>
                <tr>
                    <td>
                        <p><u>Please carry a copy of your updated resume at the time of interview.Request you to acknowledge the receipt of this mail.</u></p>
                        <p>For any query/discussion, feel free to connect with me anytime.</p>
                        <p>Thanks</p>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr style="height: 45px; background-color: #dddddd;">
        <td style="text-align: center; font-size: 11px; color: #888888; font-family: arial;">Copyright Adler Talent <?php echo date('Y'); ?>. All rights reserved</td>
    </tr>
</table>



</body>
</html>