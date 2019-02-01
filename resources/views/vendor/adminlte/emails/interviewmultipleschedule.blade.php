<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>Adler Talent</title>

    @yield('style')
</head>

<body>
<table border="0" cellpadding="0" cellspacing="0" width="600" style="font-family:Cambria; border-collapse: collapse; color: #444444;padding: 50px 54px;border:0" align="center">
    <tr>
        <td width="600">
            <b><p>Dear Sir,</p></b>
            <i><p>Greetings from Adler Talent Solutions !</p></i>
            <p><u>Please find interview schedule as below for your kind reference:</u></p>
            <p>
            <table width="580" cellpadding="3" cellspacing="0" border="1" border-color="#000000">
                <tr style="background-color: #7598d9">
                    <td align="center"style="font-family: Cambria;"><b>Sr.<br/>No.</b></td>
                    <td align="center"style="font-family: Cambria;"><b>Position</b></td>
                    <td align="center"style="font-family: Cambria;"><b>Position Location</b></td>
                    <td align="center"style="font-family: Cambria;"><b>Name of the Candidate</b></td>
                    <td align="center"style="font-family: Cambria;"><b>Interview Date</b></td>
                    <td align="center"style="font-family: Cambria;"><b>Interview Time</b></td>
                    <td align="center"style="font-family: Cambria;"><b>Candidate Location</b></td>
                    <td align="center"style="font-family: Cambria;"><b>Mode of Interview</b></td>
                    <td align="center"style="font-family: Cambria;"><b>Skype ID</b></td>
                    <td align="center"style="font-family: Cambria;"><b>Contact No.</b></td>
                    <td align="center"style="font-family: Cambria;"><b>Email ID</b></td>
                    <td align="center"style="font-family: Cambria;"><b>Confirmed</b></td>
                    <td align="center"style="font-family: Cambria;"><b>Source</b></td>
                </tr>
                <?php $i=0; ?>
                @foreach($interview_details as $key => $value)
                    <tr>
                        <td align="center"style="font-family: Cambria;">{{ ++$i }}</td>
                        <td align="center"style="font-family: Cambria;">{{$value['job_designation']}}</td>
                        <td align="center"style="font-family: Cambria;">{{$value['job_location']}}</td>
                        <td align="center"style="font-family: Cambria;">{{$value['cname']}}</td>
                        <td align="center"style="font-family: Cambria;">{{date('d/m/Y',strtotime($value['interview_date'])) }}</td>
                        <td align="center"style="font-family: Cambria;">{{date('h:i A',strtotime($value['interview_time']))  }}</td>
                        <td align="center"style="font-family: Cambria;">{{$value['candidate_location']}}</td>
                        <td align="center"style="font-family: Cambria;">{{$value['interview_type']}}</td>
                        <td align="center"style="font-family: Cambria;">{{$value['skype_id']}}</td>
                        <td align="center"style="font-family: Cambria;">{{$value['cmobile']}}</td>
                        <td align="center"style="font-family: Cambria;">{{$value['cemail']}}</td>
                        <td align="center"style="font-family: Cambria;"><b>{{'Yes'}}</b></td>
                        <td align="center"style="font-family: Cambria;"><b>{{'Adler'}}</b></td>
                    </tr>
                @endforeach
            </table>
            </p>
            <p>Hope this works.</p>
            <p>Thanks.</p>
        </td>
    </tr>
</table>
</body>
</html>