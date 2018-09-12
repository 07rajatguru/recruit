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

    <div style="background-color: #e6e7e8; margin: 10px 10px 40px 60px; padding: 30px 0; width: 682px;float:left">
            <br/>
            <h6 style="font-size: 16px; margin: 0;">Hello,</h6>
            <p style="margin:8px 0; line-height:1.2;">{{$name}} Contact you.Details are</p>
            <p style="margin:8px 0; line-height:1.2;">Name : {{$name}} </p>
            <p style="margin:8px 0; line-height:1.2;">Email : {{$email}}</p>
            <p style="margin:8px 0; line-height:1.2;">Message : {{$msg}}</p>
            <div style="clear: both;"></div>
    </div>
</body>
</html>