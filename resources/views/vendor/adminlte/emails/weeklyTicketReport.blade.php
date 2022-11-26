<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <title>Adler Talent</title>
        @yield('style')
    </head>

    <body style="margin: 0; padding-top: 30px;">
        <table border="0" cellpadding="0" cellspacing="0" width="800" style="font-family:Helvetica,Arial,sans-serif;" align="left">
            <tr>
                <td width="800" style="font-family:Cambria, serif;font-size: 11.0pt;">
                    <table width="700" cellpadding="3" cellspacing="0" border="1" border-color="#000000">
                        <tr style="background-color: #7598d9;font-family:Cambria, serif;font-size: 11.0pt;">
                            <td align="center"><b>Pending Tickets</b></td>
                            <td align="center"><b>Closed Tickets</b></td>
                        </tr>

                        @if(isset($tickets_data) && sizeof($tickets_data) > 0)
                            <tr style="font-family:Cambria, serif;font-size: 11.0pt;">
                                @if(isset($open) && $open != '')
                                    <td>{!! $open !!}</td>
                                @else
                                    <td></td>
                                @endif

                                @if(isset($closed) && $closed != '')
                                    <td>{!! $closed !!}</td>
                                @else
                                    <td></td>
                                @endif
                            </tr>
                        @endif
                    </table>
                </td>
            </tr>
        </table>
    </body>
</html>