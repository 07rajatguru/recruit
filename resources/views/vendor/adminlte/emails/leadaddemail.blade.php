<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>Adler Talent</title>
@yield('style')

</head>

<body style="margin: 0; padding-top: 30px; background-color: #f5f5f5;">
	<table align="center" width="600px" cellpadding="0" cellspacing="0" style="font-family: arial; font-size: 12px; color: #444444;">
    	<tr>
        	<td>
                <table width="100%" cellpadding="0" cellspacing="0" style="border:0;height: 70px;">
                <tr>
                    <td align="center">
                       <div class="site-branding col-md-2 col-sm-6 col-xs-12" >
                            <a href="http://adlertalent.com/" title="Adler Talent Solutions Pvt. Ltd." style="font-size: 22px;text-decoration:none">
                                <img class="site-logo"  src="{{$app_url}}/images/Adler-Header.jpg" alt="Adler Talent Solutions Pvt. Ltd." style="width:100%;height: 120px;padding-top: 16px;   vertical-align: middle;">
                            </a>
                       </div>
                    </td>
                </tr>
            </table>
        	</td>
    	</tr>
    	<tr>
    		<td>
    			<br/>
				<table width="100%" cellpadding="0" cellspacing="0" style="border:0; background-color: #ffffff; padding: 0px 50px 54px;">
	                <tr>
	                	<th align="center" style="border-top: black 1px solid;padding: 8px;border-left: black 1px solid;">Company Name</th>
	                    <td align="center" colspan="3" style="border-top: black 1px solid;padding: 8px;border-left: black 1px solid;border-right: black 1px solid;">{!! $lead_details['name'] !!}</td>
				    </tr>
	                <tr>
	                    <th align="center" style="border-top: black 1px solid;padding: 8px;border-left: black 1px solid;">Contact Point</th>
	                    <td align="center" style="border-top: black 1px solid;padding: 8px;border-left: black 1px solid;">{{ $lead_details['coordinator_name'] }}</td>
	                    <th align="center" style="border-top: black 1px solid;padding: 8px;border-left: black 1px solid;">Designation</th>
	                    <td align="center" style="border-top: black 1px solid;padding: 8px;border-left: black 1px solid;border-right: black 1px solid;">{{ $lead_details['designation'] }}</td>
	                </tr>
	                <tr>
	                   	<th align="center" style="border-top: black 1px solid;padding: 8px;border-left: black 1px solid;">Email</th>
				        <td align="center" style="border-top: black 1px solid;padding: 8px;border-left: black 1px solid;">{{ $lead_details['mail'] }}</td>
	            		<th align="center" style="border-top: black 1px solid;padding: 8px;border-left: black 1px solid;">Secondary Email</th>
	                    <td align="center" style="border-top: black 1px solid;padding: 8px;border-left: black 1px solid;border-right: black 1px solid;">{{ $lead_details['s_email'] }}</td>
	                </tr>
	                <tr>
	                	<th align="center" style="border-top: black 1px solid;padding: 8px;border-left: black 1px solid;">Mobile</th>
	                    <td align="center" style="border-top: black 1px solid;padding: 8px;border-left: black 1px solid;">{{ $lead_details['mobile'] }}</td>
	                    <th align="center" style="border-top: black 1px solid;padding: 8px;border-left: black 1px solid;">Other Number</th>
	                    <td align="center" style="border-top: black 1px solid;padding: 8px;border-left: black 1px solid;border-right: black 1px solid;">{{ $lead_details['other_number'] }}</td>
	                </tr>
	                <tr>
	                    <th align="center" style="border-top: black 1px solid;padding: 8px;border-left: black 1px solid;">Location</th>
	                    <td align="center" colspan="3" style="border-top: black 1px solid;padding: 8px;border-left: black 1px solid;border-right: black 1px solid;">{{ $lead_details['location'] }}</td>
	                </tr>
	                <tr>
			            <th align="center" style="border-top: black 1px solid;padding: 8px;border-left: black 1px solid;">Website</th>
	                    <td align="center" style="border-top: black 1px solid;padding: 8px;border-left: black 1px solid;">{{ $lead_details['website'] }}</td>
				        <th align="center" style="border-top: black 1px solid;padding: 8px;border-left: black 1px solid;">Service</th>
	                    <td align="center" style="border-top: black 1px solid;padding: 8px;border-left: black 1px solid;border-right: black 1px solid;">{{ $lead_details['service'] }}</td>
	                </tr>
	                <tr>
	                	<th align="center" style="border-top: black 1px solid;padding: 8px;border-left: black 1px solid;">Source</th>
	                    <td align="center" style="border-top: black 1px solid;padding: 8px;border-left: black 1px solid;">{{ $lead_details['source'] }}</td>
	                    <th align="center" style="border-top: black 1px solid;padding: 8px;border-left: black 1px solid;">Referred by</th>
	                    <td align="center" style="border-top: black 1px solid;padding: 8px;border-left: black 1px solid;border-right: black 1px solid;">{{ $lead_details['referredby'] }}</td>
	                </tr>
	                <tr>
			            <th align="center" style="border-top: black 1px solid;padding: 8px;border-left: black 1px solid;">Lead Status</th>
	                	<td align="center" style="border-top: black 1px solid;padding: 8px;border-left: black 1px solid;border-right: black 1px solid;" colspan="3">{{ $lead_details['lead_status'] }}</td>
	                </tr>
					<tr>
	                	<th align="center" style="border-top: black 1px solid;padding: 8px;border-left: black 1px solid;border-bottom: black 1px solid;">Remarks</th>
	                    <td align="center" style="border-top: black 1px solid;padding: 8px;border-left: black 1px solid;border-right: black 1px solid;border-bottom: black 1px solid;" colspan="3">{{ $lead_details['remarks'] }}</td>
	                </tr>
	            </table>
        	</td>
        </tr>

        <tr style="height: 45px; background-color: #dddddd;">
        	<td style="text-align: center; font-size: 11px; color: #888888; font-family: arial;">Copyright Adler Talent <?php echo date('Y'); ?>. All rights reserved</td>
    	</tr>
	</table>
</body>
</html>