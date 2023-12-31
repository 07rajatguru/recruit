@extends('adminlte::page')

@section('title', 'Accounting Head')

@section('content_header')
    <h1></h1>
@stop

@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Accounting Management</h2>
            </div>

            <div class="pull-right">
                <a class="btn btn-success" href="{{ route('accounting.create') }}">Add New Accounting Head</a>
            </div>
        </div>
    </div>

    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
    @endif

    @if($message = Session::get('error'))
        <div class="alert alert-error">
            <p>{{ $message }}</p>
        </div>
    @endif

    <table id="training_table" class="table table-striped table-bordered nowrap" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th>No</th>
                <th>Accounting Name</th>
                <th>Description</th>
                <th width="280px">Action</th>
            </tr>
        </thead>

        <tbody>            
        <?php $i = 0 ;?>
        @foreach ($accountings as $key => $value)
            <tr>
                <td>{{ ++$i }}</td>
                <td>{{ $value['name'] }}</td>
                <td style="white-space: pre-wrap; word-wrap: break-word;">{{ $value['description'] }}</td>
                <td>            
                    <a class="fa fa-edit" title="Edit" href="{{route('accounting.edit',\Crypt::encrypt($value['id'])) }}"></a>
                    
                    @permission(('accounting-head-delete'))
                        @include('adminlte::partials.deleteModal', ['data' => $value, 'name' => 'accounting','display_name'=>'Accounting Head'])
                    @endpermission
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
@stop

@section('customscripts')
    <script type="text/javascript">
        jQuery(document).ready(function(){

            var table = jQuery('#training_table').DataTable({
                responsive: true,
                stateSave : true,
            });

            new jQuery.fn.dataTable.FixedHeader( table );
        });
    </script>
@endsection