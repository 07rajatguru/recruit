<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>Adler Talent</title>

    @yield('style')
</head>

<body>
<table border="0" cellpadding="0" cellspacing="0" width="600" style="font-family:Helvetica,Arial,sans-serif; border-collapse: collapse; color: #444444;padding: 50px 54px;border:0" align="center">
    <tr>
        <td width="600">
            <b><p>Dear Sir,</p></b>
            <i><p>Greetings from Adler Talent Solutions !</p></i>
            <p><u>Please find interview schedule as below for your kind reference:</u></p>
            <p>
            <table width="580" cellpadding="3" cellspacing="0" border="1" border-color="#000000">
                <tr style="background-color: #7598d9">
                    <td align="center"><b>Sr.<br/>No.</b></td>
                    <td align="center"><b>Position</b></td>
                    <td align="center"><b>Position Location</b></td>
                    <td align="center"><b>Name of the Candidate</b></td>
                    <td align="center"><b>Interview Date</b></td>
                    <td align="center"><b>Interview Time</b></td>
                    <td align="center"><b>Interview Location</b></td>
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
                    <td align="center">{{date('d/m/Y',strtotime($interview_date)) }}</td>
                    <td align="center">{{date('h:i A',strtotime($interview_time))  }}</td>
                    <td align="center">{{$interview_location}}</td>
                    <td align="center">{{$candidate_location}}</td>
                    <td align="center">{{$interview_type}}</td>
                    <td align="center">{{$skype_id}}</td>
                    <td align="center">{{$cmobile}}</td>
                    <td align="center">{{$cemail}}</td>
                    <td align="center"><b>{{'Yes'}}</b></td>
                    <td align="center"><b>{{'Adler'}}</b></td>
                </tr>
            </table>
            </p>
            <p>Hope this works.</p>
            <p>Thanks.</p>
        </td>
    </tr>
</table>
</body>
</html>