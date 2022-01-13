<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <title>Adler Talent</title>
        @yield('style')
    </head>

<body style="margin: 0; padding-top: 30px;">
    <table border="0" cellpadding="0" cellspacing="0" width="600" style="font-family:Helvetica,Arial,sans-serif;" align="left">
        <tr>
            <td width="600" style="font-family:Cambria, serif;font-size: 11.0pt;">

                <b><p style="text-align: left;">Dear Sir/Madam,</p></b>
                <i><p style="text-align: left;">Greetings !</p></i>

                <p style="text-align: left;">Please note that i selected following optional holidays from the list.</p>
      
                @if(isset($selected_holidays) && sizeof($selected_holidays) > 0)
                <?php
                    $names_string = '';    
                    $incr = 1;
                ?>
                    @foreach($selected_holidays as $key => $value)
                        <?php 
                            if($names_string == '') {
                                $names_string = $incr . " . " . $value . " " . "<br/>";
                            }
                            else {
                                $names_string .= $incr . " . " . $value . " " . "<br/>";
                            }
                            $incr++;
                        ?>
                    @endforeach
                @endif
                
                <p style="text-align: left;">{!! $names_string !!}</p>
                
                <p style="text-align: left;">Thanks.</p>
            </td>
        </tr>
        <tr>
            <td width="800">
                <table width="100%" cellpadding="0" cellspacing="0" style="border:0;">
                    <tr>
                        <td>{!! $signature !!}</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>