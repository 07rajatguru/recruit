@extends('adminlte::page')

@section('title', 'Leave Balance')

@section('content_header')
    <h1></h1>
@stop

@section('content')

    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
    @endif

    @if ($message = Session::get('error'))
        <div class="alert alert-error">
            <p>{{ $message }}</p>
        </div>
    @endif

	<div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Month Wise Leave Balance</h2>
            </div>
            <div class="pull-right">
                <a class="btn btn-success" href="javascript:void(0);" onClick="export_data()">Download Excel</a>
            </div>
        </div>
    </div>

    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="box-body col-xs-2 col-sm-2 col-md-2">
            <div class="form-group">
                {{Form::select('month',$month_array, $month, array('id'=>'month','class'=>'form-control'))}}
            </div>
        </div>

        <div class="box-body col-xs-2 col-sm-2 col-md-2">
            <div class="form-group">
                {{Form::select('year',$year_array, $year, array('id'=>'year','class'=>'form-control'))}}
            </div>
        </div>

        <div class="box-body col-xs-1 col-sm-1 col-md-1">
            <div class="form-group">
                {!! Form::submit('Select', ['class' => 'btn btn-primary', 'onclick' => 'select_data()']) !!}
            </div>
        </div>
    </div>

    <div id="before_table" style="display:none;">
        <table class="table table-striped table-bordered nowrap" cellspacing="0" width="100%" id="user_leave_table_before">
        	<thead>
        		<tr>
    	    		<th>No</th>
                    <th width="15%">User Name</th>
                    <th>Total PL</th>
                    <th>Opted PL</th>
                    <th>PL Balance</th>
                    <th>Total SL</th>
                    <th>Opted SL</th>
                    <th>SL Balance</th>
    	    	</tr>
        	</thead>

        	<?php $i=0; ?>
        	<tbody>
        		@foreach($user_leave_data as $key => $value)
    	    		<tr>
    		    		<td>{{ ++$i }}</td>
    		    		<td>{{ $value['user_name'] }}</td>
    		    		<td>{{ $value['pl_total'] }}</td>
    		    		<td>{{ $value['pl_taken'] }}</td>
    		    		<td>{{ $value['pl_remaining'] }}</td>
                        <td>{{ $value['sl_total'] }}</td>
                        <td>{{ $value['sl_taken'] }}</td>
                        <td>{{ $value['sl_remaining'] }}</td>
    		    	</tr>
        		@endforeach
        	</tbody>
        </table>
    </div>

    <div id="after_table">
        <table class="table table-striped table-bordered nowrap" cellspacing="0" width="100%" id="user_leave_table_after">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Action</th>
                    <th width="15%">User Name</th>
                    <th>Total PL</th>
                    <th>Opted PL</th>
                    <th>PL Balance</th>
                    <!-- <th>Total SL</th>
                    <th>Opted SL</th>
                    <th>SL Balance</th> -->
                    <th>Edited By</th>
                </tr>
            </thead>

            <?php $i=0; ?>
            <tbody>
                @foreach($user_leave_data as $key => $value)
                    @if($value['edited_by'] != '')
                        <tr style="background-color:#ACACAC;">
                            <td>{{ ++$i }}</td>
                            <td>
                                <a class="fa fa-edit" href="{{ route('leave.userwiseedit',['id' => \Crypt::encrypt($value['id'])),'month' => $month,'year' => $year]) }}" title="Edit"></a>
                            </td>
                            <td>{{ $value['user_name'] }}</td>
                            <td>{{ $value['pl_total'] }}</td>
                            <td>{{ $value['pl_taken'] }}</td>
                            <td>{{ $value['pl_remaining'] }}</td>
                            <!-- <td>{{ $value['sl_total'] }}</td>
                            <td>{{ $value['sl_taken'] }}</td>
                            <td>{{ $value['sl_remaining'] }}</td> -->
                            <td>{{ $value['edited_by'] }}</td>
                        </tr>
                    @else
                        <tr>
                            <td>{{ ++$i }}</td>
                            <td>
                                <a class="fa fa-edit" href="{{ route('leave.userwiseedit',['id' => \Crypt::encrypt($value['id'])),'month' => $month,'year' => $year]) }}" title="Edit"></a>
                            </td>
                            <td>{{ $value['user_name'] }}</td>
                            <td>{{ $value['pl_total'] }}</td>
                            <td>{{ $value['pl_taken'] }}</td>
                            <td>{{ $value['pl_remaining'] }}</td>
                            <!-- <td>{{ $value['sl_total'] }}</td>
                            <td>{{ $value['sl_taken'] }}</td>
                            <td>{{ $value['sl_remaining'] }}</td> -->
                            <td></td>
                        </tr>
                    @endif
                @endforeach
            </tbody>
        </table>
    </div>
@stop

@section('customscripts')
    <script type="text/javascript">
        jQuery(document).ready(function() {

            $("#month").select2();
            $("#year").select2();

            displayDataTable();
        });

        function select_data() {

            var app_url = "{!! env('APP_URL'); !!}";
            var url = app_url+'/monthwise-leave-balance';

            var month = $("#month").val();
            var year = $("#year").val();

            var form = $('<form action="' + url + '" method="post">' +
                '<input type="hidden" name="_token" value="<?php echo csrf_token() ?>">' +
                '<input type="hidden" name="month" value="'+month+'" />' +
                '<input type="hidden" name="year" value="'+year+'" />' +
                '</form>');

            $('body').append(form);
            form.submit();
        }

        function export_data() {

            var month = $("#month").val();
            var year = $("#year").val();

            var url = 'userwiseleave/export';

            var form = $('<form action="'+url+ '" method="post">' +
            '<input type="hidden" name="_token" value="<?php echo csrf_token() ?>">' +
            '<input type="hidden" name="month" value="'+month+'" />' +
            '<input type="hidden" name="year" value="'+year+'" />' +
            '</form>');

            $('body').append(form);
            form.submit();
        }

        function displayDataTable() {

            var month = $("#month").val();
            var year = $("#year").val();

            if(month >= 8 && year >= 2022) {

                var table = jQuery('#user_leave_table_after').DataTable({
                    responsive: true,
                    "pageLength": 100,
                    stateSave: true
                });
                
                if ( ! table.data().any() ) {
                }
                else {
                    new jQuery.fn.dataTable.FixedHeader( table );
                }

                $('#before_table').hide();
            }
            else {

                var table1 = jQuery('#user_leave_table_before').DataTable({
                    responsive: true,
                    "pageLength": 100,
                    stateSave: true
                });
                
                if ( ! table1.data().any() ) {
                }
                else {
                    new jQuery.fn.dataTable.FixedHeader( table1 );
                }

                $('#before_table').show();
                $('#after_table').hide();
            }
        }
    </script>
@endsection