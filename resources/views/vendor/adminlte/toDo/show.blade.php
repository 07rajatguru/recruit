@extends('adminlte::page')

@section('title', 'Interview')

@section('content_header')
    <h1></h1>

@stop

@section('content')

    <div>
        <div class="row">
            <div class="col-lg-12 margin-tb">
                <div class="pull-left">
                    To Do's Details
                </div>
                <div class="pull-right">
                    <a class="btn btn-primary" href="{{ route('todos.index') }}"> Back</a>
                </div>

            </div>

        </div>

        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="box box-warning col-xs-12 col-sm-12 col-md-12">

                     <div class="col-xs-12 col-sm-12 col-md-12">
                        <table class="table table-bordered">
                            <tr>
                                <th scope="row">Task Owner:</th>
                                <td>{{ $toDos->task_owner }}</td>
                                <th>Subject</th>
                                <td>{{ $toDos->subject }}</td>
                            </tr>
                            <tr>
                                <th scope="row">User:</th>
                                <td>{{ $toDos->candidate }}</td>
                                <th>Due Date:</th>
                                <td>{{ $toDos->due_date }}</td>
                            </tr>
                            <tr>
                                <th scope="row">Type:</th>
                                <td>{{ $toDos->type }}</td>
                                <th>Type List:</th>
                                <td>{{ $toDos->typeList }}</td>
                            </tr>
                            <tr>
                                <th scope="row">Status :</th>
                                <td>{{ $toDos->status }}</td>
                                <th scope="row">Priority :</th>
                                <td>{{ $toDos->priority }}</td>
                            </tr>
                            <tr>
                                <th scope="row">Desscription :</th>
                                <td>{{ $toDos->description }}</td>
                               
                            </tr>
                        </table>
                    </div>
                   
                </div>
            </div>
        </div>
    </div>

@stop

