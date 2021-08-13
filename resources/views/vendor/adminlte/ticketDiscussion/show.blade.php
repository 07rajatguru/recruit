@extends('adminlte::page')

@section('title', 'Ticket Discussion')

@section('content_header')
    <h1></h1>
@stop

@section('content')

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

<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="pull-left">
            <h2>{{ $ticket_res['ticket_no'] }}</h2>
        </div>
        <div class="pull-right">
            <a class="btn btn-primary" href="{{ route('ticket.index') }}">Back</a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="box box-warning col-xs-12 col-sm-12 col-md-12">
            <div class="box-header col-md-6 ">
                       
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <table class="table table-bordered">
                    <tr>
                        <th>Module :</th>
                        <td>{{ $ticket_res['module_name'] }}</td>
                    </tr>
                    <tr>
                        <th>Status :</th>
                        <td>{{ $ticket_res['status'] }}</td>
                    </tr>
                    <tr>
                        <th>Query Type :</th>
                        <td>{{ $ticket_res['question_type'] }}</td>
                    </tr>
                    <tr>
                        <th>Added By :</th>
                        <td>{{ $ticket_res['added_by'] }}</td>
                    </tr>
                    <tr>
                        <th>Description :</th>
                        <td>{{ $ticket_res['description'] }}</td>
                    </tr>
                </table>
            </div>

        </div>
    </div>

    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="box box-warning col-xs-12 col-sm-12 col-md-12">
            <div class="box-header  col-md-6 ">
                <h3 class="box-title">Attachments</h3>&nbsp;&nbsp;
                @include('adminlte::ticketDiscussion.upload', ['data' => $ticket_res, 'name' => 'ticketattachments'])
            </div>

            <div class="box-header col-md-8"></div>

            <div class="col-xs-12 col-sm-12 col-md-12">
                <table class="table table-bordered">
                    <tr>
                        <th></th>
                        <th>File Name</th>
                        <th>Size</th>
                    </tr>
                        @if(sizeof($ticket_res_doc)>0)
                            @foreach($ticket_res_doc as $key => $value)
                                <tr>
                                    <td>
                                        <a download href="{{ $value['url'] }}" ><i class="fa fa-fw fa-download"></i></a>&nbsp;
                                        @include('adminlte::partials.confirm', ['data' => $value,'id'=> $ticket_res['id'], 'name' => 'ticketattachments' ,'display_name'=> 'Attachments'])
                                    </td>

                                    <td>
                                        <a target="_blank" href="{{ $value['url'] }}"> {{ $value['name'] }}</a>
                                    </td>
                                    <td>{{ $value['size'] }}</td>
                                </tr>
                            @endforeach
                        @endif
                </table>
            </div>
        </div>
    </div>
</div>
@endsection