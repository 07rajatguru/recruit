@extends('adminlte::page')

@section('title', 'User Report')

@section('content_header')
    <h1></h1>

@stop

@section('content')

	<div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Userwise Report</h2>
            </div>

            @permission(('display-user-report'))
                <div class="pull-right col-md-2">
                    <a class="btn btn-success btn-block" href="javascript:void(0)" onClick="export_data()">Export</a>
                </div>
            @endpermission
        </div>

        <div class="col-xs-12 col-sm-12 col-md-12">

            <div class="box-body col-xs-2 col-sm-2 col-md-2">
                <div class="form-group">
                    {{Form::select('user',$users,null,array('id'=>'user','class'=>'form-control'))}}
                </div>
            </div>

        	<div class="box-body col-xs-2 col-sm-2 col-md-2">
        		<div class="form-group">
		        	{{Form::select('select',$select,2 , array('id'=>'select','class'=>'form-control','onchange'=>'getSelect()'))}}
	        	</div>
    		</div>

    		<div class="form-group custom">
    			<div class="box-body col-xs-3 col-sm-3 col-md-3">
        			<div class="form-group">
		        		{{Form::text('from_date',null , array('id'=>'from_date', 'placeholder' => 'From Date', 'class'=>'form-control'))}}
	        		</div>
    			</div>

    			<div class="box-body col-xs-3 col-sm-3 col-md-3">
	        		<div class="form-group">
			        	{{Form::text('to_date',null , array('id'=>'to_date', 'placeholder' => 'To Date', 'class'=>'form-control'))}}
	    	    	</div>
    			</div>
    		</div>

    		<div class="form-group month">
    			<div class="box-body col-xs-3 col-sm-3 col-md-3">
    				{{Form::select('month', $month_array, null, array('id'=>'month', 'class'=>'form-control'))}}
    			</div>
    		</div>
    		
    		<div class="form-group quater">
    			<div class="box-body col-xs-3 col-sm-3 col-md-3">
    				{{Form::select('quater', $quater, null, array('id'=>'quater', 'class'=>'form-control'))}}
    			</div>
    		</div>

    		<div class="form-group year">
    			<div class="box-body col-xs-3 col-sm-3 col-md-3">
    				{{Form::select('year', $year_array, $default, array('id'=>'year', 'class'=>'form-control'))}}
    			</div>
    		</div>

    		<div class="form-group">
    			<div class="box-body col-xs-2 col-sm-2 col-md-2">
    				{!! Form::submit('Select', ['class' => 'btn btn-primary', 'onclick' => 'select_data()']) !!}
    			</div>
    		</div>
    	</div>
    </div>

    <br/>

    <div class = "table-responsive">
    <table class="table table-striped table-bordered nowrap" cellspacing="0" width="100%" id="user_report_table">
    	<thead>
    		<tr>
    			<td colspan="8" style="text-align: center;">{{ $user_name }} - {{ $total }}</td>
    		</tr>
            <tr>
    		    <th>No</th>
    		    <th>Candidate <br/>Name</th>
    		    <th>Company <br/>Name</th>
    		    <th>Position/Dept</th>
    		    <th>Salary <br/>Offered(fixed)</th>
    		    <th>Billing</th>
    		    <th>Joining <br/>Date</th>
    		    <th>Efforts with</th>
    		    {{--<th>Remarks</th>--}}
            </tr>
    	</thead>
    	<?php $i=0;?>
    	<tbody>
    		@foreach($userwise as $users)
    		<tr>
    			<td>{{ ++$i }}</td>
    			<td>{{ $users['candidate_name'] or '' }}</td>
    			<td>{{ $users['company_name'] or '' }}</td>
    			<td>{{ $users['position'] or '' }}</td>
    			<td>{{ $users['fixed_salary'] or '' }}</td>
    			<td>{{ $users['billing'] or '' }}</td>
    			<td>{{ $users['joining_date'] or '' }}</td>
    			<td>{{ $users['efforts'] or '' }}</td>
    			{{--<td>{{ $users['remark'] or '' }}</td>--}}
    		</tr>
    		@endforeach
    	</tbody>
    </table>
	</div>

@endsection

