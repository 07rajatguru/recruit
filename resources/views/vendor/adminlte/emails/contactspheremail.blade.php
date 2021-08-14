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
				<table width="100%" cellpadding="0" cellspacing="0" style="border:0; background-color: #ffffff; padding: 20px 50px 54px;">
					<tr>
	                    <th align="center" style="border-top: black 1px solid;padding: 8px;border-left: black 1px solid;">Contact Name</th>
	                    <td align="center" style="border-top: black 1px solid;padding: 8px;border-left: black 1px solid;">{{ $contact_details['name'] }}</td>
	                    <th align="center" style="border-top: black 1px solid;padding: 8px;border-left: black 1px solid;">Designation</th>
	                    <td align="center" style="border-top: black 1px solid;padding: 8px;border-left: black 1px solid;border-right: black 1px solid;">{{ $contact_details['designation'] }}</td>
	                </tr>
	                <tr>
	                	<th align="center" style="border-top: black 1px solid;padding: 8px;border-left: black 1px solid;">Company Name</th>
	                    <td align="center" style="border-top: black 1px solid;padding: 8px;border-left: black 1px solid;">{!! $contact_details['company'] !!}</td>
	                    <th align="center" style="border-top: black 1px solid;padding: 8px;border-left: black 1px solid;">Contact Number</th>
	                    <td align="center" style="border-top: black 1px solid;padding: 8px;border-left: black 1px solid;border-right: black 1px solid;">{{ $contact_details['contact_number'] }}</td>
				    </tr>
	                
	                <tr>
	                   	<th align="center" style="border-top: black 1px solid;padding: 8px;border-left: black 1px solid;">Official Email Id</th>
				        <td align="center" style="border-top: black 1px solid;padding: 8px;border-left: black 1px solid;">{{ $contact_details['official_email_id'] }}</td>
	            		<th align="center" style="border-top: black 1px solid;padding: 8px;border-left: black 1px solid;">Personal Id</th>
	                    <td align="center" style="border-top: black 1px solid;padding: 8px;border-left: black 1px solid;border-right: black 1px solid;">{{ $contact_details['personal_id'] }}</td>
	                </tr>
	                <tr>
	                	
	                    <th align="center" style="border-top: black 1px solid;padding: 8px;border-left: black 1px solid;">Source</th>
	                    <td align="center" style="border-top: black 1px solid;padding: 8px;border-left: black 1px solid;">{{ $contact_details['source'] }}</td>
	                    <th align="center" style="border-top: black 1px solid;padding: 8px;border-left: black 1px solid;">Linkedin Profile Link</th>
	                    <td align="center" style="border-top: black 1px solid;padding: 8px;border-left: black 1px solid;border-right: black 1px solid;">{{ $contact_details['linkedin_profile_link'] }}</td>
	                </tr>
	                <tr>
	                    <th align="center" style="border-top: black 1px solid;padding: 8px;border-left: black 1px solid;">Referred by</th>
	                    <td align="center" style="border-top: black 1px solid;padding: 8px;border-left: black 1px solid;">{{ $contact_details['referred_by'] }}
	                    </td>
	                   	<th align="center" style="border-top: black 1px solid;padding: 8px;border-left: black 1px solid;">Location</th>
	                    <td align="center" style="border-top: black 1px solid;padding: 8px;border-left: black 1px solid;border-right: black 1px solid;">{{ $contact_details['location'] }}</td>

	                </tr>
					<tr>
	                	<th align="center" style="border-top: black 1px solid;padding: 8px;border-left: black 1px solid;border-bottom: black 1px solid;"> Self Remarks</th>
	                    <td align="center" style="border-top: black 1px solid;padding: 8px;border-left: black 1px solid;border-right: black 1px solid;border-bottom: black 1px solid;" colspan="3">{!! $contact_details['self_remarks'] !!}</td>
	                </tr>
	            </table>
        	</td>
        </tr>
	</table>
</body>
</html>