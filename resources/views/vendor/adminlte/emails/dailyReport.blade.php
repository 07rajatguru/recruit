<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>Adler Talent</title>

    @yield('style')
</head>

<body style="margin: 0; padding-top: 30px; background-color: #f5f5f5;">
            <u><b><p>No of CVs Associated : {{$associate_count}}</p></b></u>
            <table width="100%" cellpadding="0" cellspacing="0" border="1" border-color="#000000">
                <tr style="background-color: #C4D79B">
                    <td align="center"><b>Sr.No.</b></td>
                    <td align="center"><b>Date</b></td>
                    <td align="center"><b>Position Name</b></td>
                    <td align="center"><b>Company</b></td>
                    <td align="center"><b>Location</b></td>
                    <td align="center"><b>No of resumes<br/>associated</b></td>
                    <td align="center"><b>Status</b></td>
                </tr>

                <?php $i=0; ?>
                @foreach($associate_daily as $key=>$value)
                <tr>
                    <td align="center">{{ ++$i }}</td>
                    <td align="center">{{date('jS F,y') }}</td>
                    <td align="center">{{$value['posting_title']}}</td>
                    <td align="center">{{$value['company']}}</td>
                    <td align="center">{{$value['location']}}</td>
                    <td align="center">{{$value['associate_count'] or ''}}</td>
                    <td align="center">{{$value['status']}}</td>
                </tr> 
                @endforeach
            </table>
            <u><b><p>No of Interviews Scheduled : {{''}}</p></b></u>
            <u><b><p>No of Leads Added : {{$lead_count}}</p></b></u>
</body>
</html>