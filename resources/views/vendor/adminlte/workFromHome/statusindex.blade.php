@extends('adminlte::page')

@section('title', 'Work From Home Request')

@section('content_header')
@stop

@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Work From Home Request ({{ $count }})</h2>
            </div>
            <div class="pull-right">
                <a class="btn btn-success" href="{{ route('workfromhome.add') }}">Add New Request
                </a>
            </div>
        </div>
    </div>

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
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="col-xs-2 col-sm-2 col-md-2">
                <div class="form-group">
                    {{Form::select('month',$month_array, $month, array('id'=>'month','class'=>'form-control'))}}
                </div>
            </div>
            <div class="col-xs-2 col-sm-2 col-md-2">
                <div class="form-group">
                    {{Form::select('year',$year_array, $year, array('id'=>'year','class'=>'form-control'))}}
                </div>
            </div>
            <div class="col-xs-1 col-sm-1 col-md-1">
                <div class="form-group">
                    {!! Form::submit('Select', ['class' => 'btn btn-primary', 'onclick' => 'select_data()']) !!}
                </div>
            </div>
        </div>
    </div><br/>

    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="col-xs-1 col-sm-1 col-md-1">
                <a href="{{ route('workfromhome.status',array('pending',$month,$year)) }}" style="text-decoration: none;color: black;"><div style="height:35px;background-color:#8FB1D5;font-weight: 600;border-radius: 50px;padding:9px 0px 0px 9px;text-align: center;width:120px;margin-left: -5px;" title="Pending">Pending ({{ $pending }})</div></a>
            </div>

            <div class="col-xs-1 col-sm-1 col-md-1">
                <a href="{{ route('workfromhome.status',array('approved',$month,$year)) }}" style="text-decoration: none;color: black;"><div style="height:35px;background-color:#32CD32;font-weight: 600;border-radius: 50px;padding:9px 0px 0px 9px;text-align: center;width:120px;margin-left: 25px;" title="Approved">Approved ({{ $approved }})</div></a>
            </div>

            <div class="col-xs-1 col-sm-1 col-md-1">
                <a href="{{ route('workfromhome.status',array('rejected',$month,$year)) }}" style="text-decoration: none;color: black;"><div style="height:35px;background-color:#F08080;font-weight: 600;border-radius: 50px;padding:9px 0px 0px 9px;text-align: center;width:120px;margin-left: 55px;" title="Rejected">Rejected ({{ $rejected }})</div></a>
            </div>   
        </div>
    </div>
    <br/><br/>
    
    <table class="table table-striped table-bordered nowrap" cellspacing="0" width="100%" id="work_from_home_table">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="8%">Action</th>
                <th width="11%">From Date</th>
                <th width="11%">To Date</th>
                <th width="13%">Username</th>
                <th>Subject</th>
                <th width="11%">Status</th>
            </tr>
        </thead>
        <?php $i=0; ?>
        <tbody>
            @if(isset($work_from_home_res) && $work_from_home_res != '')
                @foreach ($work_from_home_res as $key => $value)
                    <tr>
                        <td>{{ ++$i }}</td>
                        <td>
                            <a class="fa fa-circle" href="{{ route('workfromhome.show',\Crypt::encrypt($value['id'])) }}" title="Show"></a>

                            <a class="fa fa-edit" href="{{ route('workfromhome.edit',\Crypt::encrypt($value['id'])) }}" title="Edit"></a>
                            
                            @permission(('work-from-home-delete'))
                                @include('adminlte::partials.deleteModal', ['data' => $value, 'name' => 'workfromhome','display_name'=>'Work From Home Request'])
                            @endpermission
                        </td>

                        <td>{{ $value['from_date'] }}</td>
                        <td>{{ $value['to_date'] }}</td>
                        <td>{{ $value['user_name'] }}</td>
                        <td>{{ $value['subject'] }}</td>

                        @if($value['status'] == 0)
                            <td style="background-color:#8FB1D5;">Pending</td>
                        @elseif($value['status'] == 1)
                            <td style="background-color:#32CD32;">Approved</td>
                        @elseif($value['status'] == 2)
                            <td style="background-color:#F08080;">Rejected</td>
                        @endif
                    </tr>
                @endforeach
            @endif
        </tbody>        
    </table>

    <input type="hidden" name="status" id="status" value="{{ $status }}">
@stop

@section('customscripts')
    <script type="text/javascript">
        jQuery(document).ready(function() {

            var table = jQuery('#work_from_home_table').DataTable({
                responsive: true,
                "pageLength": 100,
                stateSave: true,
                "columnDefs": [ {orderable: false, targets: [1]} ]
            });

            if ( ! table.data().any() ) {
            }
            else {
                new jQuery.fn.dataTable.FixedHeader( table );
            }

            $("#month").select2();
            $("#year").select2();
        });

        function select_data() {

            var app_url = "{!! env('APP_URL'); !!}";
            var month = $("#month").val();
            var year = $("#year").val();
            var status = $("#status").val();

            if(status == 0) {
                status = 'pending';
            }
            else if(status == 1) {
                status = 'approved';
            }
            else if(status == 2) {
                status = 'rejected';
            }

            var url = app_url+'/work-from-home/'+status+'/'+month+'/'+year;

            var form = $('<form action="' + url + '" method="post">' +
                '<input type="hidden" name="_token" value="<?php echo csrf_token() ?>">' +
                '<input type="hidden" name="month" value="'+month+'" />' +
                '<input type="hidden" name="year" value="'+year+'" />' +
                '<input type="hidden" name="status" value="'+status+'" />' +
                '</form>');

            $('body').append(form);
            form.submit();
        }
    </script>
@endsection