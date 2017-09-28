@extends('adminlte::page')

@section('title', 'HRM')

@section('content_header')
    <h1></h1>
@stop

@section('content')
    <div class="row">

        <div class="col-lg-12 margin-tb">

            <div class="pull-left">

                <h2>Industry Management</h2>

            </div>

            <div class="pull-right">

                {{--@permission(('industry-create'))--}}

                <a class="btn btn-success" href="{{ route('industry.create') }}"> Create New Industry</a>

                {{--@endpermission--}}

            </div>

        </div>

    </div>

    @if ($message = Session::get('success'))

        <div class="alert alert-success">

            <p>{{ $message }}</p>

        </div>

    @endif

    <table class="table table-striped table-bordered nowrap" cellspacing="0" width="100%" id="industry_table">
        <thead>
            <tr>

                <th>No</th>

                <th>Name</th>

                <th width="280px">Action</th>

            </tr>
        </thead>
        <tbody>
        <?php $i = 0; ?>
        @foreach ($industry as $key => $role)

            <tr>

                <td>{{ ++$i }}</td>

                <td>{{ $role->name }}</td>

                <td>

                    <a class="btn btn-info" href="{{ route('industry.show',$role->id) }}">Show</a>

                    {{--@permission(('industry-edit'))--}}

                    <a class="btn btn-primary" href="{{ route('industry.edit',$role->id) }}">Edit</a>

                    {{--@endpermission--}}

                    {{--@permission(('industry-delete'))--}}

                    {!! Form::open(['method' => 'DELETE','route' => ['industry.destroy', $role->id],'style'=>'display:inline']) !!}

                    {!! Form::submit('Delete', ['class' => 'btn btn-danger']) !!}

                    {!! Form::close() !!}

                    {{--@endpermission--}}

                </td>

            </tr>

        @endforeach
        </tbody>
    </table>

@endsection


@section('customscripts')
    <script type="text/javascript">
        jQuery(document).ready(function(){
            var table = jQuery('#industry_table').DataTable( {
                responsive: true
            } );

            new jQuery.fn.dataTable.FixedHeader( table );
        });
    </script>
@endsection