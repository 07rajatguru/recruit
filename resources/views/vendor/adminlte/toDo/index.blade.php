@extends('adminlte::page')

@section('title', 'HRM')

@section('content_header')
    <h1></h1>
@stop

@section('content')
    <div class="row">

        <div class="col-lg-12 margin-tb">

            <div class="pull-left">

                <h2>To Do's</h2>

            </div>

            <div class="pull-right">

                {{--@permission(('permision-create'))--}}

                <a class="btn btn-success" href="{{ route('todos.create') }}"> Create New To Do</a>

                {{--@endpermission--}}

            </div>

        </div>

    </div>

    @if ($message = Session::get('success'))

        <div class="alert alert-success">

            <p>{{ $message }}</p>

        </div>

    @endif

    @if ($message = Session::get('error'))

        <div class="alert alert-success">

            <p>{{ $message }}</p>

        </div>

    @endif

    <table class="table table-striped table-bordered nowrap" cellspacing="0" width="100%" id="todos_table">
        <thead>
        <tr>
            <th>No</th>
            <th>Subject</th>
            {{--<th width="280px">Action</th>--}}
        </tr>
        </thead>
        <tbody>
        <?php $i=0; ?>
        @foreach ($todos as $key => $todo)

            <tr>

                <td>{{ ++$i }}</td>

                <td>{{ $todo->subject }}</td>


                {{--<td>

                    <a class="btn btn-primary" href="{{ route('todos.edit',$todo->id) }}">Edit</a>


                    @include('adminlte::partials.deleteModal', ['data' => $todo, 'name' => 'todos','display_name'=>'To Do's])


                </td>--}}

            </tr>

        @endforeach
        </tbody>
    </table>

@endsection

@section('customscripts')
    <script type="text/javascript">
        jQuery(document).ready(function(){
            var table = jQuery('#todos_table').DataTable( {
                responsive: true
            } );

            new jQuery.fn.dataTable.FixedHeader( table );
        });
    </script>
@endsection