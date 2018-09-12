@extends('adminlte::page')

@section('title', 'Selection Report')

@section('content_header')
    <h1></h1>

@stop

@section('content')

	<div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Selection Report</h2>
            </div>
            <div class="pull-right col-md-2">
                <a class="btn btn-success btn-block" href="javascript:void(0);" onClick="export_data()">Export</a>
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12">
        	<div class="box-body col-xs-3 col-sm-3 col-md-3">
        		<div class="form-group">
		        	{{Form::select('select',$select,2 , array('id'=>'select','class'=>'form-control','onchange'=>'getSelect()'))}}
	        	</div>
    		</div>

            <div class="box-body col-xs-3 col-sm-3 col-md-3 custom_from_date">
                <div class="form-group">
                    {{Form::text('from_date',null , array('id'=>'from_date', 'placeholder' => 'From Date', 'class'=>'form-control'))}}
                </div>
            </div>

            <div class="box-body col-xs-3 col-sm-3 col-md-3 custom_to_date">
                <div class="form-group">
                    {{Form::text('to_date',null , array('id'=>'to_date', 'placeholder' => 'To Date', 'class'=>'form-control'))}}
                </div>
            </div>

            <div class="box-body col-xs-3 col-sm-3 col-md-3 month">
                <div class="form-group">
                    {{Form::select('month', $month_array, null, array('id'=>'month', 'class'=>'form-control'))}}
                </div>
            </div>

            <div class="box-body col-xs-3 col-sm-3 col-md-3 quater">
                <div class="form-group">
                    {{Form::select('quater', $quater, 0, array('id'=>'quater', 'class'=>'form-control'))}}
                </div>
            </div>

            <div class="box-body col-xs-2 col-sm-2 col-md-2 year">
                <div class="form-group">
                    {{Form::select('year', $year_array, $default, array('id'=>'year', 'class'=>'form-control'))}}
                </div>
            </div>

    		<div class="box-body col-xs-3 col-sm-3 col-md-3">
    			<div class="form-group">
    				{!! Form::submit('Select', ['class' => 'btn btn-primary', 'onclick' => 'select_data()']) !!}
    			</div>
    		</div>

    	</div>
    </div>

    <br/>

    <div class = "table-responsive">
    <table class="table table-striped table-bordered nowrap" cellspacing="0" width="100%" id="selection_report_table">
    	<thead>
            <tr>
    		    <th>No</th>
    		    <th>Candidate <br/> Name</th>
    		    <th>Company <br/> Name</th>
    		    <th>Position <br/>/Dept</th>
    		    <th>Salary <br/> Offered <br/>(Fixed)</th>
    		    <th>Billing(Salary <br/> Offered <br/>*Percentage <br/> Charged)</th>
    		    <th>GST @ <br/>18%(Billing <br/>Amount*18/100)</th>
    		    <th>Invoice <br/> Raised <br/> (Billing+GST)</th>
    		    <th>Payment Expected <br/> including GST <br/> (Billing*90/100)+GST</th>
    		    <th>Joining date</th>
    		    <th>Contact Person</th>
    		    <th>Location</th>
            </tr>
    	</thead>
    	<?php $i=0;?>
    	<tbody>
    		@foreach($selection as $selections)
            <tr>
    		    <td>{{ ++$i }}</td>
    		    <td>{{ $selections['candidate_name'] or '' }}</td>
    		    <td style="white-space: pre-wrap; word-wrap: break-word;">{{ $selections['company_name'] or '' }}</td>
    		    <td style="white-space: pre-wrap; word-wrap: break-word;">{{ $selections['position'] or '' }}</td>
    		    <td style="white-space: pre-wrap; word-wrap: break-word;">{{ $selections['fixed_salary'] or '' }}</td>
    		    <td>{{ $selections['billing'] or '' }}</td>
    		    <td>{{ $selections['gst'] or '' }}</td>
    		    <td>{{ $selections['invoice'] or '' }}</td>
    		    <td>{{ $selections['payment'] or '' }}</td>
    		    <td>{{ $selections['joining_date'] or '' }}</td>
    		    <td>{{ $selections['contact_person'] or '' }}</td>
    		    <td>{{ $selections['location'] or '' }}</td>
            </tr>
    		@endforeach
    	</tbody>
    </table>
	</div>
    
