

<table class="table table-striped table-bordered nowrap" cellspacing="0" width="100%" id="personwise-report" style="border: 1 solid #000000;">
	<thead>
		<tr>
			<td></td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td>
			<td></td> <td></td> <td></td> <td></td>
		</tr>
		<tr>
			<th rowspan="2" style="border: 1 solid #000000;text-align: center;width: 5;">Sr.No</th>
			<th rowspan="2" style="border: 1 solid #000000;text-align: center;width: 15;">Name of <br/> Employee</th>
			<th colspan="3" style="border: 1 solid #000000;text-align: center;">Q1 (April-June)</th>
			<th colspan="3" style="border: 1 solid #000000;text-align: center;">Q2 (July-Sept)</th>
			<th colspan="3" style="border: 1 solid #000000;text-align: center;">Q3 (Oct-Dec)</th>
			<th colspan="3" style="border: 1 solid #000000;text-align: center;">Q4 (Jan-March)</th>
		</tr>
		<tr>
			<th style="border: 1 solid #000000;text-align: center;width: 10;"></th>
			<th style="border: 1 solid #000000;text-align: center;width: 15;"></th>
			<th style="border: 1 solid #000000;text-align: center;width: 10;">Target</th>
			<th style="border: 1 solid #000000;text-align: center;width: 10;">Achieved</th>
			<th style="border: 1 solid #000000;text-align: center;width: 10;">Eligibility</th>
			<th style="border: 1 solid #000000;text-align: center;width: 10;">Target</th>
			<th style="border: 1 solid #000000;text-align: center;width: 10;">Achieved</th>
			<th style="border: 1 solid #000000;text-align: center;width: 10;">Eligibility</th>
			<th style="border: 1 solid #000000;text-align: center;width: 10;">Target</th>
			<th style="border: 1 solid #000000;text-align: center;width: 10;">Achieved</th>
			<th style="border: 1 solid #000000;text-align: center;width: 10;">Eligibility</th>
			<th style="border: 1 solid #000000;text-align: center;width: 10;">Target</th>
			<th style="border: 1 solid #000000;text-align: center;width: 10;">Achieved</th>
			<th style="border: 1 solid #000000;text-align: center;width: 10;">Eligibility</th>
		</tr>
	</thead>
    <?php $i = 0;?>
    <tbody>
        @foreach($eligible['eligible_data'] as $key => $value)
            <tr>
                <td style="border: 1 solid #000000;text-align: center;">{{++$i}}</td>
                <td style="border: 1 solid #000000;">{{ $key }}</td>
                @foreach($value as $k => $v)
                    <td style="border: 1 solid #000000;">{{ $v['target'] or '-' }}</td>
                    <td style="border: 1 solid #000000;">{{ $v['achieved'] or '-' }}</td>
                    <td style="border: 1 solid #000000;text-align: center;">{{ $v['eligibility'] or '-' }}</td>
                @endforeach
            </tr>
        @endforeach
    </tbody>

    <tr>
		<td></td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td>
		<td></td> <td></td> <td></td> <td></td>
	</tr>
	<tr>
		<td></td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td>
		<td></td> <td></td> <td></td> <td></td>
	</tr>
	<tr>
		<td></td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td> <td></td>
		<td></td> <td></td> <td></td> <td></td>
	</tr>

	<thead>
        <tr>
            <th rowspan="2" style="border: 1 solid #000000;text-align: center;width: 5;">Sr.No</th>
            <th rowspan="2" style="border: 1 solid #000000;text-align: center;width: 15;">Name of <br/> Employee</th>
            <th colspan="3" style="border: 1 solid #000000;text-align: center;">6 Month (Q1+Q2)</th>
            <th colspan="3" style="border: 1 solid #000000;text-align: center;">9 Month (Q1+Q2+Q3)</th>
            <th colspan="3" style="border: 1 solid #000000;text-align: center;">12 Month (Q1+Q2+Q3+Q4)</th>
        </tr>
        <tr>
        	<th style="border: 1 solid #000000;text-align: center;width: 10;"></th>
			<th style="border: 1 solid #000000;text-align: center;width: 15;"></th>
            <th style="border: 1 solid #000000;text-align: center;width: 10;">Target</th>
            <th style="border: 1 solid #000000;text-align: center;width: 10;">Achieved</th>
            <th style="border: 1 solid #000000;text-align: center;width: 10;">Eligibility</th>
            <th style="border: 1 solid #000000;text-align: center;width: 10;">Target</th>
            <th style="border: 1 solid #000000;text-align: center;width: 10;">Achieved</th>
            <th style="border: 1 solid #000000;text-align: center;width: 10;">Eligibility</th>
            <th style="border: 1 solid #000000;text-align: center;width: 10;">Target</th>
            <th style="border: 1 solid #000000;text-align: center;width: 10;">Achieved</th>
            <th style="border: 1 solid #000000;text-align: center;width: 10;">Eligibility</th>
        </tr>
    </thead>
    <?php $i = 0;?>
    <tbody>
        @foreach($eligible['eligible_detail'] as $key => $value)
            <tr>
                <td style="border: 1 solid #000000;text-align: center;">{{++$i}}</td>
                <td style="border: 1 solid #000000;">{{ $key }}</td>
                @foreach($value as $k => $v)
                    <td style="border: 1 solid #000000;">{{ $v['target'] or '-' }}</td>
                    <td style="border: 1 solid #000000;">{{ $v['achieved'] or '-' }}</td>
                    <td style="border: 1 solid #000000;text-align: center;">{{ $v['eligibility'] or '-' }}</td>
                @endforeach
            </tr>
        @endforeach
    </tbody>
</table>