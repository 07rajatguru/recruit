@extends('adminlte::page')

@section('title', 'Client Remarks')

@section('content_header')
    <h1></h1>
@stop

@section('content')
<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="pull-left">
            <h2>Client Remarks({{ $count }})</h2>
        </div>
        <div class="pull-right">
            <a class="btn btn-success" href="{{ route('clientremarks.create') }}"> Add Client Remarks</a>
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

<table class="table table-striped table-bordered nowrap" cellspacing="0" width="100%" id="client_remarks_table">
    <thead>
        <tr>
	       <th width="50px">No</th>
	       <th width="100px">Action</th>
	       <th>Remarks</th>
	    </tr>
    </thead>
    <tbody>
        <?php $i=0; ?>
        @foreach ($client_remarks as $key => $value)
            <tr>
                <td>{{ ++$i }}</td>
                <td>
                    <a class="fa fa-edit" href="{{ route('clientremarks.edit',$value['id']) }}"></a>

                    @if($isSuperAdmin)
                        @include('adminlte::partials.deleteModalNew', ['data' => $value, 
                        'name' => 'clientremarks','display_name'=>'Client Remarks'])
                    @endif
                </td>
                <td>{{ $value['remarks'] }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
@stop

@section('customscripts')
    <script type="text/javascript">
        jQuery(document).ready(function()
        {
            var table = jQuery('#client_remarks_table').DataTable(
            {
                responsive: true,
                stateSave : true,
            });
        });
    </script>
@endsection