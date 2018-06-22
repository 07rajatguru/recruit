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
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12">
        	<div class="box-body col-xs-4 col-sm-4 col-md-4">
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
    				<div class="form-group">
    					{{Form::select('month', $month, null, array('id'=>'month', 'class'=>'form-control'))}}
    				</div>
    			</div>
    		</div>

    		<div class="form-group quater">
    			<div class="box-body col-xs-3 col-sm-3 col-md-3">
    				<div class="form-group">
    					{{Form::select('quater', $quater, null, array('id'=>'quater', 'class'=>'form-control'))}}
    				</div>
    			</div>
    		</div>

    		<div class="form-group year">
    			<div class="box-body col-xs-3 col-sm-3 col-md-3">
    				<div class="form-group">
    					{{Form::select('year', $year, null, array('id'=>'year', 'class'=>'form-control'))}}
    				</div>
    			</div>
    		</div>

    		<div class="box-body col-xs-2 col-sm-2 col-md-2">
    			<div class="form-group">
    				{!! Form::submit('Select', ['class' => 'btn btn-primary']) !!}
    			</div>
    		</div>

    	</div>
    </div>
    
@stop

@section('customscripts')
	<script type="text/javascript">

		$(document).ready(function(){
            $("#from_date").datepicker({
                format: "dd-mm-yyyy",
                autoclose: true,
            });
             $("#to_date").datepicker({
                format: "dd-mm-yyyy",
                autoclose: true,
            });

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
			else if(selectlist == 3){
				$(".custom").hide();
				$(".month").hide();
				$(".year").show();
				$(".quater").hide();
			}
			else{
				$(".custom").hide();
				$(".month").hide();
				$(".year").hide();
				$(".quater").hide();
			}
		}

	</script>

@endsection