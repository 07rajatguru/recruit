@extends('adminlte::page')

@section('title', 'Benchmark')

@section('content_header')
    <h1></h1>
@stop

@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Benchmark({{ $count or 0}})</h2>
            </div>

            <div class="pull-right">
                <a class="btn btn-success" href="{{ route('rolewisebenchmark.create') }}">Add New Benchmark</a>
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

    <table class="table table-striped table-bordered nowrap" cellspacing="0" width="100%" id="bench_mark_table">
        <thead>
        <tr>
            <th width="5%">No</th>
            <th width="10%"><center>Department <br/> Name</center></th>
            <th width="10%"><center>Role Name</center></th>
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

        @if(isset($bench_mark) && sizeof($bench_mark) > 0)
            @foreach ($bench_mark as $key => $value)
                <tr>
                    
                    <td>{{ ++$i }}</td>
                    <td><center>{{ $value['department_name'] }}</center></td>
                    <td style="white-space: pre-wrap; word-wrap: break-word; color:black; text-decoration:none;"><center>{{ $value['role_name'] }}</center></td>
                    <td><center>{{ $value['no_of_resumes'] }}</center></td>
                    <td><center>{{ $value['shortlist_ratio'] }}</center></td>
                    <td><center>{{ $value['interview_ratio'] }}</center></td>
                    <td><center>{{ $value['selection_ratio'] }}</center></td>
                    <td><center>{{ $value['offer_acceptance_ratio'] }}</center></td>
                    <td><center>{{ $value['joining_ratio'] }}</center></td>
                    <td><center>{{ $value['after_joining_success_ratio'] }}</center></td>
                   
                    <td>
                        <a class = "fa fa-edit" title = "Edit" href="{{ route('rolewisebenchmark.edit',$value['id']) }}"></a>

                        @permission(('user-benchmark-delete'))
                            @include('adminlte::partials.deleteModal', ['data' => $value, 'name' => 'rolewisebenchmark','display_name'=>'Benchmark'])
                        @endpermission
                    </td>
                </tr>
            @endforeach
        @endif
        </tbody>
    </table>
@endsection

@section('customscripts')
    <script type="text/javascript">
        jQuery(document).ready(function(){

            var table = jQuery('#bench_mark_table').DataTable( {
                responsive: true,
                stateSave : true,
                "columnDefs": [ {orderable: false, targets: [10]}],
            } );

            if ( ! table.data().any() ) {
            }
            else{
                new jQuery.fn.dataTable.FixedHeader( table );
            }
        });
    </script>
@endsection