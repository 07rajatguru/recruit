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
                <b><p style="text-align: left;">Dear {{ $client_owner }},</p></b>
                <i><p style="text-align: left;">Greetings from Adler Talent Solutions !</p></i>
                <p style="text-align: left;"><u>Please find current job openings report as below for your reference:</u></p>
                    <table width="600" cellpadding="3" cellspacing="0" border="1" border-color="#000000">
                        <tr style="background-color: #7598d9;font-family:Cambria, serif;font-size: 11.0pt;">
                            <td align="left"><b>Sr. No.</b></td>
                            <td align="left"><b>Client Owner</b></td>
                            <td align="left"><b>Position Name</b></td>
                            <td align="left"><b>Candidates Associated</b></td>
                            <td align="left"><b>Shortlisted Names</b></td>
                            <td align="left"><b>Interview Attended Names</b></td>
                            <td align="left"><b>Remarks</b></td>
                        </tr>

                        <?php $i=0; ?>
                        @foreach($list_array as $key => $value)
                            <tr style="font-family:Cambria, serif;font-size: 11.0pt;">
                                
                                @if(isset($value['associate_candidates']) && sizeof($value['associate_candidates']) > 0)
                                    <?php
                                        $ass_names_string = '';
                                    ?>
                                    @foreach($value['associate_candidates'] as $k1 => $v1)
                                        <?php 
                                            if($ass_names_string == '') {
                                                $ass_names_string = $v1['candidate_name'];
                                            }
                                            else {
                                                $ass_names_string .= " , " . $v1['candidate_name'];
                                            }
                                        ?>
                                    @endforeach
                                @else
                                    <?php
                                        $ass_names_string = '';
                                    ?>
                                @endif

                                @if(isset($value['shortlisted_candidates']) && sizeof($value['shortlisted_candidates']) > 0)
                                    <?php
                                        $short_names_string = '';
                                    ?>
                                    @foreach($value['shortlisted_candidates'] as $k2 => $v2)
                                        <?php 
                                            if($short_names_string == '') {
                                                $short_names_string = $v2['candidate_name'];
                                            }
                                            else {
                                                $short_names_string .= " , " . $v2['candidate_name'];
                                            }
                                        ?>
                                    @endforeach
                                @else
                                    <?php
                                        $short_names_string = '';
                                    ?>
                                @endif

                                @if(isset($value['attended_interviews']) && sizeof($value['attended_interviews']) > 0)
                                    <?php
                                        $attend_names_string = '';
                                    ?>
                                    @foreach($value['attended_interviews'] as $k3 => $v3)
                                        <?php 
                                            if($attend_names_string == '') {
                                                $attend_names_string = $v3['candidate_name'];
                                            }
                                            else {
                                                $attend_names_string .= " , " . $v3['candidate_name'];
                                            }
                                        ?>
                                    @endforeach
                                @else
                                    <?php
                                        $attend_names_string = '';
                                    ?>
                                @endif

                                <td align="left">{{ ++$i }}</td>
                                <td align="left">{{ $client_owner_short_name }}</td>
                                <td align="left">{{ $value['posting_title'] }}</td>
                                <td align="left">{{ $ass_names_string }}</td>
                                <td align="left">{{ $short_names_string }}</td>
                                <td align="left">{{ $attend_names_string }}</td>
                                <td align="left"></td>
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