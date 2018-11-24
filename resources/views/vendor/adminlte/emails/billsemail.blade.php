<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	@yield('style')
</head>

<body>
	<table border="1" cellpadding="0" cellspacing="0" width="601" style="text-align: center;">
		@foreach($bills_details as $key => $value)
		<tr style="background: rgb(146, 208, 80); height: 16pt;">
			<td><p style="margin-bottom: 10pt;line-height: 13.8pt;width: 200pt;"><b>Company Name</b></p></td>
			<td><p style="margin-bottom: 10pt;line-height: 13.8pt;width: 200pt;"><b>{{ $value['company_name'] }}</b></td>
		</tr>
		<tr style="height: 16pt;">
			<td style="background-color: yellow;"><p style="margin-bottom: 10pt;line-height: 13.8pt;width: 200pt;"><b>Candidate Name</b></p></td>
			<td style="background: rgb(228, 223, 236);"><p style="margin-bottom: 10pt;line-height: 13.8pt;width: 200pt;"><b>{{ $value['candidate_name'] }}</b></p></td>
		</tr>
		<tr style="height: 16pt;">
			<td><p style="margin-bottom: 10pt;line-height: 13.8pt;width: 200pt;">Candidate Contact number (Cell Phone & home both)</p></td>
			<td><p style="margin-bottom: 10pt;line-height: 13.8pt;width: 200pt;">{{ $value['candidate_contact_number'] }}</p></td>
		</tr>
		<tr style="height: 16pt;">
			<td><p style="margin-bottom: 10pt;line-height: 13.8pt;width: 200pt;">Designation offered</p></td>
			<td><p style="margin-bottom: 10pt;line-height: 13.8pt;width: 200pt;">{{ $value['designation_offered'] }}</p></td>
		</tr>
		<tr style="height: 16pt;">
			<td><p style="margin-bottom: 10pt;line-height: 13.8pt;width: 200pt;">Joining Date</p></td>
			<td><p style="margin-bottom: 10pt;line-height: 13.8pt;width: 200pt;">{{ date('d-M-y',strtotime($value['date_of_joining'])) }}</p></td>
		</tr>
		<tr style="height: 16pt;">
			<td><p style="margin-bottom: 10pt;line-height: 13.8pt;width: 200pt;">Job Location</p></td>
			<td><p style="margin-bottom: 10pt;line-height: 13.8pt;width: 200pt;">{{ $value['job_location'] }}</p></td>
		</tr>
		<tr style="height: 16pt;">
			<td><p style="margin-bottom: 10pt;line-height: 13.8pt;width: 200pt;">Fixed Salary</p></td>
			<td><p style="margin-bottom: 10pt;line-height: 13.8pt;width: 200pt;">{{ $value['fixed_salary'] }}</p></td>
		</tr>
		<tr style="height: 16pt;">
			<td><p style="margin-bottom: 10pt;line-height: 13.8pt;width: 200pt;">Percentage Charged</p></td>
			<td><p style="margin-bottom: 10pt;line-height: 13.8pt;width: 200pt;">{{ $value['percentage_charged'] }}%</p></td>
		</tr>
		<tr style="height: 16pt;">
			<td><p style="margin-bottom: 10pt;line-height: 13.8pt;width: 200pt;">Efforts</p></td>
			<td><p style="margin-bottom: 10pt;line-height: 13.8pt;width: 200pt;">{{ $value['efforts'] }}</p></td>
		</tr>
		<tr style="height: 16pt;">
			<td><p style="margin-bottom: 10pt;line-height: 13.8pt;width: 200pt;">Source (Naukri/Monster/Referral)</p></td>
			<td><p style="margin-bottom: 10pt;line-height: 13.8pt;width: 200pt;">{{ $value['source'] }}</p></td>
		</tr>
		<tr style="height: 16pt;">
			<td><p style="margin-bottom: 10pt;line-height: 13.8pt;width: 200pt;"><b>Client Name</b></p></td>
			<td><p style="margin-bottom: 10pt;line-height: 13.8pt;width: 200pt;">{{ $value['client_name'] }}</p></td>
		</tr>
		<tr style="height: 16pt;">
			<td><p style="margin-bottom: 10pt;line-height: 13.8pt;width: 200pt;">Contact number (Cell phone & Office land line)</p></td>
			<td><p style="margin-bottom: 10pt;line-height: 13.8pt;width: 200pt;">{{ $value['client_contact_number'] }}</p></td>
		</tr>
		<tr style="height: 16pt;">
			<td><p style="margin-bottom: 10pt;line-height: 13.8pt;width: 200pt;">Client Email ID</p></td>
			<td><p style="margin-bottom: 10pt;line-height: 13.8pt;width: 200pt;">{{ $value['client_email_id'] }}</p></td>
		</tr>
		<tr style="height: 16pt;">
			<td><p style="margin-bottom: 10pt;line-height: 13.8pt;width: 200pt;">Address of communication </p></td>
			<td><p style="margin-bottom: 10pt;line-height: 13.8pt;width: 200pt;">{{ $value['address_of_communication'] }}</p></td>
		</tr>
		@endforeach
	</table>

</body>