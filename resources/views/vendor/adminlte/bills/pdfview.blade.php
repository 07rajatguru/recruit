<!DOCTYPE html>
<html>
	<body>
		<table style="font-size: 13px;font-family: Calibri;">
			<tr>
				<td colspan="10">
					<img src="{{ public_path().'/images/Adler-Header.jpg' }}" height="100px" width="700px" />
				</td>
			</tr>
			<tr>
				<td colspan="10"></td>
			</tr>
			<tr>
				<td colspan="10"></td>
			</tr>
			<tr>
				<th colspan="10" style="text-align: center;background: #A9A9A9;color: #000000;border: 1px solid #000000;">Invoice</th>
			</tr>
			<tr>
				<td colspan="10"></td>
			</tr>
			<tr>
				<th colspan="4" style="text-align: center;background: #add8e6;border: 1px solid #000000;">Bill to Party</th>
				<th colspan="4" style="text-align: center;background: #add8e6;border: 1px solid #000000;">Ship to Party</th>
				<th colspan="2" style="text-align: center;background: #add8e6;border: 1px solid #000000;">
				</th>
			</tr>
			<tr>
				<td colspan="4" style="border: 1px solid #000000;"><b>Name:</b> {{ $invoice_data['company_name'] }}</td>
				<td colspan="4" style="border: 1px solid #000000;"><b>Name:</b> {{ $invoice_data['company_name'] }}</td>
				<td colspan="2" rowspan="2" style="border: 1px solid #000000;"><center><b> Invoice No. :
				</center><br/> <center>ATS/18-19/___</center></b></td>
			</tr>
			<tr>
				<td colspan="4" style="border: 1px solid #000000;"><b>Address:</b> {{ $invoice_data['billing_address'] }}</td>
				<td colspan="4" style="border: 1px solid #000000;"><b>Address:</b> {{ $invoice_data['shipping_address'] }}</td>
			</tr>
			<tr>
				<td colspan="4" style="border: 1px solid #000000;"><b>GST Number - </b> {{ $invoice_data['gst_no'] }}</td>
				<td colspan="4" style="border: 1px solid #000000;"><b>GST Number - </b> {{ $invoice_data['gst_no'] }}</td>
				<td colspan="2" style="border: 1px solid #000000;"><b>Dt- _________</b></td>
			</tr>
			<tr>
				<td colspan="10"></td>
			</tr>
			<tr>
				<th colspan="10" style="text-align: center;background: #A9A9A9;border: 1px solid #000000;">Description</th>
			</tr>
			<tr>
				<td colspan="10"></td>
			</tr>
			<tr>
				<th style="text-align: center;border: 1px solid #000000;">Sr. <br/> No</th>
				<th colspan="8" style="text-align: center;border: 1px solid #000000;">Description</th>
				<th style="text-align: center;border: 1px solid #000000;">Amount</th>
			</tr>
			<tr>
				<td style="text-align: center;border: 1px solid #000000;">1</td>
				<td colspan="8" style="border: 1px solid #000000;">&nbsp;<b>Towards professional fee for recruitment (SAC Code-9997): </b></td>
				<td style="border: 1px solid #000000;"></td>
			</tr>
			<tr>
				<td style="border: 1px solid #000000;"></td>
				<td colspan="8" style="border: 1px solid #000000;">Candidate Name: {{ $invoice_data['candidate_name'] }}</td>
				<td style="text-align: center;border: 1px solid #000000;">{{ $invoice_data['fees'] }}</td>
			</tr>
			<tr>
				<td style="border: 1px solid #000000;"></td>
				<td colspan="8" style="border: 1px solid #000000;">Designation: {{ $invoice_data['designation_offered'] }}</td>
				<td style="border: 1px solid #000000;"></td>
			</tr>
			<tr>
				<td style="border: 1px solid #000000;"></td>
				<td colspan="8" style="border: 1px solid #000000;">Job Location: {{ $invoice_data['job_location'] }}</td>
				<td style="border: 1px solid #000000;"></td>
			</tr>
			<tr>
				<td style="border: 1px solid #000000;"></td>
				<td colspan="8" style="border: 1px solid #000000;">Fixed CTC: {{ $invoice_data['fixed_salary'] }}</td>
				<td style="border: 1px solid #000000;"></td>
			</tr>
			<tr>
				<td style="border: 1px solid #000000;"></td>
				<td colspan="8" style="border: 1px solid #000000;">Percentage Charged: {{ $invoice_data['percentage_charged'] }}%</td>
				<td style="border: 1px solid #000000;"></td>
			</tr>
			<tr>
				<td style="border: 1px solid #000000;"></td>
				<td colspan="8" style="border: 1px solid #000000;">Joining Date: {{ date('d-m-Y', strtotime($invoice_data['joining_date'])) }}</td>
				<td style="border: 1px solid #000000;"></td>
			</tr>
			<tr>
				<td style="border: 1px solid #000000;"></td>
				<td colspan="8" style="text-align: right;border: 1px solid #000000;">Amount Excluding GST</td>
				<td style="text-align: center;border: 1px solid #000000;">{{ $invoice_data['fees'] }}</td>
			</tr>

			@if(isset($invoice_data['gst_check']) && $invoice_data['gst_check'] == '24')
			<tr>
				<td style="border: 1px solid #000000;"></td>
				<td colspan="8" style="text-align: right;border: 1px solid #000000;">CGST  @ 9%</td>
				<td style="text-align: center;border: 1px solid #000000;">{{ $invoice_data['cgst'] }}</td>
			</tr>
			<tr>
				<td style="border: 1px solid #000000;"></td>
				<td colspan="8" style="text-align: right;border: 1px solid #000000;">SGST  @ 9%</td>
				<td style="text-align: center;border: 1px solid #000000;">{{ $invoice_data['sgst'] }}</td>
			</tr>
			@else
			<tr>
				<td style="border: 1px solid #000000;"></td>
				<td colspan="8" style="text-align: right;border: 1px solid #000000;">IGST  @ 18%</td>
				<td style="text-align: center;border: 1px solid #000000;">{{ $invoice_data['gst'] }}</td>
			</tr>
			<tr>
				<td colspan="10"></td>
			</tr>
			@endif
			<tr>
				<td style="text-align: center;border: 1px solid #000000;vertical-align:middle;"><b>INR:</b></td>
				<td colspan="7" style="text-align: center;border: 1px solid #000000;vertical-align:middle;"><b>{{ $invoice_data['amount_in_words'] }}</b></td>
				<td style="text-align: center;border: 1px solid #000000;vertical-align:middle;"><b>Total</b></td>
				<td style="text-align: center;border: 1px solid #000000;vertical-align:middle;"><b>{{ $invoice_data['billing_amount'] }}</b></td>
			</tr>
			<tr>
				<td colspan="10"></td>
			</tr>
			<tr>
				<td colspan="10"></td>
			</tr>
			<tr>
				<td colspan="10"></td>
			</tr>
			<tr>
				<td colspan="10"><b>Company PAN No  :  </b> AAMCA2137K  |  <b>GST No : </b> 24AAMCA2137K1ZP  |  <b>Service Category  :  </b> Manpower Services</td>
			</tr>
			<tr>
				<td colspan="10"><b>MSME Registration No. : </b> GJ01D0136980</td>
			</tr>
			<tr>
				<td colspan="10"><b><u>Subject To Ahmedabad Jurisdiction</u></b></td>
			</tr>
			<tr>
				<td colspan="10">Please make cheque payable to: Adler Talent Solutions Pvt. Ltd.</td>
			</tr>
			<tr>
				<td colspan="10"><b>Bank Name : </b> ICICI Bank Limited</td>
			</tr>
			<tr>
				<td colspan="10"><b>Branch Name : </b> SG Road branch</td>
			</tr>
			<tr>
				<td colspan="10"><b>Account Number : </b>029505002727</td>
			</tr>
			<tr>
				<td colspan="10"><b>Type of Account : </b> Current Account</td>
			</tr>
			<tr>
				<td colspan="10"><b>IFSC Code : </b> ICIC0000295</td>
			</tr>
			<tr>
				<td colspan="10"></td>
			</tr>
			<tr>
				<td colspan="10"><b>For, Adler Talent Solutions Pvt. Ltd.</b></td>
			</tr>
			<tr>
				<td colspan="10"></td>
			</tr>
			<tr>
				<td colspan="10"><img src="{{ public_path().'/images/Raj_Signature.png' }}" height="70px" width="70px" /></td>
			</tr>
			<tr>
				<td colspan="10"><b>Authorized Signatory</b></td>
			</tr>
			<tr>
				<td colspan="10" style="text-align: center;"><b><u>THANK YOU FOR PARTNERING WITH US!</u></b>
				</td>
			</tr>
		</table>
	</body>
</html>