@stop

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

            var table = jQuery('#selection_report_table').DataTable( {
                responsive: true,
                "pageLength": 100,
                stateSave: true
            });
            new jQuery.fn.dataTable.FixedHeader( table );

            getSelect();
            //select_data();
        });
		
		function getSelect() {
			var selectlist = $("#select").val();

			if (selectlist == 0){
				$(".custom_from_date").show();
                $(".custom_to_date").show();
				$(".month").hide();
				$(".year").hide();
				$(".quater").hide();
			}
			else if(selectlist == 1){
                $(".custom_from_date").hide();
                $(".custom_to_date").hide();
				$(".month").show();
				$(".year").show();
				$(".quater").hide();
			}
			else if(selectlist == 2){
                $(".custom_from_date").hide();
                $(".custom_to_date").hide();
				$(".month").hide();
				$(".year").show();
				$(".quater").show();
			}
			else if(selectlist == 3){
                $(".custom_from_date").hide();
                $(".custom_to_date").hide();
				$(".month").hide();
				$(".year").show();
				$(".quater").hide();
			}
			else{
                $(".custom_from_date").hide();
                $(".custom_to_date").hide();
				$(".month").hide();
				$(".year").hide();
				$(".quater").hide();
			}
		}

        function select_data(){
           
            var select = $("#select").val();
            var month = $("#month").val();
            var year = $("#year").val();
            var from_date = $("#from_date").val();
            var to_date = $("#to_date").val();
            var quater = $("#quater").val();

            var url = '/selectionreport';

            if (select == 0){
            var form = $('<form action="' + url + '" method="post">' +
                '<input type="hidden" name="_token" value="<?php echo csrf_token() ?>">' +
                '<input type="text" name="select" value="'+select+'" />'+
                '<input type="text" name="from_date" value="'+from_date+'" />' +
                '<input type="text" name="to_date" value="'+to_date+'" />' +
                '</form>');

            $('body').append(form);
            form.submit();
            }

            else if (select == 1){
            var form = $('<form action="' + url + '" method="post">' +
                '<input type="hidden" name="_token" value="<?php echo csrf_token() ?>">' +
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
                '<input type="text" name="select" value="'+select+'" />'+
                '<input type="text" name="quater" value="'+quater+'" />' +
                '<input type="text" name="year" value="'+year+'" />' +
                '</form>');

            $('body').append(form);
            form.submit();
            }

            else if (select == 3){
            var form = $('<form action="' + url + '" method="post">' +
                '<input type="hidden" name="_token" value="<?php echo csrf_token() ?>">' +
                '<input type="text" name="select" value="'+select+'" />'+
                '<input type="text" name="year" value="'+year+'" />' +
                '</form>');

            $('body').append(form);
            form.submit();
            }

        }

        function export_data(){

            var select = $("#select").val();
            var month = $("#month").val();
            var year = $("#year").val();
            var from_date = $("#from_date").val();
            var to_date = $("#to_date").val();
            var quater = $("#quater").val();

            var url = 'selectionreport/export';

            if (select == 0){
            var form = $('<form action="' + url + '" method="post">' +
                '<input type="hidden" name="_token" value="<?php echo csrf_token() ?>">' +
                '<input type="text" name="select" value="'+select+'" />'+
                '<input type="text" name="from_date" value="'+from_date+'" />' +
                '<input type="text" name="to_date" value="'+to_date+'" />' +
                '</form>');

            $('body').append(form);
            form.submit();
            }

            else if (select == 1){
            var form = $('<form action="' + url + '" method="post">' +
                '<input type="hidden" name="_token" value="<?php echo csrf_token() ?>">' +
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
                '<input type="text" name="select" value="'+select+'" />'+
                '<input type="text" name="quater" value="'+quater+'" />' +
                '<input type="text" name="year" value="'+year+'" />' +
                '</form>');

            $('body').append(form);
            form.submit();
            }

            else if (select == 3){
            var form = $('<form action="' + url + '" method="post">' +
                '<input type="hidden" name="_token" value="<?php echo csrf_token() ?>">' +
                '<input type="text" name="select" value="'+select+'" />'+
                '<input type="text" name="year" value="'+year+'" />' +
                '</form>');

            $('body').append(form);
            form.submit();
            }
        }
	</script>

@endsection