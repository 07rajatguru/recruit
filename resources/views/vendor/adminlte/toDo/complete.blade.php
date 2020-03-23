@extends('adminlte::page')

@section('title', "Completed ToDo's")

@section('content_header')
    <h1></h1>
@stop

@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Completed To Do's ({{ $count or 0 }})</h2>
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
            <th>Action</th>
            <th>Subject</th>
            <th>Assign By</th>
            <th>Assign To</th>
            <th>Due Date</th>
            <th>Status</th>
        </tr>
        </thead>
        {{--<tbody>
        php $i=0;
        @foreach ($todos as $key => $todo)

            <tr>

                <td>{{ ++$i }}</td>

                <td style="white-space: pre-wrap; word-wrap: break-word;">{{ $todo['subject'] }}</td>

                <td>{{ $todo['am_name'] }}</td>

                <td>{{ $todo['assigned_to'] }}</td>

                <td data-th="Lastrun" data-order="{{$todo['due_date_ts']}}">{{ $todo['due_date'] }}</td>

                <td>{{ $todo['status'] }}</td>

                <td>

                    <a title="Show"  class="fa fa-circle" href="{{ route('todos.show',$todo['id']) }}"></a>
                    @if(($todo['task_owner'] == $user_id)  || $isSuperAdmin || $isStrategyCoordination)
                        <a title="Edit" class="fa fa-edit" href="{{ route('todos.edit',$todo['id']) }}"></a>
                    @endif
                    {{--@include('adminlte::partials.deleteModal', ['data' => $todo, 'name' => 'todos','display_name'=>'Todo'])
                    {{--@if($todo['status_ids']!=$todo_status)
                    @include('adminlte::partials.completedtodo', ['data' => $todo, 'name' => 'todos','display_name'=>'Todo'])
                    @endif

                </td>

            </tr>

        @endforeach
        </tbody>--}}
    </table>

@endsection

@section('customscripts')
    <script type="text/javascript">
        jQuery(document).ready(function(){
            /*var table = jQuery('#todos_table').DataTable( {
                responsive: true
            } );

            new jQuery.fn.dataTable.FixedHeader( table );*/

            $("#todos_table").DataTable({
                'bProcessing' : true,
                'serverSide' : true,
                "order" : [0, 'desc'],
                "columnDefs": [ {orderable: false, targets: [1]},],
                "ajax" : {
                    'url' : '../todos/complete/all',
                    'type' : 'get',
                    error: function(){

                    }
                },
                responsive : true,
                "pageLength": 10,
                "pagingType" : "full_numbers",
                stateSave : true,
            });
        });
    </script>
@endsection