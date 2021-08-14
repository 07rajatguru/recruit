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
            <td width="600"  style="background-color: green; !important;">
                <table width="100%" cellpadding="0" cellspacing="0" style="border:0; background-color: #ffffff; padding: 50px 54px;">
                    <tr style="font-family:Cambria, serif;font-size: 11.0pt;">
                        <td style="font-family:Cambria, serif;font-size: 11.0pt;">
                            <b><p style="margin-top: 0px; margin-bottom: 14px;">
                            Dear {{$candidate_name}},</p></b>
                            <i><p>Greetings from Adler Talent Solutions !! </p></i>
                            <p style="text-align: justify;">
     
                               @if(isset($company_desc) && $company_desc != '')
                                    {!! $company_desc !!}
                               @endif
                               
                            </p>
                            <p><b><u> As per our telephonic conversation, please find the details below:</u></b></p>
                            <p><b>Company Name : </b>{{ $company_name }}</p>
                            <p><b>Company URL: </b><a href="{{ $company_url }}">{{ $company_url }}</a></p>
                            <p><b>About Client : </b></p>
                            <p>{!! $client_desc !!}</p>
                            <p><b>Job Designation :</b> {{ $job_designation }}</p>
                            <p><b>Job Location :</b> {{ $job_location }}</p>
                            <p><b>Job Description :</b></p>
                            <p> {!! $job_description !!}</p>
                           
                            <p style="color:red;"><b>Please note that we have shared your resume to the client and will get back to you for further process.</b></p>
                            <p><b>For any query/discussion, feel free to connect with me anytime.</b></p>
                            <p><b>Thanks.</b></p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>