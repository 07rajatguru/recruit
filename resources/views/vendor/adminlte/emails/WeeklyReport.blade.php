<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>Adler Talent</title>

    @yield('style')
</head>

<body style="margin: 0; padding-top: 30px; background-color: #f5f5f5;">
            <u><b><h1>No of CVs Associated in this week : 40 (7*5 = 35+5 = 40)</h1></b></u>
            <table width="100%" cellpadding="0" cellspacing="0" border="1" border-color="#000000">
                <tr style="background-color: #C4D79B">
                    <td align="center"><b>Sr.No.</b></td>
                    <td align="center"><b>Day(Date)</b></td>
                    <td align="center"><b>No of resumes<br/>associated</b></td>
                </tr>

                <?php $i=0; ?>
                <tr>
                    <td align="center">{{ ++$i }}</td>
                    <td align="center">{{date(' l (jS F,y)')}}</td>
                    <td align="center">{{''}}</td>
                </tr> 
                <tr>
                    <td align="center"></td>
                    <td align="center">Total Associated</td>
                    <td align="center"></td>
                </tr>
                <tr>
                    <td align="center"></td>
                    <td align="center">Benchmark</td>
                    <td align="center">40</td>
                </tr>
                <tr>
                    <td align="center"></td>
                    <td align="center">No of resumes not <br/> achieved</td>
                    <td align="center"></td>
                </tr>
            </table>
            <u><b><h1>No of Interviews Scheduled : {{''}}</h1></b></u>
            <table width="100%" cellpadding="0" cellspacing="0" border="1" border-color="#000000">
                <tr style="background-color: #C4D79B">
                    <td align="center"><b>Sr.No.</b></td>
                    <td align="center"><b>Day(Date)</b></td>
                    <td align="center"><b>No of resumes<br/>associated</b></td>
                </tr>
                <?php $i=0; ?>
                <tr>
                    <td align="center">{{ ++$i }}</td>
                    <td align="center">{{''}}</td>
                    <td align="center">{{''}}</td>
                </tr>
                <tr>
                    <td align="center"></td>
                    <td align="center">Total</td>
                    <td align="center"></td>
                </tr>
            </table>
            <u><b><h1>No of Leads Added : {{''}}</h1></b></u>
</body>
</html>