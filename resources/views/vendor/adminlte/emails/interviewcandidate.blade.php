<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <title>Adler Talent</title>
        @yield('style')
    </head>

    <body style="margin: 0; padding-top: 30px; background-color: #f5f5f5;">
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
                <td width="600"  style="background-color: green; !important;">
                    <table width="100%" cellpadding="0" cellspacing="0" style="border:0; background-color: #ffffff;">
                        <tr style="font-family:Cambria, serif;font-size: 11.0pt;">
                            <td style="font-family:Cambria, serif;font-size: 11.0pt;">
                                <b><p style="margin-top: 0px; margin-bottom: 14px;">
                                Dear {{$cname}},</p></b>
                                <i><p>Greetings from Adler Talent Solutions !! </p></i>
                                <p style="text-align: justify;">

                                    @if(isset($company_desc) && $company_desc != '')
                                        {!! $company_desc !!}
                                    @endif
                                    <!-- <br/><br/>For more details about our company, please visit : <a href="https://adlertalent.com/">www.adlertalent.com</a> -->
                                </p>
                                <p><b><u> As per our telephonic conversation, please find the interview Details below :</u></b></p>
                                <p><b>Company Name : </b>{{ $company_name }}</p>
                                <p><b>Company URL : </b><a href="{{ $company_url }}">{{ $company_url }}</a></p>
                                <p><b>About Client : </b></p>
                                <p>{!! $client_desc !!}</p>
                                <p><b>Job Designation :</b> {{ $job_designation }}</p>
                                <p><b>Job Location :</b> {{ $job_location }}</p>
                                <p><b>Job Description :</b></p>
                                <p> {!! $job_description !!}</p>
                                <p><b>Interview Date/Day : </b> {{date('jS F,y (l)',strtotime($interview_date)) }}</p>
                                <p><b>Interview Time : </b> {{date('h:i A',strtotime($interview_time))  }}</p>

                                @if(isset($interview_type) && $interview_type == 'Personal Interview')
                                    <p><b>Interview Venue : </b> {{ $interview_location }}</p>
                                @endif
                                
                                <p><b>Contact Person : </b>{{$contact_person}}</p>

                                @if(isset($interview_type) && $interview_type == 'Personal Interview')
                                    <p style="color:black;"><u>Please carry following documents at the time of interview:</u><br/><br/>
                                    1. 2 Passport Size Photographs<br/>
                                    2. Updated CV (Mandatory)<br/>
                                    3. Application Form<br/>
                                    4. Copy of 3 Month Salary Slips<br/>
                                    5. Copy of Appointment / Increment letter whichever has latest salary breakup.<br/></p>
                                @endif

                                @if((isset($interview_type) && $interview_type == 'Personal Interview') || (isset($interview_type) && $interview_type == 'Telephonic Interview'))

                                @else

                                    <p style="color:black;">
                                        <u><b>Important Notes for Video Interview:</b></u><br/><br/>

                                        <ul style="list-style-type: square;">
                                            
                                            <li>Must attend through laptop ensuring proper background and lighting <b>(Attending interview through Mobile must be avoided).</b></li>

                                            <li>Test your technology - Check one day prior that your webcam, audio and video application is working properly.</li>

                                            <li>Set your phone to silent.</li>

                                            <li>Set up your camera so that your face is nicely framed.</li>

                                            <li>Maintain good eye contact and body language.</li>

                                            <li>Find a quiet, private, well-lit place, free from possible interruptions with high speed internet connectivity.</li>

                                            <li>Dress for Success - Be absolutely well-dressed in business formals for your own confidence and to impress the interviewer.</li>
                                        </ul>
                                    </p>
                                @endif

                                <p style="color:red;">Request you to acknowledge the receipt of this mail.</p>
                                <p>For any query/discussion, feel free to connect with me anytime.</p>
                                <p>Thanks.</p>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td width="600">
                    <table width="100%" cellpadding="0" cellspacing="0">
                        <tr style="height: 45px; background-color: #dddddd;">
                            <td style="text-align: center; font-size: 11px; color: #888888; font-family: arial;">Copyright Adler Talent <?php echo date('Y'); ?>. All rights reserved</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </body>
</html>