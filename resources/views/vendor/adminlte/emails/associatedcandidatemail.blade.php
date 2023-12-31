<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>Adler Talent</title>
    @yield('style')
</head>

<body style="margin: 0; padding-top: 30px; background-color: #f5f5f5;">
    <table border="0" cellpadding="0" cellspacing="0" width="600" style="font-family:Helvetica,Arial,sans-serif; border-collapse: collapse; color: #444444;" align="center">
        <tr>
            <td width="600" style="background-color: green; !important;">
                <table width="100%" cellpadding="0" cellspacing="0" style="border:0; background-color: #ffffff; padding: 50px 54px;">
                    <tr style="font-family:Cambria, serif;font-size: 11.0pt;">
                        <td>
                            <b>Hello,</p></b>
                            <p>{{ $subject }} :</p>
                            <p>
                                <table width="600" cellpadding="3" cellspacing="0" border="1" border-color="#000000">
                                    <tr style="background-color: #7598d9">
                                        <td align="center"><b>Sr.<br/>No.</b></td>
                                        <td align="center"><b>Candidate Name</b></td>
                                        <td align="center"><b>Candidate Owner</b></td>
                                        <td align="center"><b>Candidate Email</b></td>
                                        <td align="center"><b>Candidate Status</b></td>
                                        <td align="center"><b>Candidate Mobile No.</b></td>
                                        <td align="center"><b>Round</b></td>
                                    </tr>
                                    <?php $i=0; ?>
                                    @foreach($candidate as $key => $value)
                                        <tr>
                                            <td align="center">{{ ++$i }}</td>
                                            <td align="center">{{$value['fname']}}</td>
                                            <td align="center">{{$value['owner']}}</td>
                                            <td align="center">{{$value['email']}}</td>
                                            <td align="center">{{$value['status']}}</td>
                                            <td align="center">{{$value['mobile']}}</td>
                                            <td align="center">{{$value['shortlisted']}}</td>
                                        </tr>
                                    @endforeach
                                </table>
                            </p>
                            <p>Thanks.</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>