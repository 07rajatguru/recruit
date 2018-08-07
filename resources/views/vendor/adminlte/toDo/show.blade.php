@extends('adminlte::page')

@section('title', 'ToDo')

@section('content_header')
    <h1></h1>

@stop

@section('content')

    <div>
        <div class="row">
            <div class="col-lg-12 margin-tb">
                <div class="pull-left">
                    <h2>To Do's Details</h2>
                </div>
                <div class="pull-right">
                    <a class="btn btn-primary" href="{{ route('todos.index') }}"> Back</a>
                </div>

            </div>

        </div>

        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="box box-warning col-xs-12 col-sm-12 col-md-12">

                    <div class="box-header with-border col-md-6 ">
                        <h3 class="box-title">Basic Information</h3>
                    </div>

                     <div class="col-xs-12 col-sm-12 col-md-12">
                        <table class="table table-bordered">
                            <tr>
                                <th scope="row">Task Owner:</th>
                                <td>{{ $toDos['task_owner'] }}</td>
                                <th>Subject</th>
                                <td>{{ $toDos['subject'] }}</td>
                            </tr>
                            <tr>
                                <th scope="row">User:</th>
                                <td>{{ $toDos['assigned_to'] }}</td>
                                <th>Due Date:</th>
                                <td>{{ $toDos['due_date'] }}</td>
                            </tr>
                            <tr>
                                <th scope="row">Status :</th>
                                <td>{{ $toDos['status'] }}</td>
                                <th scope="row">Start Date :</th>
                                <td>{{ $toDos['start_date'] }}</td>
                            </tr>
                            <tr>
                                <th scope="row">Type:</th>
                                <td></td>
                                <th>Type List:</th>
                                <td></td>
                            </tr>
                            <tr>
                                <th scope="row">Remarks :</th>
                                <td>{{ $toDos['description'] }}</td>
                               
                            </tr>
                        </table>
                    </div>
                   
                </div>
            </div>
        </div>
    </div>

@stop

