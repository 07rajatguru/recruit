<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>Adler Talent</title>

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
                                <img width="1000" class="site-logo"  src="{{$app_url}}/images/Adler-Header.jpg" alt="Adler Talent Solutions Pvt. Ltd." style="width:100%;height: 150px;padding-top: 16px;   vertical-align: middle;">
                            </a>
                        </div>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td>
            <table border="1" cellpadding="0" cellspacing="0" style="text-align: center;"  id="userwise-monthly-report">
                <thead>
                <tr style="font-weight: bold;">
                    <td style="background-color: #FA8258">Sr. No.</td>
                    <td style="background-color: #FA8258">User</td>
                    <td style="background-color: #F7D358;">No. of Cvs Associated</td>
                    <td style="background-color: #F7D358;">Benchmarks of cvs</td>
                    <td style="background-color: #F7D358;">Benchmarks not achieved in cvs </td>
                    <td style="background-color: #BDBDBD;">No. of Interviews Attended</td>
                    <td style="background-color: #BDBDBD;">Benchmarks of Interviews</td>
                    <td style="background-color: #BDBDBD;">Benchmarks not achieved in Interviews </td>
                </tr>
                </thead>
                <tbody>
                <?php $i=1; ?>
                @foreach($response as $k=>$v)
                    <tr>
                        <td>{!! $i !!}</td>
                        <td>{!! $v['uname'] !!}</td>
                        <td>{!! $v['cvs'] !!}</td>
                        <td>150</td>
                        <?php
                        $not_ach = $v['cvs'] -150
                        ?>
                        @if($not_ach<0)
                            <td style="color:red;">{!! $not_ach !!}</td>
                        @else
                            <td style="color:green;">{!! $not_ach !!}</td>
                        @endif
                        <td>{!! $v['interviews'] !!}</td>
                        <td>38</td>
                        <?php
                        $not_ach_in = $v['interviews'] - 38
                        ?>
                        @if($not_ach<0)
                            <td style="color:red;">{!! $not_ach_in !!}</td>
                        @else
                            <td style="color:green;">{!! $not_ach_in !!}</td>
                        @endif
                    </tr>
                    <?php $i++; ?>
                @endforeach
                </tbody>
            </table>
        </td>
    </tr>
    <tr style="height: 45px; background-color: #dddddd;">
        <td>
            <table width="100%" cellpadding="0" cellspacing="0" style="border:0;height: 70px;">
                <tr >
                    <td style="text-align: center; font-size: 11px; color: #888888; font-family: arial;">Copyright Adler Talent <?php echo date('Y'); ?>. All rights reserved</td>
                </tr>
            </table>
        </td>
    </tr>
</table>

</body>
</html>