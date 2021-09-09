<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <title>Adler Talent</title>
        @yield('style')
    </head>

<body style="margin: 0; padding-top: 30px;">
    <table border="0" cellpadding="0" cellspacing="0" style="font-family:Helvetica,Arial,sans-serif;" align="left">
        <tr>
            <td style="font-family:Cambria, serif;font-size: 11.0pt;">
                <b><p style="text-align: left;">Dear {{ $client_name }},</p></b>
                <i><p style="text-align: left;">Greetings from Adler Talent Solutions !</p></i>
                <p style="text-align: left;">Please find the hiring report below for your reference. Let me know convenient time to discuss this with you over phone.</p>
                    <table cellpadding="3" cellspacing="0" border="1" border-color="#000000">
                        <tr style="background-color: #7598d9;font-family:Cambria, serif;font-size: 11.0pt;">
                            <td align="center" width="5%"><b>Sr. No.</b></td>
                            <!-- <td align="center"><b>Client Owner</b></td> -->
                            <td align="center" width="15%"><b>Position Name</b></td>
                            <td align="center" width="14%"><b>CVs Shared</b></td>
                            <td align="center" width="15%"><b>Shortlisted Names</b></td>
                            <td align="center" width="15%"><b>Interview Attended Names</b></td>
                            <td align="center" width="18%"><b>Adler's Remarks</b></td>
                            <td align="center" width="18%"><b>Client's Remarks</b></td>
                        </tr>

                        <?php $i=0; ?>
                        @foreach($list_array as $key => $value)
                            <tr style="font-family:Cambria, serif;font-size: 11.0pt;">
                                
                                @if(isset($value['associate_candidates']) && sizeof($value['associate_candidates']) > 5)
                                    <?php 
                                        $ass_names_string = sizeof($value['associate_candidates']);
                                    ?>
                                @elseif(isset($value['associate_candidates']) && sizeof($value['associate_candidates']) > 0)
                                    <?php
                                        $ass_names_string = '';
                                        $ass_incr = 1;
                                    ?>
                                    @foreach($value['associate_candidates'] as $k1 => $v1)
                                        <?php 
                                            if($ass_names_string == '') {
                                                $ass_names_string = $ass_incr . " . " . $v1['candidate_name'] . " " . "<br/>";
                                            }
                                            else {
                                                $ass_names_string .= $ass_incr . " . " . $v1['candidate_name'] . " " . "<br/>";
                                            }

                                            $ass_incr++;
                                        ?>
                                    @endforeach
                                @else
                                    <?php
                                        $ass_names_string = '';
                                    ?>
                                @endif

                                @if(isset($value['shortlisted_candidates']) && sizeof($value['shortlisted_candidates']) > 5)
                                    <?php 
                                        $short_names_string = sizeof($value['shortlisted_candidates']);
                                    ?>
                                @elseif(isset($value['shortlisted_candidates']) && sizeof($value['shortlisted_candidates']) > 0)
                                    <?php
                                        $short_names_string = '';
                                        $short_incr = 1;
                                    ?>
                                    @foreach($value['shortlisted_candidates'] as $k2 => $v2)
                                        <?php 
                                            if($short_names_string == '') {
                                                $short_names_string = $short_incr . " . " . $v2['candidate_name'] . " " . "<br/>";
                                            }
                                            else {
                                                $short_names_string .= $short_incr . " . " . $v2['candidate_name'] . " " . "<br/>";
                                            }

                                            $short_incr++;
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
                                        $attend_incr = 1;
                                    ?>
                                    @foreach($value['attended_interviews'] as $k3 => $v3)
                                        <?php 
                                            if($attend_names_string == '') {
                                                $attend_names_string = $attend_incr . " . " . $v3['candidate_name'] . " " . "<br/>";
                                            }
                                            else {
                                                $attend_names_string .= $attend_incr . " . " . $v3['candidate_name'] . " " . "<br/>";
                                            }

                                            $attend_incr++;
                                        ?>
                                    @endforeach
                                @else
                                    <?php
                                        $attend_names_string = '';
                                    ?>
                                @endif

                                <td align="center">{{ ++$i }}</td>
                                <!-- <td align="center">{{ $client_owner }}</td> -->
                                <td align="center">{{ $value['posting_title'] }}</td>
                                <td align="center">{!! $ass_names_string !!}</td>
                                <td align="center">{!! $short_names_string !!}</td>
                                <td align="center">{!! $attend_names_string !!}</td>
                                <td align="center"></td>
                                <td align="center"></td>
                            </tr>
                        @endforeach
                    </table>
                    
                <p style="font-family:Cambria, serif;font-size: 11.0pt;text-align: left;">Also, let us know if you have any other priority roles for us to focus upon apart from above list.</p>
                <p style="font-family:Cambria, serif;font-size: 11.0pt;text-align: left;">Awaiting your revert.</p>
                <p style="font-family:Cambria, serif;font-size: 11.0pt;text-align: left;">Thanks.</p>
            </td>
        </tr>
    </table>
</body>
</html>