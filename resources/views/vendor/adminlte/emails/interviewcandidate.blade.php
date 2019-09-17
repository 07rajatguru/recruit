<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>Adler Talent</title>

    @yield('style')
</head>

<body style="margin: 0; padding-top: 30px; background-color: #f5f5f5;">

<body>
<table border="0" cellpadding="0" cellspacing="0" width="600" style="font-family:Helvetica,Arial,sans-serif; border-collapse: collapse; color: #444444;" align="center">
    <tr>
        <td width="600" >
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
        <td width="600"  style="background-color: green; !important;">
            <table width="100%" cellpadding="0" cellspacing="0" style="border:0; background-color: #ffffff; padding: 50px 54px;">
                <tr style="font-family:Cambria, serif;font-size: 11.0pt;">
                    <td style="font-family:Cambria, serif;font-size: 11.0pt;">
                        <b><p style="margin-top: 0px; margin-bottom: 14px;">
                        Dear {{$cname}},</p></b>
                        <i><p>Greetings from Adler Talent Solutions !! </p></i>
                        <p style="text-align: justify;">
                           <!--  Adler Talent Solutions Private Limited is one of the fastest growing company into Talent consulting business catering services to Top Corporates and MNCs.We are offering quality services into Executive Search and talent solutions across INDIA and Overseas. We cater talent solution services in industries like Infrastructure, Oil and Gas, EPC Projects, Port and SEZ, Manufacturing, Engineering, Automobile, Auto Ancillary, Construction,Real Estate, Telecom, Textile,Pharmaceutical, Logistic, Consumer Durable, FMCG, Retail, Hospitality, Media, Chemicals, Information Technology and service industries. -->
                           @if(isset($company_desc) && $company_desc != '')
                                {{ $company_desc }}
                           @endif
                            <br/><br/>For more details about our company, please visit : <a href="https://adlertalent.com/">www.adlertalent.com</a>
                        </p>
                        <p><b><u> As per our telephonic conversation, please find the interview Details below:</u></b></p>
                        <p><b>Company Name : </b>{{ $company_name }}</p>
                        <p><b>Company URL: </b><a href="{{ $company_url }}">{{ $company_url }}</a></p>
                        <p><b>About Client : </b></p>
                        <p>{!! $client_desc !!}</p>
                        <p><b>Job Designation :</b> {{ $job_designation }}</p>
                        <p><b>Job Location :</b> {{ $job_location }}</p>
                        <p><b>Job Description :</b></p>
                        <p> {!! $job_description !!}</p>
                        <p><b>Interview Date/Day : </b> {{date('jS F,y (l)',strtotime($interview_date)) }}</p>
                        <p><b>Interview Time : </b> {{date('h:i A',strtotime($interview_time))  }}</p>
                        <p><b>Interview Venue : </b> {{ $interview_location }}</p>
                        <p><b>Contact Person : </b>{{$contact_person}}</p>
                        <p style="color:black;"><u>Please carry following documents at the time of interview:</u><br/><br/>
                        1. 2 Passport Size Photographs<br/>
                        2. Updated CV (Mandatory)<br/>
                        3. Application Form<br/>
                        4. Copy of 3 Month Salary Slips<br/>
                        5. Copy of Appointment / Increment letter whichever has latest salary breakup.<br/></p>
                        <p style="color:red;">Request you to acknowledge the receipt of this mail.</u></p>
                        <p>For any query/discussion, feel free to connect with me anytime.</p>
                        <p>Thanks.</p>
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