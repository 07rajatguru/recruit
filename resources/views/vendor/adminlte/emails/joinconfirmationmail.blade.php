<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	@yield('style')
</head>

<body>
	<table>
		<tr>
			<td>
				<b><p style="font-family: arial;">Dear {{ $join_mail['client_name'] }},</p></b>
                <p>Greetings from Adler Talent Solutions ! </p>
			</td>
		</tr>
		<tr>
			<td>
				<u>Request you to kindly confirm the joining of candidate as below:</u>
			</td>
		</tr>
		<br/>
		<table border="1" cellpadding="0" cellspacing="0" width="100%" style="text-align: center;">
			<tr style="color: yellow; background-color: #7598d9;">
				<td><b>Company Name</b></td>
				<td><b>{{ $join_mail['client_company_name'] }}</b></td>
			</tr>
			<tr style="background-color: #B8DBE6;">
				<td><b>Candidate Name</b></td>
				<td><b>{{ $join_mail['candidate_name'] }}</b></td>
			</tr>
			<tr>
				<td>Designation offered</td>
				<td>{{ $join_mail['designation_offered'] }}</td>
			</tr>
			<tr>
				<td>Joining Date</td>
				<td>{{ date('d-M-y',strtotime($join_mail['joining_date'])) }}</td>
			</tr>
			<tr>
				<td>Job Location</td>
				<td>{{ $join_mail['job_location'] }}</td>
			</tr>
			<tr>
				<td>Salary</td>
				<td>{{ $join_mail['fixed_salary'] }}</td>
			</tr>
			<tr>
				<td>Percentage Charged</td>
				<td>{{ $join_mail['percentage_charged'] }}%</td>
			</tr>
			<tr>
				<td>Recruitment Fees</td>
				<td>{{ $join_mail['fees'] }}</td>
			</tr>
			<tr>
				<td>GST @ 18%</td>
				<td>{{ $join_mail['gst'] }}</td>
			</tr>
			<tr>
				<td><b>Billable Amount</b></td>
				<td><b>{{ $join_mail['billing_amount'] }}</b></td>
			</tr>
		</table>
		<br/>
		<tr>
			<td>
				<u>GST number of ADLER TALENT SOLUTIONS PVT. LTD. as 24AAMCA2137K1ZP</u> .<br/><br/>
				Also, Request you to kindly confirm your GST number for the said invoice to send it accordingly.<br/><br/>
				Awaiting your revert to raise an invoice accordingly.<br/><br/>
				Thanks.
			</td>
		</tr>
	</table>
</body>
</html>