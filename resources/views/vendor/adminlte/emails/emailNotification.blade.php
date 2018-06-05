<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>Adler Talent</title>
@yield('style')

</head>

<body style="margin: 0; padding-top: 30px; background-color: #f5f5f5;">

@foreach($mail as $key => $value)
{!! $value['message'] !!}
@endforeach
</body>
</html>