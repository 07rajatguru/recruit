<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>Adler Talent</title>
    @yield('style')
</head>

<body style="margin: 0; padding-top: 30px; background-color: #f5f5f5;">
    @foreach($mail as $key => $value)
        @if($value['module'] == 'Process Manual')
            <table border="0" cellpadding="0" cellspacing="0" width="600" style="font-family:Helvetica,Arial,sans-serif; border-collapse: collapse; color: #444444;" align="center">
                <tr>
                    <td width="600" >
                        <table  cellpadding="0" cellspacing="0" style="border:0; background-color: #ffffff; padding: 50px 54px;">
                            <tr>
                                <td>
                                    <b><p style="margin-top: 0px; margin-bottom: 14px; font-family: arial;">Hello Team,</p></b>
                                    <p>New Process Manual has been added.</p>
                                </td>
                            </tr>
                        </table>
                        
                        <table width="100%" cellpadding="0" cellspacing="0" style="border:0; background-color: #ffffff; padding: 0px 50px 54px;">
                            <tr>
                                <td align="center" style="padding: 0px;">
                                    <a style="border: black; background-color: darkgray;color: white;padding: 10px 20px 10px 20px; border-radius: 18px;font-size: 15px;width: 59%;text-decoration: none;" class="btn btn-primary" formtarget="_blank" href="{{getenv('APP_URL').'/process/'.$module_id.'/show'}}">Show</a>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        @elseif($value['module'] == 'Today\'s Process Manual')
            <table border="0" cellpadding="0" cellspacing="0" width="600" style="font-family:Helvetica,Arial,sans-serif; border-collapse: collapse; color: #444444;" align="center">
                    <tr style="background-color:white;"><td colspan="5"></td></tr>
                    <tr style="background-color:white;"><td colspan="5"></td></tr>
                    <tr style="background-color:white;">
                        <table align="center" width="600px" cellpadding="0" border="1" cellspacing="0" style="font-family: arial; font-size: 12px; color: #444444;">
                            <tr style="background-color:white;height: 30px;">
                                <th>Sr No</th>
                                <th>Title</th>
                                <th width="90">Action</th>
                            </tr>
                            @if(isset($process) && sizeof($process) > 0)
                                @foreach($process as $k => $v)
                                    <tr style="background-color:white;height: 30px;">
                                        <td align="center">{{ ++$k }}</td>
                                        <td align="center">{{ $v['title'] }}</td>
                                        <td align="center" width="90">
                                            <a style="border: black; background-color: #9c5cac;color: white;padding: 3px 15px 3px 15px; border-radius: 18px;font-size: 15px;width: 59%;" class="btn btn-primary" formtarget="_blank" href="{{getenv('APP_URL').'/process/'.$v['t_id'].'/show'}}">Show</a>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        </table>
                    </tr>
                </table>
        @else
            {!! $value['message'] !!}
        @endif
    @endforeach
</body>
</html>