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

                <b><p style="text-align: left;">Dear {{ $user_name }},</p></b>
                <p style="text-align: left;">Greetings from Easy2Hire!</p>

                <u><p style="text-align: left;">This is to inform that you have opted for the following Optional Holidays :</p></u>
      
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
                
                <p style="text-align: left;">You are hereby requested to co-ordinate with your Reporting Manager and plan the delegation of ongoing tasks within the team to avoid any further delay or communication gap.</p>
                
                <p style="text-align: left;">Note: Optional Holidays are subject to approval. Reporting/HR/Director reserves the right to change the same based on business requirements.</p>

                <p style="text-align: left;">Thanks.<br/>Easy2Hire Team.</p>
            </td>
        </tr>
    </table>
</body>
</html>