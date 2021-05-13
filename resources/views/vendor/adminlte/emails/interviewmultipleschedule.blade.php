<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <title>Adler Talent</title>
        @yield('style')
    </head>

<body style="margin: 0; padding-top: 30px;">
    <table border="0" cellpadding="0" cellspacing="0" width="600" style="font-family:Helvetica,Arial,sans-serif;" align="left">
        <tr>
            <td width="600" style="font-family:Cambria, serif;font-size: 11.0pt;">
                <b><p style="text-align: left;">Dear Sir,</p></b>
                <i><p style="text-align: left;">Greetings from Adler Talent Solutions !</p></i>
                <p style="text-align: left;"><u>Please find interview schedule as below for your kind reference:</u></p>
                    <table width="600" cellpadding="3" cellspacing="0" border="1" border-color="#000000">
                        <tr style="background-color: #7598d9;font-family:Cambria, serif;font-size: 11.0pt;">
                            <td align="left"><b>Sr. No.</b></td>
                            <td align="left"><b>Position</b></td>
                            <td align="left"><b>Position Location</b></td>
                            <td align="left"><b>Name of the Candidate</b></td>
                            <td align="left"><b>Interview Date</b></td>
                            <td align="left"><b>Interview Time</b></td>
                            <td align="left"><b>Interview Location</b></td>
                            <td align="left"><b>Candidate Location</b></td>
                            <td align="left"><b>Mode of Interview</b></td>

                            @if(isset($type_string) && $type_string != '')
                                @if(strpos($type_string,"Video") !== false)
                                    <td align="left"><b>Video ID</b></td>
                                @endif
                            @endif

                            <td align="left"><b>Contact No.</b></td>
                            <td align="left"><b>Email ID</b></td>
                            <td align="left"><b>Confirmed</b></td>
                            <td align="left"><b>Source</b></td>

                            @if(isset($yesterday_date) && $yesterday_date != '')
                                <td align="left"><b>Action</b></td>
                            @endif
                        </tr>

                        <?php $i=0; ?>
                        @foreach($interview_details as $key => $value)
                            <tr style="font-family:Cambria, serif;font-size: 11.0pt;">
                                <td align="left">{{ ++$i }}</td>
                                <td align="left">{{$value['client_name']}} - {{$value['job_designation']}}</td>
                                <td align="left">{{$value['job_location']}}</td>
                                <td align="left">{{$value['cname']}}</td>
                                <td align="left">{{date('d/m/Y',strtotime($value['interview_date'])) }}</td>
                                <td align="left">{{date('h:i A',strtotime($value['interview_time']))  }}</td>
                                <td align="left">{{$value['interview_location']}}</td>
                                <td align="left">{{$value['candidate_location']}}</td>
                                <td align="left">{{$value['interview_type']}}</td>

                                @if(isset($type_string) && $type_string != '')
                                    @if(strpos($type_string,"Video") !== false)
                                        <td align="left">{{$value['skype_id']}}</td>
                                    @endif
                                @endif
                                
                                <td align="left">{{$value['cmobile']}}</td>
                                <td align="left">{{$value['cemail']}}</td>
                                <td align="left"><b>{{'Yes'}}</b></td>
                                <td align="left"><b>{{'Adler'}}</b></td>

                                @if(isset($yesterday_date) && $yesterday_date != '')
                                    <td align="left">
                                        <a formtarget="_blank" href="{{getenv('APP_URL').'/interview/'.$value['id'].'/edit/index'}}">Edit</a>
                                    </td>
                                @endif
                            </tr>
                        @endforeach
                    </table>
                <p style="font-family:Cambria, serif;font-size: 11.0pt;text-align: left;">Hope this works.</p>
                <p style="font-family:Cambria, serif;font-size: 11.0pt;text-align: left;">Thanks.</p>
            </td>
        </tr>
    </table>
</body>
</html>