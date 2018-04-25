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
                        <b><p style="margin-top: 0px; margin-bottom: 14px; font-family: arial;">Dear Sir,</p></b>
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
                        <p><u> Please find below Interview Schedule:</u></p>
                    </td>
                </tr>
            </table>
            <table cellpadding="0" cellspacing="0" border="1" border-color="#000000">
                <tr style="background-color: #66B2FF">
                    <td align="center"><b>Sr.<br/>No.</b></td>
                    <td align="center" width="100%"><b>Position</b></td>
                    <td align="center"><b>Position Location</b></td>
                    <td align="center"><b>Name of the Candidate</b></td>
                    <td align="center"><b>Interview Date</b></td>
                    <td align="center"><b>Interview Time</b></td>
                    <td align="center"><b>Candidate Location</b></td>
                    <td align="center"><b>Mode of Interview</b></td>
                    <td align="center"><b>Skype ID</b></td>
                    <td align="center"><b>Contact No.</b></td>
                    <td align="center"><b>Email ID</b></td>
                    <td align="center"><b>Confirmed</b></td>
                    <td align="center"><b>Source</b></td>
                </tr>
                <?php $i=0; ?>
                <tr>
                    <td align="center">{{ ++$i }}</td>
                    <td align="center">{{$job_designation}}</td>
                    <td align="center">{{$job_location}}</td>
                    <td align="center">{{$cname}}</td>
                    <td align="center">{{date('jS F,y',strtotime($interview_date)) }}</td>
                    <td align="center">{{date('h:i A',strtotime($interview_time))  }}</td>
                    <td align="center">{{$ccity}}</td>
                    <td align="center">{{$interview_type}}</td>
                    <td align="center">{{''}}</td>
                    <td align="center">{{$cmobile}}</td>
                    <td align="center">{{$cemail}}</td>
                    <td align="center"><b>{{'Yes'}}</b></td>
                    <td align="center"><b>{{'Adler'}}</b></td>
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