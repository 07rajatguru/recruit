<!DOCTYPE html>
<html>
	<body>
		<table style="width: 40%;">
			<tr>
				<td></td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td>
			</tr>
			<tr>
				<td></td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td>
			</tr>

			<tr>
				<th colspan="10" style="text-align: center;background: #808080;color: #483d8b;">Invoice</th>
			</tr>
			<tr>
				<td></td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td>
			</tr>
			<tr>
				<td></td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td>
			</tr>
			<tr>
				<th colspan="5" style="text-align: center;background: #add8e6;">Bill to Party</th>
				<th colspan="4" style="text-align: center;background: #add8e6;">Ship to Party</th>
				<th style="background: #add8e6;"></th>
			</tr>
			<tr>
				<td colspan="5">Name: {{ $invoice_data['company_name'] }}</td>
				<td colspan="4">Name: {{ $invoice_data['company_name'] }}</td>
				<td rowspan="2"><b> Invoice No. :<br/> ATS/18-19/___</b></td>
			</tr>
			<tr>
				<td colspan="5" rowspan="2">Address: {{ $invoice_data['billing_address'] }}</td>
				<td colspan="4" rowspan="2">Address: {{ $invoice_data['shipping_address'] }}</td>
			</tr>
			<tr>
				<td colspan="5"></td>
				<td colspan="4"></td>
				<td rowspan="2"><b>Dt- _________</b></td>
			</tr>
			<tr>
				<td colspan="5">GST Number- {{ $invoice_data['gst_no'] }}</td>
				<td colspan="4">GST Number- {{ $invoice_data['gst_no'] }}</td>
			</tr>
			<tr>
				<td></td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td>
			</tr>
			<tr>
				<th colspan="10" style="text-align: center;background: #808080;">Description</th>
			</tr>
			<tr>
				<th>Sr. <br/> No</th>
				<th colspan="8" style="text-align: center;">Description</th>
				<th style="text-align: center;">Amount</th>
			</tr>
			<tr>
				<td>1</td>
				<td colspan="7"><b>Towards professional fee for recruitment (SAC Code-9997):</b></td>
				<td></td>
			</tr>
			<tr>
				<td></td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td>
			</tr>
			<tr>
				<td></td>
				<td colspan="5">Candidate Name:</td>
				<td colspan="3">{{ $invoice_data['candidate_name'] }}</td>
				<td style="text-align: center;">{{ $invoice_data['fees'] }}</td>
			</tr>
			<tr>
				<td></td>
				<td colspan="5">Designation:</td>
				<td colspan="3">{{ $invoice_data['designation_offered'] }}</td>
				<td></td>
			</tr>
			<tr>
				<td></td>
				<td colspan="5">Job Location:</td>
				<td colspan="3">{{ $invoice_data['job_location'] }}</td>
				<td></td>
			</tr>
			<tr>
				<td></td>
				<td colspan="5">Fixed CTC:</td>
				<td colspan="3">{{ $invoice_data['fixed_salary'] }}</td>
				<td></td>
			</tr>
			<tr>
				<td></td>
				<td colspan="5">Percentage Charged:</td>
				<td colspan="3">{{ $invoice_data['percentage_charged'] }}%</td>
				<td></td>
			</tr>
			<tr>
				<td></td>
				<td colspan="5">Joining Date:</td>
				<td colspan="3">{{ date('d-m-Y', strtotime($invoice_data['joining_date'])) }}</td>
				<td></td>
			</tr>
			<tr>
				<td></td>
				<td colspan="8" style="text-align: right;">Amount Excluding GST</td>
				<td style="text-align: center;">{{ $invoice_data['fees'] }}</td>
			</tr>
			@if(isset($invoice_data['gst_check']) && $invoice_data['gst_check'] == '24')
			<tr>
				<td></td>
				<td colspan="8" style="text-align: right;">CGST  @ 9%</td>
				<td style="text-align: center;">{{ $invoice_data['cgst'] }}</td>
			</tr>
			<tr>
				<td></td>
				<td colspan="8" style="text-align: right;">SGST  @ 9%</td>
				<td style="text-align: center;">{{ $invoice_data['sgst'] }}</td>
			</tr>
			@else
			<tr>
				<td></td>
				<td colspan="8" style="text-align: right;">IGST  @ 18%</td>
				<td style="text-align: center;">{{ $invoice_data['gst'] }}</td>
			</tr>
			<tr>
				<td></td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td>
			</tr>
			@endif
			<tr>
				<td>INR:</td>
				<td colspan="7" style="text-align: center;">{{ $invoice_data['amount_in_words'] }}</td>
				<td style="text-align: center;">Total</td>
				<td style="text-align: center;">{{ $invoice_data['billing_amount'] }}</td>
			</tr>
			<tr>
				<td></td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td>
			</tr>
			<tr>
				<td colspan="10">Company PAN No    : AAMCA2137K   |  GST No: 24AAMCA2137K1ZP |  Service Category  : Manpower Services</td>
			</tr>
			<tr>
				<td colspan="10"><u>Subject To Ahmedabad Jurisdiction</u></td>
			</tr>
			<tr>
				<td colspan="10">Please make cheque payable to: <b> Adler Talent Solutions Pvt. Ltd.</b></td>
			</tr>
			<tr>
				<td colspan="10">Bank Name : ICICI Bank Limited</td>
			</tr>
			<tr>
				<td colspan="10">Branch Name : SG Road branch</td>
			</tr>
			<tr>
				<td colspan="10">Account Number : 029505002727</td>
			</tr>
			<tr>
				<td colspan="10">Type of Account : Current Account</td>
			</tr>
			<tr>
				<td colspan="10">IFSC Code : ICIC0000295</td>
			</tr>
			<tr>
				<td></td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td>
			</tr>
			<tr>
				<td colspan="10"><b>For, Adler Talent Solutions Pvt. Ltd. </b></td>
			</tr>
			<tr>
				<td></td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td>
			</tr>
			<tr>
				<td></td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td>
			</tr>
			<tr>
				<td></td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td>
			</tr>
			<tr>
				<td colspan="10"><b>Authorized Signatory</b></td>
			</tr>
			<tr>
				<td></td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td>
			</tr>
			<tr>
				<td colspan="10" style="text-align: center;"><u>THANK YOU FOR PARTNERING WITH US!</u></td>
			</tr>
		</table>
	</body>
</html>