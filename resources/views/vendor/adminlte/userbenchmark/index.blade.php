@extends('adminlte::page')

@section('title', 'User Benchmark')

@section('content_header')
    <h1></h1>
@stop

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="pull-left">
                <h2>User Benchmark ({{ $total_count or 0}})</h2>
            </div>
            <div class="pull-right">
                <a class="btn btn-success" href="{{ route('userbenchmark.create') }}"> Add New Benchmark</a>
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

    <div role="tabpanel">
        <ul class="nav nav-tabs" role="tablist">
            <li role="presentation" class="active_status active">
                <a href="#div_active" role="tab" data-toggle="tab" style="font-size:15px;color: black;" title="Active"><b>Active ({{ $active_count or 0 }})</b></a>
            </li>
            <li role="presentation" class="inactive_status">
                <a href="#div_inactive" role="tab" data-toggle="tab" style="font-size:15px;color: black;" title="Inactive"><b>Inactive ({{ $inactive_count or 0 }})</b></a>
            </li>
        </ul>
    </div>
    <br/>
    <div class="tab-content">
        <div role="tabpanel" class="tab-pane active" id="div_active">
            <table class="table table-striped table-bordered nowrap" cellspacing="0" width="100%" id="user_active">
                <thead>
                    <tr>
                        <th width="5%">No</th>
                        <th width="10%"><center>Department Name</center></th>
                        <th width="10%"><center>User Name</center></th>
                        <th><center>No Of <br/> Resumes</center></th>
                        <th><center>Shortlist <br/> Ratio</center></th>
                        <th><center>Interview <br/> Ratio</center></th>
                        <th><center>Selection <br/> Ratio</center></th>
                        <th><center>Offer Acceptance <br/> Ratio</center></th>
                        <th><center>Joining <br/> Ratio</center></th>
                        <th><center>After Joining <br/> Success Ratio</center></th>
                        <th width="10%">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i=0; ?>
                    @if(isset($active_user_bench_mark) && sizeof($active_user_bench_mark) > 0)
                        @foreach ($active_user_bench_mark as $key => $value)
                            <tr>
                                <td>{{ ++$i }}</td>
                                <td><center>{{ $value['department_name'] }}</center></td>
                                <td><center>{{ $value['user_name'] }}</center></td>
                                <td><center>{{ $value['no_of_resumes'] }}</center></td>
                                <td><center>{{ $value['shortlist_ratio'] }}</center></td>
                                <td><center>{{ $value['interview_ratio'] }}</center></td>
                                <td><center>{{ $value['selection_ratio'] }}</center></td>
                                <td><center>{{ $value['offer_acceptance_ratio'] }}</center></td>
                                <td><center>{{ $value['joining_ratio'] }}</center></td>
                                <td><center>{{ $value['after_joining_success_ratio'] }}</center></td>
                               
                                <td>
                                    <a class = "fa fa-edit" title = "Edit" href="{{ route('userbenchmark.edit',\Crypt::encrypt($value['id'])) }}"></a>

                                    @permission(('user-benchmark-delete'))
                                        @include('adminlte::partials.deleteModal', ['data' => $value, 'name' => 'userbenchmark','display_name'=>'Benchmark'])
                                    @endpermission
                                </td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>

        <div role="tabpanel" class="tab-pane inactive" id="div_inactive">
            <table class="table table-striped table-bordered nowrap" cellspacing="0" width="100%" id="user_inactive">
                <thead>
                    <tr>
                        <th width="5%">No</th>
                        <th width="10%"><center>Department Name</center></th>
                        <th width="10%"><center>User Name</center></th>
                        <th><center>No Of <br/> Resumes</center></th>
                        <th><center>Shortlist <br/> Ratio</center></th>
                        <th><center>Interview <br/> Ratio</center></th>
                        <th><center>Selection <br/> Ratio</center></th>
                        <th><center>Offer Acceptance <br/> Ratio</center></th>
                        <th><center>Joining <br/> Ratio</center></th>
                        <th><center>After Joining <br/> Success Ratio</center></th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i=0; ?>
                    @if(isset($inactive_user_bench_mark) && sizeof($inactive_user_bench_mark) > 0)
                        @foreach ($inactive_user_bench_mark as $key => $value)
                            <tr>
                                <td>{{ ++$i }}</td>
                                <td><center>{{ $value['department_name'] }}</center></td>
                                <td><center>{{ $value['user_name'] }}</center></td>
                                <td><center>{{ $value['no_of_resumes'] }}</center></td>
                                <td><center>{{ $value['shortlist_ratio'] }}</center></td>
                                <td><center>{{ $value['interview_ratio'] }}</center></td>
                                <td><center>{{ $value['selection_ratio'] }}</center></td>
                                <td><center>{{ $value['offer_acceptance_ratio'] }}</center></td>
                                <td><center>{{ $value['joining_ratio'] }}</center></td>
                                <td><center>{{ $value['after_joining_success_ratio'] }}</center></td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </div>
@endsection

@section('customscripts')
    <script type="text/javascript">
        jQuery(document).ready(function(){

            var table = jQuery('#user_active').DataTable( {
                responsive: true,
                stateSave : true,
                "columnDefs": [ {orderable: false, targets: [10]}],
            } );

            if ( ! table.data().any() ) {
            }
            else{
                new jQuery.fn.dataTable.FixedHeader( table );
            }

            var table = jQuery('#user_inactive').DataTable( {
                responsive: true,
                stateSave : true,
            } );

            if ( ! table.data().any() ) {
            }
            else{
                new jQuery.fn.dataTable.FixedHeader( table );
            }
        });
    </script>
@endsection