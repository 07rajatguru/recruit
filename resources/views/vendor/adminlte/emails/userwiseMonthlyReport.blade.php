<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>Adler Talent Solutions Pvt. Ltd.</title>
    @yield('style')
</head>

<style>

</style>
<body style="margin: 0; padding-top: 30px; background-color: #f5f5f5;">

<table align="center" width="600px" cellpadding="0" cellspacing="0" style="font-family: arial; font-size: 12px; color: #444444;">
    <tr>
        <td>
            <table width="100%" cellpadding="0" cellspacing="0" style="border:0;height: 70px;">
                <tr>
                    <td align="center">
                        <div class="site-branding col-md-2 col-sm-6 col-xs-12" >
                            {{--<a href="http://adlertalent.com/" title="Adler Talent Solutions Pvt. Ltd." style="font-size: 22px;text-decoration:none">        <img class="site-logo" width="60px" height="80px" src="http://adlertalent.com/wp-content/uploads/2016/06/logo.png" alt="Adler Talent Solutions Pvt. Ltd." style=" padding-top: 16px;   vertical-align: middle;"> <span>Adler Talent Solutions Pvt. Ltd.</span> </a>--}}
                            <a href="http://adlertalent.com/" title="Adler Talent Solutions Pvt. Ltd." style="font-size: 22px;text-decoration:none">
                                <img width="800" class="site-logo"  src="{{$app_url}}/images/Adler-Header.jpg" alt="Adler Talent Solutions Pvt. Ltd." style="width:100%;height: 150px;padding-top: 16px;   vertical-align: middle;">
                            </a>
                        </div>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td width="800">
            <table border="1" cellpadding="0" cellspacing="0" style="text-align: center;"  id="userwise-monthly-report">
                <thead>
                <tr style="font-weight: bold;">

                    <td style="background-color: #FA8258">Sr. No.</td>
                    <td style="background-color: #FA8258" width="20%">User</td>

                    @if(isset($user_details->cv_report) && $user_details->cv_report == 'Yes')
                        <td style="background-color: #F7D358;">No. of Cvs Associated</td>
                        <td style="background-color: #F7D358;">Benchmarks of cvs</td>
                        <td style="background-color: #F7D358;">Benchmarks not achieved in cvs</td>
                    @endif

                    @if(isset($user_details->interview_report) && $user_details->interview_report == 'Yes')
                        <td style="background-color: #BDBDBD;">No. of Interviews Attended</td>
                        <td style="background-color: #BDBDBD;">Benchmarks of Interviews</td>
                        <td style="background-color: #BDBDBD;">Benchmarks not achieved in Interviews </td>
                    @endif

                    @if(isset($user_details->lead_report) && $user_details->lead_report == 'Yes')
                        <td style="background-color: #C4D79B;">No. of Leads Added</td>
                    @endif
                </tr>
                </thead>
                <tbody>
                    <?php $i=1; ?>
                    @if(isset($response) && sizeof($response) > 0)
                        @foreach($response as $k=>$v)
                            <tr>
                                <td>{!! $i !!}</td>
                                <td>{!! $v['uname'] !!}</td>

                                @if(isset($user_details->cv_report) && $user_details->cv_report == 'Yes')
                                    <td>{!! $v['cvs'] !!}</td>
                                    <td>150</td>
                                    <?php
                                    $not_ach = $v['cvs'] -150
                                    ?>
                                    @if($not_ach<0)
                                        <td style="color:red;">{!! $not_ach !!}</td>
                                    @else
                                        <td style="background-color:#92D050;">{!! $not_ach !!}</td>
                                    @endif
                                @endif

                                @if(isset($user_details->interview_report) && $user_details->interview_report == 'Yes')
                                    <td>{!! $v['interviews'] !!}</td>
                                    <td>38</td>
                                    <?php
                                    $not_ach_in = $v['interviews'] - 38
                                    ?>
                                    @if($not_ach_in<0)
                                        <td style="color:red;">{!! $not_ach_in !!}</td>
                                    @else
                                        <td style="background-color:#92D050;">{!! $not_ach_in !!}</td>
                                    @endif
                                @endif

                                @if(isset($user_details->lead_report) && $user_details->lead_report == 'Yes')
                                    @if(isset($v['lead_count']) && $v['lead_count'] != '')
                                        <td>{!! $v['lead_count'] !!}</td>
                                    @else
                                        <td></td>
                                    @endif
                                @endif
                            </tr>
                            <?php $i++; ?>
                        @endforeach
                    @endif
                </tbody>
            </table>
            @if(isset($user_details->lead_report) && $user_details->lead_report == 'Yes')
                @if($total_leads > 0)
                    <table width="100%" cellspacing="0">
                        <tr>
                            <td colspan="7">
                                <u><b><h3>Lead Details : {{ $total_leads or '0'}}</h3></b></u>
                            </td>
                        </tr>
                    </table>

                    <div class = "table-responsive">
                        <table border="1" cellpadding="0" cellspacing="0" style="text-align: center;" width="75%" id="monthly_report_leads_table">
                            <thead>
                                <tr style="background-color: #C4D79B;">
                                    <th style="text-align: center;">Sr. No.</th>
                                    <th style="text-align: center;">Company Name</th>
                                    <th style="text-align: center;">Contact Point</th>
                                    <th style="text-align: center;">Designation</th>
                                    <th style="text-align: center;">Email ID</th>
                                    <th style="text-align: center;">Mobile No.</th>
                                    <th style="text-align: center;">City</th>
                                    <th style="text-align: center;">Website</th>
                                    <th style="text-align: center;">Service</th>
                                    <th style="text-align: center;">Lead Status</th>
                                    <th style="text-align: center;">Source</th>
                                </tr>
                            </thead>
                            <?php $i=0;?>
                            <tbody>
                                @foreach($response as $key => $value)
                                    @if(isset($value['leads_data']) && $value['leads_data'] != '' && $value['leads_data']!='' && $value['leads_data']!=0)
                                        @foreach($value['leads_data'] as $k1 => $v1)
                                            <tr style="text-align: center;">
                                                <td>{{ ++$i }}</td>
                                                <td>{{ $v1['company_name'] }}</td>
                                                <td>{{ $v1['contact_point'] }}</td>
                                                <td>{{ $v1['designation'] }}</td>
                                                <td>{{ $v1['email'] }}</td>
                                                <td>{{ $v1['mobile'] }}</td>
                                                <td>{{ $v1['city'] }}</td>
                                                <td>{{ $v1['website'] }}</td>
                                                <td>{{ $v1['service'] }}</td>
                                                <td>{{ $v1['lead_status'] }}</td>
                                                <td>{{ $v1['source'] }}</td>
                                            </tr>
                                        @endforeach
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            @endif
        </td>
    </tr>
    <br/><br/><tr style="height: 45px; background-color: #dddddd;">
        <td width="800">
            <table width="100%" cellpadding="0" cellspacing="0" style="border:0;height: 70px;">
                <tr >
                    <td style="text-align: center; font-size: 11px; color: #888888; font-family: arial;">Copyright Adler Talent <?php echo date('Y'); ?>. All rights reserved.</td>
                </tr>
            </table>
        </td>
    </tr>
</table>
</body>
</html>