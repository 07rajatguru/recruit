@extends('adminlte::page')

@section('title', 'Company')

@section('content_header')
    <h1></h1>
@stop

@section('content')
<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="pull-left">
            <h2>Company Management</h2>
        </div>

        <div class="pull-right">
            @permission(('companies-add'))
                <a class="btn btn-success" href="{{ route('companies.create') }}"> Create New Company</a>
            @endpermission
        </div>
    </div>
</div>

@if ($message = Session::get('success'))
    <div class="alert alert-success">
        <p>{{ $message }}</p>
    </div>
@endif

<table class="table table-striped table-bordered nowrap" cellspacing="0" width="100%" id="company_table">
    <thead>
        <tr>
            <th width="40px">No</th>
            <th width="40px">Action</th>
            <th>Name</th>
        </tr>
    </thead>
    <tbody>
    <?php $i = 0; ?>
        @foreach ($companies as $key => $value)
            <tr>
                <td>{{ ++$i }}</td>
                <td>
                    @permission(('companies-edit'))
                        <a class="fa fa-edit" href="{{ route('companies.edit',\Crypt::encrypt($value->id)) }}"></a>
                    @endpermission
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

            var table = jQuery('#company_table').DataTable({
                responsive: true,
                stateSave : true
            });

            new jQuery.fn.dataTable.FixedHeader( table );
        });
    </script>
@endsection