@section('customscripts')
	<script type="text/javascript">

		$(document).ready(function(){
			 $("#from_date").datepicker({
                format: "yyyy-mm-dd",
                autoclose: true,
            });
             $("#to_date").datepicker({
                format: "yyyy-mm-dd",
                autoclose: true,
            });

			var table = jQuery('#user_report_table').DataTable( {
                responsive: true,
                "pageLength": 100,
                stateSave: true
            });

            if ( ! table.data().any() ) {
            }
            else{
                new jQuery.fn.dataTable.FixedHeader( table );
            }

			getSelect();
		});

		function getSelect() {
			var selectlist = $("#select").val();

			if (selectlist == 0){
				$(".custom").show();
				$(".month").hide();
				$(".year").hide();
				$(".quater").hide();
			}
			else if(selectlist == 1){
				$(".custom").hide();
				$(".month").show();
				$(".year").show();
				$(".quater").hide();
			}
			else if(selectlist == 2){
				$(".custom").hide();
				$(".month").hide();
				$(".year").show();
				$(".quater").show();
			}
			else{
				$(".custom").hide();
				$(".month").hide();
				$(".year").hide();
				$(".quater").hide();
			}
		}

		function select_data(){
           
            var user= $("#user").val();
            var select = $("#select").val();
            var month = $("#month").val();
            var from_date = $("#from_date").val();
            var to_date = $("#to_date").val();
            var year = $("#year").val();
            var quater = $("#quater").val();
            var app_url = "{!! env('APP_URL'); !!}";

            var url = app_url+'/userreport';

            if (select == 0){
            var form = $('<form action="' + url + '" method="post">' +
                '<input type="hidden" name="_token" value="<?php echo csrf_token() ?>">' +
                '<input type="text" name="user" value="'+user+'" />' +
                '<input type="text" name="select" value="'+select+'" />'+
                '<input type="text" name="from_date" value="'+from_date+'" />' +
                '<input type="text" name="to_date" value="'+to_date+'" />' +
                '</form>');

            $('body').append(form);
            form.submit();
            }

            if (select == 1){
            var form = $('<form action="' + url + '" method="post">' +
                '<input type="hidden" name="_token" value="<?php echo csrf_token() ?>">' +
                '<input type="text" name="user" value="'+user+'" />' +
                '<input type="text" name="select" value="'+select+'" />'+
                '<input type="text" name="month" value="'+month+'" />' +
                '<input type="text" name="year" value="'+year+'" />' +
                '</form>');

            $('body').append(form);
            form.submit();
            }

            else if (select == 2){
            var form = $('<form action="' + url + '" method="post">' +
                '<input type="hidden" name="_token" value="<?php echo csrf_token() ?>">' +
                '<input type="text" name="user" value="'+user+'" />' +
                '<input type="text" name="select" value="'+select+'" />'+
                '<input type="text" name="quater" value="'+quater+'" />' +
                '<input type="text" name="year" value="'+year+'" />' +
                '</form>');

            $('body').append(form);
            form.submit();
            }
        }

        function export_data(){
           
            var user= $("#user").val();           
            var select = $("#select").val();
            var month = $("#month").val();
            var from_date = $("#from_date").val();
            var to_date = $("#to_date").val();
            var year = $("#year").val();
            var quater = $("#quater").val();
            var app_url = "{!! env('APP_URL'); !!}";

            var url = app_url+'/userreport/export';

            if (select == 0){
            var form = $('<form action="' + url + '" method="post">' +
                '<input type="hidden" name="_token" value="<?php echo csrf_token() ?>">' +
                '<input type="text" name="user" value="'+user+'" />' +
                '<input type="text" name="select" value="'+select+'" />'+
                '<input type="text" name="from_date" value="'+from_date+'" />' +
                '<input type="text" name="to_date" value="'+to_date+'" />' +
                '</form>');

            $('body').append(form);
            form.submit();
            }

            if (select == 1){
            var form = $('<form action="' + url + '" method="post">' +
                '<input type="hidden" name="_token" value="<?php echo csrf_token() ?>">' +
                '<input type="text" name="user" value="'+user+'" />' +
                '<input type="text" name="select" value="'+select+'" />'+
                '<input type="text" name="month" value="'+month+'" />' +
                '<input type="text" name="year" value="'+year+'" />' +
                '</form>');

            $('body').append(form);
            form.submit();
            }

            else if (select == 2){
            var form = $('<form action="' + url + '" method="post">' +
                '<input type="hidden" name="_token" value="<?php echo csrf_token() ?>">' +
                '<input type="text" name="user" value="'+user+'" />' +
                '<input type="text" name="select" value="'+select+'" />'+
                '<input type="text" name="quater" value="'+quater+'" />' +
                '<input type="text" name="year" value="'+year+'" />' +
                '</form>');

            $('body').append(form);
            form.submit();
            }
        }
	</script>
@endsection