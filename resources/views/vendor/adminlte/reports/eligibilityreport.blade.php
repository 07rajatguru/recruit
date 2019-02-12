@extends('adminlte::page')

@section('title', 'Eligibility Report')

@section('content_header')
    <h1></h1>
@stop

@section('content')

	<div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Eligibility Report</h2>
            </div>

            <div class="pull-right">
                <a class="btn btn-success" href="{{ route('report.eligibilityreportadd') }}"> Add Eligibility</a>
            </div>
        </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="box-body col-xs-3 col-sm-3 col-md-3">
            <div class="form-group">
            	<strong>Select Year:</strong>
                {{Form::select('year',$year_array, $year, array('id'=>'year','class'=>'form-control'))}}
            </div>
        </div>

        <div class="box-body col-xs-2 col-sm-2 col-md-2">
            <div class="form-group" style="margin-top: 19px;">
                {!! Form::submit('Select', ['class' => 'btn btn-primary', 'onclick' => 'select_data()']) !!}
            </div>
        </div>
        <div class="pull-right col-md-2">
            <a class="btn btn-success btn-block" href="javascript:void(0);" onClick="export_data()">Export</a>
        </div>
    </div>
    <br/>
    <div class="col-xs-12 col-sm-12 col-md-12">
		<table class="table table-striped table-bordered nowrap" cellspacing="0" width="100%" id="personwise-report" style="border: 2px solid black;">
			<thead>
				<tr>
					<th rowspan="2" style="border: 1px solid black;text-align: center;">Sr.No</th>
					<th rowspan="2" style="border: 1px solid black;text-align: center;">Name of <br/> Employee</th>
					<th colspan="3" style="border: 1px solid black;text-align: center;">Q1 (April-June)</th>
					<th colspan="3" style="border: 1px solid black;text-align: center;">Q2 (July-Sept)</th>
					<th colspan="3" style="border: 1px solid black;text-align: center;">Q3 (Oct-Dec)</th>
					<th colspan="3" style="border: 1px solid black;text-align: center;">Q4 (Jan-March)</th>
				</tr>
				<tr>
					<th style="border: 1px solid black;text-align: center;">Target</th>
					<th style="border: 1px solid black;text-align: center;">Achieved</th>
					<th style="border: 1px solid black;text-align: center;">Eligibility</th>
					<th style="border: 1px solid black;text-align: center;">Target</th>
					<th style="border: 1px solid black;text-align: center;">Achieved</th>
					<th style="border: 1px solid black;text-align: center;">Eligibility</th>
					<th style="border: 1px solid black;text-align: center;">Target</th>
					<th style="border: 1px solid black;text-align: center;">Achieved</th>
					<th style="border: 1px solid black;text-align: center;">Eligibility</th>
					<th style="border: 1px solid black;text-align: center;">Target</th>
					<th style="border: 1px solid black;text-align: center;">Achieved</th>
					<th style="border: 1px solid black;text-align: center;">Eligibility</th>
				</tr>
			</thead>
            <?php $i = 0;?>
            <tbody>
                @foreach($eligible_data as $key => $value)
                    <tr>
                        <td>{{++$i}}</td>
                        <td>{{ $key }}</td>
                        @foreach($value as $k => $v)
                            <td>{{ $v['target'] or '-' }}</td>
                            <td>{{ $v['achieved'] or '-' }}</td>
                            <td>{{ $v['eligibility'] or '-' }}</td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
		</table>
	</div>

    <div class="col-xs-12 col-sm-12 col-md-12">
        <table class="table table-striped table-bordered nowrap" cellspacing="0" width="100%" id="personwise-report" style="border: 2px solid black;">
            <thead>
                <tr>
                    <th rowspan="2" style="border: 1px solid black;text-align: center;">Sr.No</th>
                    <th rowspan="2" style="border: 1px solid black;text-align: center;">Name of <br/> Employee</th>
                    <th colspan="3" style="border: 1px solid black;text-align: center;">6 Month (Q1+Q2)</th>
                    <th colspan="3" style="border: 1px solid black;text-align: center;">9 Month (Q1+Q2+Q3)</th>
                    <th colspan="3" style="border: 1px solid black;text-align: center;">12 Month (Q1+Q2+Q3+Q4)</th>
                </tr>
                <tr>
                    <th style="border: 1px solid black;text-align: center;">Target</th>
                    <th style="border: 1px solid black;text-align: center;">Achieved</th>
                    <th style="border: 1px solid black;text-align: center;">Eligibility</th>
                    <th style="border: 1px solid black;text-align: center;">Target</th>
                    <th style="border: 1px solid black;text-align: center;">Achieved</th>
                    <th style="border: 1px solid black;text-align: center;">Eligibility</th>
                    <th style="border: 1px solid black;text-align: center;">Target</th>
                    <th style="border: 1px solid black;text-align: center;">Achieved</th>
                    <th style="border: 1px solid black;text-align: center;">Eligibility</th>
                </tr>
            </thead>
            <?php $i = 0;?>
            <tbody>
                @foreach($eligible_detail as $key => $value)
                    <tr>
                        <td>{{++$i}}</td>
                        <td>{{ $key }}</td>
                        @foreach($value as $k => $v)
                            <td>{{ $v['target'] or '-' }}</td>
                            <td>{{ $v['achieved'] or '-' }}</td>
                            <td>{{ $v['eligibility'] or '-' }}</td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@stop

@section('customscripts')
<script type="text/javascript">

	function select_data(){
        var year = $("#year").val();

        var url = '/eligibility-report';

        var form = $('<form action="'+url+ '" method="post">' +
            '<input type="hidden" name="_token" value="<?php echo csrf_token() ?>">' +
            '<input type="text" name="year" value="'+year+'" />' +
            '</form>');

        $('body').append(form);
        form.submit();
    }

    function export_data(){
            var year = $("#year").val();

            var url = '/eligibility-report/export';

            var form = $('<form action="'+url+ '" method="post">' +
                '<input type="hidden" name="_token" value="<?php echo csrf_token() ?>">' +
                '<input type="text" name="year" value="'+year+'" />' +
                '</form>');

            $('body').append(form);
            form.submit();
        }
</script>
@endsection