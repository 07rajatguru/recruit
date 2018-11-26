<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	@yield('style')
</head>

<body>
	<table border="1" cellpadding="0" cellspacing="0" width="100%" style="text-align: center;">
		@foreach($bills_details as $key => $value)
		<tr style="background: rgb(146, 208, 80); height: auto;width: auto;">
			<td><b>Company Name</b></td>
			<td><b>{{ $value['company_name'] }}</b></td>
		</tr>
		<tr style="height: auto;width: auto;">
			<td style="background-color: yellow;"><b>Candidate Name</b></td>
			<td style="background: rgb(228, 223, 236);"><b>{{ $value['candidate_name'] }}</b></td>
		</tr>
		<tr style="height: auto;width: auto;">
			<td>Candidate Contact number (Cell Phone & home both)</td>
			<td>{{ $value['candidate_contact_number'] }} {{ $value['candidate_other_no'] }}</td>
		</tr>
		<tr style="height: auto;width: auto;">
			<td>Designation offered</td>
			<td>{{ $value['designation_offered'] }}</td>
		</tr>
		<tr style="height: auto;width: auto;">
			<td>Joining Date</td>
			<td>{{ date('d-M-Y',strtotime($value['date_of_joining'])) }}</td>
		</tr>
		<tr style="height: auto;width: auto;">
			<td>Job Location</td>
			<td>{{ $value['job_location'] }}</td>
		</tr>
		<tr style="height: auto;width: auto;">
			<td>Fixed Salary</td>
			<td>{{ $value['fixed_salary'] }} CTC</td>
		</tr>
		<tr style="height: auto;width: auto;">
			<td>Percentage Charged</td>
			<td>{{ $value['percentage_charged'] }}%</td>
		</tr>
		<tr style="height: auto;width: auto;">
			<td>Efforts</td>
			<td>{{ $value['efforts'] }}</td>
		</tr>
		<tr style="height: auto;width: auto;">
			<td>Source (Naukri/Monster/Referral)</p></td>
			<td>{{ $value['source'] }}</p></td>
		</tr>
		<tr style="height: auto;width: auto;">
			<td><b>Client Name</b></p></td>
			<td>{{ $value['client_name'] }}</p></td>
		</tr>
		<tr style="height: auto;width: auto;">
			<td>Contact number (Cell phone & Office land line)</td>
			<td>{{ $value['client_contact_number'] }} {{ $value['client_other_no'] }}</td>
		</tr>
		<tr style="height: auto;width: auto;">
			<td>Client Email ID</p></td>
			<td>{{ $value['client_email_id'] }}</td>
		</tr>
		<tr style="height: auto;width: auto;">
			<td>Address of communication </td>
			<td>{{ $value['address_of_communication'] }}</td>
		</tr>
		@endforeach
	</table>

</body>