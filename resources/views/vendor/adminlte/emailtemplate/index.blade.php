@extends('adminlte::page')

@section('title', 'Email Template')

@section('content_header')
    <h1></h1>
@stop

@section('content')
<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="pull-left">
            <h2>Email Templates({{ $count }})</h2>
        </div>
        <div class="pull-right">
            <a class="btn btn-success" href="{{ route('emailtemplate.create') }}"> Add New Email Template</a>
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

<table class="table table-striped table-bordered nowrap" cellspacing="0" width="100%" id="email_template_table">
    <thead>
        <tr>
	       <th width="50px">No</th>
	       <th width="100px">Action</th>
	       <th>Name</th>
           <th>Subject</th>
	    </tr>
    </thead>
    <tbody>
        <?php $i=0; ?>
        @if(isset($email_template) && sizeof($email_template) > 0)
            @foreach ($email_template as $key => $value)
                <tr>
                    <td>{{ ++$i }}</td>
                    <td>
                        <a class="fa fa-circle" href="{{ route('emailtemplate.show',\Crypt::encrypt($value['id'])) }}">
                        </a>
                        <a class="fa fa-edit" href="{{ route('emailtemplate.edit',\Crypt::encrypt($value['id'])) }}">
                        </a>
                        @permission(('email-template-delete'))
                            @include('adminlte::partials.deleteModalNew', ['data' => $value, 'name' => 'emailtemplate','display_name'=>'Email Template'])
                        @endpermission
                    </td>
                    <td>{{ $value['name'] }}</td>
                    <td>{{ $value['subject'] }}</td>
                </tr>
            @endforeach
        @endif
    </tbody>
</table>
@stop

@section('customscripts')
    <script type="text/javascript">
        jQuery(document).ready(function()
        {
            var table = jQuery('#email_template_table').DataTable(
            {
                responsive: true,
                stateSave : true,
            });
        });
    </script>
@endsection