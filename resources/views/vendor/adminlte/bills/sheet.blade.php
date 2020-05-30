<!DOCTYPE html>
<html>
	<body>
		<table style="width: 40%;">
			<tr>
				<td colspane="10" style="width: 7;">
					<img src="{{ public_path().'/images/Adler-Header.jpg' }}" height="110.5%" width="110.5%" />
				</td>
			</tr>
			<tr>
				<td style="width: 7;"></td> <td style="width: 7;"></td> <td style="width: 3;"></td> <td style="width: 4;"></td> <td style="width: 4;"></td> <td></td> <td style="width: 6;"></td> <td></td> <td></td> <td></td>
			</tr>
			<tr>
				<th colspan="10" style="text-align: center;background: #A9A9A9;color: #000000;border: 5px solid #000000;">Invoice</th>
			</tr>
			<tr>
				<td></td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td>
			</tr>
			<tr>
				<th colspan="5" style="text-align: center;background: #add8e6;border: 5px solid #000000;">Bill to Party</th>
				<th colspan="4" style="text-align: center;background: #add8e6;border: 5px solid #000000;">Ship to Party</th>
				<th style="background: #add8e6;border: 2px solid #000000;"></th>
			</tr>
			<tr>
				<td colspan="5" style="border: 5px solid #000000;">Name: {{ $invoice_data['company_name'] }}</td>
				<td colspan="4" style="border: 5px solid #000000;">Name: {{ $invoice_data['company_name'] }}</td>
				<td rowspan="2" style="border: 5px solid #000000;width:17;vertical-align:middle;"><center><b> Invoice No. :</center><br/> <center>ATS/18-19/___</center>
				</b></td>
			</tr>
			<!-- <tr>
				<td colspan="5" rowspan="2" style="border: 5px solid #000000;">Address: {{ $invoice_data['billing_address'] }}</td>
				<td colspan="4" rowspan="2" style="border: 5px solid #000000;">Address: {{ $invoice_data['shipping_address'] }}</td>
			</tr> -->
			<tr>
				<td colspan="5" style="border: 5px solid #000000;vertical-align:top;" height="60">Address: {{ $invoice_data['billing_address'] }}
				</td>
				<td colspan="4" style="border: 5px solid #000000;vertical-align:top;width: 13;" height="60">Address: {{ $invoice_data['shipping_address'] }}
				</td>
			</tr>
		<!-- 	<tr>
				<td colspan="5" style="border: 5px solid #000000;"></td>
				<td colspan="4" style="border: 5px solid #000000;"></td>
				
			</tr> -->
			<tr>
				<td colspan="5" style="border: 5px solid #000000;">GST Number- {{ $invoice_data['gst_no'] }}</td>
				<td colspan="4" style="border: 5px solid #000000;">GST Number- {{ $invoice_data['gst_no'] }}</td>
				<td style="border: 5px solid #000000;vertical-align:middle;height: 20;text-align: left;"><b>Dt- _________</b></td>
			</tr>
			<tr>
				<th colspan="10" style="border: 5px solid #000000;"></th>
			</tr>
			<tr>
				<th colspan="10" style="text-align: center;background: #A9A9A9;border: 5px solid #000000;">Description</th>
			</tr>
			<tr>
				<th style="text-align: center;border: 5px solid #000000;height: 30;width: 3;">Sr. <br/> No</th>
				<th colspan="8" style="text-align: center;border: 5px solid #000000;height: 30">Description</th>
				<th style="text-align: center;border: 5px solid #000000;height: 30">Amount</th>
			</tr>
			<tr>
				<td style="text-align: center;border: 5px solid #000000;">1</td>
				<td colspan="8" style="border: 5px solid #000000;width: 5;"><b>&nbsp;Towards professional fee for recruitment (SAC Code-9997):</b></td>
				<td style="border: 5px solid #000000;"></td>
			</tr>
			<tr>
				<td style="border: 5px solid #000000;"></td> 
				<td colspan="8" style="border: 5px solid #000000;"></td> 
				<td style="border: 5px solid #000000;"></td>
			</tr>
			<tr>
				<td style="border: 5px solid #000000;"></td>
				<td colspan="6" style="border: 5px solid #000000;">Candidate Name: {{ $invoice_data['candidate_name'] }}</td>
				<td style="border: 5px solid #000000;"></td>
				<td style="border: 5px solid #000000;"></td>
				<td style="text-align: center;border: 5px solid #000000;">{{ $invoice_data['fees'] }}
				</td>
			</tr>
			<tr>
				<td style="border: 5px solid #000000;"></td>
				<td colspan="6" style="border: 5px solid #000000;">Designation: {{ $invoice_data['designation_offered'] }}</td>
				<td style="border: 5px solid #000000;"></td>
				<td style="border: 5px solid #000000;"></td>
				<td style="border: 5px solid #000000;"></td>
			</tr>
			<tr>
				<td style="border: 5px solid #000000;"></td>
				<td colspan="6" style="border: 5px solid #000000;">Job Location: {{ $invoice_data['job_location'] }}</td>
				<td style="border: 5px solid #000000;"></td>
				<td style="border: 5px solid #000000;"></td>
				<td style="border: 5px solid #000000;"></td>
			</tr>
			<tr>
				<td style="border: 5px solid #000000;"></td>
				<td colspan="6" style="border: 5px solid #000000;">Fixed CTC: {{ $invoice_data['fixed_salary'] }}</td>
				<td style="border: 5px solid #000000;"></td>
				<td style="border: 5px solid #000000;"></td>
				<td style="border: 5px solid #000000;"></td>
			</tr>
			<tr>
				<td style="border: 5px solid #000000;"></td>
				<td colspan="6" style="border: 5px solid #000000;">Percentage Charged: {{ $invoice_data['percentage_charged'] }}%</td>
				<td style="border: 5px solid #000000;"></td>
				<td style="border: 5px solid #000000;"></td>
				<td style="border: 5px solid #000000;"></td>
			</tr>
			<tr>
				<td style="border: 5px solid #000000;"></td>
				<td colspan="6" style="border: 5px solid #000000;">Joining Date: {{ date('d-m-Y', strtotime($invoice_data['joining_date'])) }}</td>
				<td style="border: 5px solid #000000;"></td>
				<td style="border: 5px solid #000000;"></td>
				<td style="border: 5px solid #000000;"></td>
			</tr>
			<tr>
				<td style="border: 5px solid #000000;"></td>
				<td colspan="6" style="border: 5px solid #000000;"></td>
				<td style="border: 5px solid #000000;"></td>
				<td style="border: 5px solid #000000;"></td>
				<td style="border: 5px solid #000000;"></td>
			</tr>
			<tr>
				<td style="border: 5px solid #000000;"></td>
				<td colspan="8" style="text-align: right;border: 5px solid #000000;">Amount Excluding GST</td>
				<td style="text-align: center;border: 5px solid #000000;">{{ $invoice_data['fees'] }}</td>
			</tr>
			@if(isset($invoice_data['gst_check']) && $invoice_data['gst_check'] == '24')
			<tr>
				<td style="border: 5px solid #000000;"></td>
				<td colspan="8" style="text-align: right;border: 5px solid #000000;">CGST  @ 9%</td>
				<td style="text-align: center;border: 5px solid #000000;">{{ $invoice_data['cgst'] }}</td>
			</tr>
			<tr>
				<td style="border: 5px solid #000000;"></td>
				<td colspan="8" style="text-align: right;border: 5px solid #000000;">SGST  @ 9%</td>
				<td style="text-align: center;border: 5px solid #000000;">{{ $invoice_data['sgst'] }}</td>
			</tr>
			@else
			<tr>
				<td style="border: 5px solid #000000;"></td>
				<td colspan="8" style="text-align: right;border: 5px solid #000000;">IGST  @ 18%</td>
				<td style="text-align: center;border: 5px solid #000000;">{{ $invoice_data['gst'] }}</td>
			</tr>
			<tr>
				<td></td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td><td></td>
			</tr>
			@endif
			<tr>
				<td style="text-align: center;border: 5px solid #000000;height: 30;vertical-align:middle;font-weight: bold;font-size: 10;">INR:</td>
				<td colspan="7" style="text-align: center;border: 5px solid #000000;height: 30;vertical-align:middle;font-weight: bold;font-size: 10;width: 20;">{{ $invoice_data['amount_in_words'] }}</td>
				<td style="text-align: center;border: 5px solid #000000;height: 30;vertical-align:middle;font-weight: bold;font-size: 10;">Total</td>
				<td style="text-align: center;border: 5px solid #000000;height: 30;vertical-align:middle;font-weight: bold;font-size: 10;">{{ $invoice_data['billing_amount'] }}</td>
			</tr>
			<tr>
				<td></td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td>
			</tr>
			<tr>
				<td colspan="10" style="font-size: 11;">Company PAN No  :  AAMCA2137K  |  GST No: 24AAMCA2137K1ZP  |  Service Category  :  Manpower Services</td>
			</tr>
			<tr>
				<td colspan="10" style="font-size: 11;">MSME Registration No. : GJ01D0136980</td>
			</tr>
			<tr>
				<td colspan="10" style="font-size: 11;"><u>Subject To Ahmedabad Jurisdiction</u></td>
			</tr>
			<tr>
				<td colspan="10" style="font-size: 11;">Please make cheque payable to: Adler Talent Solutions Pvt. Ltd.
				</td>
			</tr>
			<tr>
				<td colspan="10" style="font-size: 11;">Bank Name : ICICI Bank Limited</td>
			</tr>
			<tr>
				<td colspan="10" style="font-size: 11;">Branch Name : SG Road branch</td>
			</tr>
			<tr>
				<td colspan="10" style="font-size: 11;">Account Number : 029505002727</td>
			</tr>
			<tr>
				<td colspan="10" style="font-size: 11;">Type of Account : Current Account</td>
			</tr>
			<tr>
				<td colspan="10" style="font-size: 11;">IFSC Code : ICIC0000295</td>
			</tr>
			<tr>
				<td></td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td>
			</tr>
			<tr>
				<td colspan="10"><b>For, Adler Talent Solutions Pvt. Ltd. </b></td>
			</tr>
			<tr>
				<td>
					<img src="{{ public_path().'/images/Signature.png' }}" height="65%"/>
				</td> 
				<td style="width: 13;"></td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td>
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
				<td colspan="10" style="text-align: center;"><u>THANK YOU FOR PARTNERING WITH US!</u>
				</td>
			</tr>
		</table>
	</body>
</html>