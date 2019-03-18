@extends('adminlte::page')

@section('title', 'Customer Support')

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
            <h2>Support Details</h2>
        </div>
        <div class="pull-right">
            <a class="btn btn-primary" href="{{ route('customer.index') }}"> Back</a>
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
                        <th>User Name:-</th>
                        <td>{{ $customer_support_res['user_nm'] }}</td>
                    </tr>
                    <tr>
                        <th>Module:-</th>
                        <td>{{ $customer_support_res['module'] }}</td>
                    </tr>
                    <tr>
                        <th>Subject:-</th>
                        <td>{{ $customer_support_res['subject'] }}</td>
                    </tr>
                    <tr>
                        <th>Description:-</th>
                        <td>{{ $customer_support_res['message'] }}</td>
                    </tr>
                </table>
            </div>

        </div>
    </div>

    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="box box-warning col-xs-12 col-sm-12 col-md-12">
            <div class="box-header  col-md-6 ">
                <h3 class="box-title">Attachments</h3>
                    &nbsp;&nbsp;
                    @include('adminlte::customerSupport.upload', ['data' => $customer_support_res, 'name' => 'customerattachments'])
            </div>

            <div class="box-header  col-md-8 ">
            </div>

            <div class="col-xs-12 col-sm-12 col-md-12">
                <table class="table table-bordered">
                    <tr>
                        <th></th>
                        <th>File Name</th>
                        <th>Size</th>
                    </tr>
                        @if(sizeof($customer_support_doc)>0)
                            @foreach($customer_support_doc as $key=>$value)
                                <tr>
                                    <td>
                                        <a download href="{{ $value['url'] }}" ><i  class="fa fa-fw fa-download"></i></a>
                                        &nbsp;
                                        @include('adminlte::partials.confirm', ['data' => $value,'id'=> $customer_support_res['id'], 'name' => 'customerattachments' ,'display_name'=> 'Attachments'])
                                    </td>

                                    <td>
                                    <a target="_blank" href="{{ $value['url'] }}">{{ $value['name'] }}
                                    </a>
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