<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>Adler Talent</title>

    @yield('style')

    <style type="text/css">
        tr { background-color: white; }
        td { background-color: white; }
        th { background-color: white; }
        p {
            padding-left: 200px;
            font-size: 14px;
        }
        table, th, td {
            height: 30px;
            border: 2px solid black;
            border-collapse: collapse;
        }
        button {
            position: absolute;
            padding: 5px 20px;
            background-color: DarkOrchid;
            color: white;
            border:none;
            font-size: 14px;
            border-radius:50px;
        }
        div {
            padding: 13px;
            background-color: WhiteSmoke;
        }
        body {
            background-color: WhiteSmoke;
        }
    </style>

    </head>
    <body>
        <div>
            <p style="font-family:Cambria, serif;font-size: 11.0pt;">Dear {{ $rm_name }},</p>
            <p style="font-family:Cambria, serif;font-size: 11.0pt;">Please find the Daily Activity Report as below:</p>

            <center>
                <table style="width: 60%;">
                    <thead>
                        <tr>
                            <th  width="7%" style="background-color: none;font-size: 9.0pt; ">Sr No</th>
                            <th  width="20%" style="background-color: none;font-size: 9.0pt; ">Employee Name</th>
                            <th  width="20%" style="background-color: none;font-size: 9.0pt; ">CVs Added</th>
                            <th  width="20%" style="background-color: none;font-size: 9.0pt; ">Interviews Scheduled</th>
                            <th  width="15%" style="background-color: none;font-size: 9.0pt; ">Leads Added</th>
                            <th  width="17%" style="background-color: none;font-size: 9.0pt; ">Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php $i=0; ?>
                        @if(isset($data) && sizeof($data)>0)
                            @foreach($data as $kd => $vd)
                            <?php 
                                // Set the background color based on the count conditions
                                $associate_count = isset($vd['associate_count']) ? $vd['associate_count'] : 0;
                                $total_interview_cnt = isset($vd['interview_daily']) ? sizeof($vd['interview_daily']) : 0;
                                $leads_count = isset($vd['leads_count']) ? $vd['leads_count'] : 0;

                                $bg_colors = [
                                    'associate' => '',
                                    'interview' => '',
                                    'leads' => ''
                                ];

                                // Set the background color for associate column
                                if($associate_count >= 7) {
                                    $bg_colors['associate'] = '#b6d7a8'; // Light green
                                } elseif($associate_count >= 4 && $associate_count <= 6) {
                                    $bg_colors['associate'] = '#ffd966'; // Light yellow
                                } else {
                                    $bg_colors['associate'] = '#ea9999'; // Light red
                                }

                                // Set the background color for interview column
                                if($total_interview_cnt >= 7) {
                                    $bg_colors['interview'] = '#b6d7a8'; // Light green
                                } elseif($total_interview_cnt >= 4 && $total_interview_cnt <= 6) {
                                    $bg_colors['interview'] = '#ffd966'; // Light yellow
                                } else {
                                    $bg_colors['interview'] = '#ea9999'; // Light red
                                }

                                // Set the background color for leads column
                                if($leads_count >= 7) {
                                    $bg_colors['leads'] = '#b6d7a8'; // Light green
                                } elseif($leads_count >= 4 && $leads_count <= 6) {
                                    $bg_colors['leads'] = '#ffd966'; // Light yellow
                                } else {
                                    $bg_colors['leads'] = '#ea9999'; // Light red
                                }
                             ?>
                            <tr style="font-family:Cambria, serif;font-size: 11.0pt;">
                                <td align="center">{{ ++$i }}</td>
                                <td align="center">{{ $kd }}</td>
                                @if(isset($vd['user_details']->cv_report) && $vd['user_details']->cv_report == 'Yes')
                                    <td align="center" style="background-color:{{$bg_colors['associate']}};">{{$vd['associate_count'] or '0'}}</td>
                                @else
                                    <td align="center" style="background-color:{{$bg_colors['associate']}};">0</td>
                                @endif

                                @if(isset($vd['user_details']->interview_report) && $vd['user_details']->interview_report == 'Yes')
                                    <?php $total_interview_cnt = sizeof($vd['interview_daily']); ?>
                                    <td align="center" style="background-color:{{$bg_colors['interview']}};"> {{$total_interview_cnt or '0'}}</td>
                                @else
                                    <td align="center" style="background-color:{{$bg_colors['interview']}};">0</td>
                                @endif

                                @if(isset($vd['user_details']->lead_report) && $vd['user_details']->lead_report == 'Yes')
                                    <td align="center" style="background-color:{{$bg_colors['leads']}};"> {{$vd['leads_count'] or '0'}}</td>
                                @else
                                    <td align="center" style="background-color:{{$bg_colors['leads']}};">0</td>
                                @endif

                                <td align="center"><button><a  style="color: white;" href="{{getenv('APP_URL').'/daily-report/' . \Crypt::encrypt($vd['user_details']->id) }}">Show</a></button></td>
                            </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </center>

            <p style="font-family:Cambria, serif;font-size: 11.0pt;">Request you to connect with your Team Members and understand the further plan of actions accordingly.</p>
            <p style="font-family:Cambria, serif;font-size: 11.0pt;">Thanks.</p>
            <p style="font-family:Cambria, serif;font-size: 11.0pt;">E2H Team</p>

        </div>
    </body>
</html>