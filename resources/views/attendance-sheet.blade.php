@if(isset($list) && sizeof($list) > 0)
<?php
    $year_display = substr($year, -2);
    $month_display = date('F', mktime(0, 0, 0, $month, 10));
?>
	<table class="table table-striped table-bordered nowrap" cellspacing="0" id="attendance_table">
		<thead>
			<tr>
                <th style="border: 1px solid black;text-align: center;"></th>
                <th style="border: 1px solid black;text-align: center;"></th>
                <th style="border: 1px solid black;padding-left: 700px;"colspan="36">Adler - Attendance Sheet - {{ $month_display }}' {{ $year_display }}</th>
            </tr>
            <tr>
                <th style="border: 1px solid black;text-align: center;" rowspan="2"><br/><br/>Sr. No.</th>
                <th style="border: 1px solid black;background-color:#d6e3bc;">ADLER EMPLOYEES
                </th>
                <th colspan="36" style="border: 1px solid black;padding-left: 820px;">DATE</th>
            </tr>

            <th style="border: 1px solid black;background-color:#d6e3bc">NAME OF PERSON</th>
            <th style="border: 1px solid black;">Department</th>
            <th style="border: 1px solid black;">Working Hours</th>
            <th style="border: 1px solid black;">Date of Joining</th>

            @foreach($list as $key => $value)
                @foreach($value as $key1=>$value1)
                    <?php
                        $con_dt = date("j", mktime(0, 0, 0, 0, $key1, 0));
                    ?>
                    <th style="border: 1px solid black;width: 1px;">{{ $con_dt }}</th>
                @endforeach
            @endforeach
		</thead>
		<tbody>
		</tbody>
	</table>
@endif