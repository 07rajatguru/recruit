@extends('adminlte::page')

@section('title', 'Department')

@section('content_header')
    <h1></h1>
@stop

@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Department ({{ $count }})</h2>
            </div>

            <div class="pull-right">
                <a class="btn btn-success" href="{{ route('departments.create') }}">Add New Department</a>
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

    <table class="table table-striped table-bordered nowrap" cellspacing="0" width="100%" id="department_table">
        <thead>
            <tr>
                <th width="50px">No</th>
                <th width="50px">Action</th>
                <th>Name</th>
            </tr>
        </thead>
        <tbody>
        <?php $i = 0; ?>
        @foreach ($departments as $key => $value)
            <tr>
                <td>{{ ++$i }}</td>
                <td>
                    <a class="fa fa-edit" href="{{ route('departments.edit',$value->id) }}"></a>

                    @include('adminlte::partials.deleteModalNew', ['data' => $value, 
                        'name' => 'departments','display_name'=>'Department'])
                </td>
                <td>{{ $value->name }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection

@section('customscripts')
    <script type="text/javascript">
        
        jQuery(document).ready(function() {

            var table = jQuery('#department_table').DataTable({
                responsive: true,
                stateSave : true,
                "columnDefs": [ {orderable: false, targets: [1]}],
            });

            if ( ! table.data().any() ) {
            }
            else{
                new jQuery.fn.dataTable.FixedHeader( table );
            }
        });
    </script>
@endsection