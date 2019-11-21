@extends('adminlte::page')

@section('title', 'Client')

@section('content_header')
    <h1></h1>
@stop

@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-right">
                <a class="btn btn-success" href="{{ route('client.create') }}"> Create New Client</a>
                <a class="btn btn-primary" href="{{ route('client.index') }}"> Back</a>
            </div>
            <div  class="pull-left">
                <h2> {{ $source }} Clients ({{ $count }}) </h2>
            </div>
        </div>
    </div>

    <input type="hidden" name="source" id="source" value="{{ $source }}">

    <div class="row">
        <div class="col-md-12">
            <div class="col-md-1" style="width: 11%;">
                <a href="{{ route('client.active') }}" style="text-decoration: none;color: black;"><div style="margin:5px;height:35px;background-color:#5cb85c;font-weight: 600;border-radius: 22px;padding:9px 0px 0px 9px;text-align: center;">Active({{ $active }})</div></a>
            </div>

            <div class="col-md-1" style="width: 11%;">
                <a href="{{ route('client.passive') }}" style="text-decoration: none;color: black;"><div style="margin:5px;height:35px;background-color:#d9534f;font-weight: 600;border-radius: 22px;padding:9px 0px 0px 9px;text-align: center;">Passive({{ $passive }}) </div></a>
            </div>

            <div class="col-md-1" style="width: 11%;">
                <a href="{{ route('client.leaders') }}" style="text-decoration: none;color: black;"><div style="margin:5px;height:35px;background-color:#337ab7;font-weight: 600;border-radius: 22px;padding:9px 0px 0px 9px;text-align: center;">Leaders({{ $leaders }})</div></a>
            </div>

            <div class="col-md-1" style="width: 11%;">
                <a href="{{ route('client.left') }}" style="text-decoration: none;color: black;"><div style="margin:5px;height:35px;background-color:#5bc0de;font-weight: 600;border-radius: 22px;padding:9px 0px 0px 9px;text-align: center;">Left({{ $left }}) </div>
                </a>
            </div>
        </div>
    </div>

    <br/>
    @if($isSuperAdmin || $isStrategy )
        <div class="row">
            <div class="col-md-12">
                <div class="col-md-2">
                    <a href="{{ route('client.paramount') }}" style="text-decoration: none;color: black;"><div style="margin:5px;height:35px;background-color:#E9967A;font-weight: 600;border-radius: 22px;padding:9px 0px 0px 9px;text-align: center;">Paramount ({{ $para_cat }})</div></a>
                </div>
                <div class="col-md-2">
                    <a href="{{ route('client.moderate') }}" style="text-decoration: none;color: black;"><div style="margin:5px;height:35px;background-color:#D3D3D3;font-weight: 600;border-radius: 22px;padding:9px 0px 0px 9px;text-align: center;">Moderate ({{ $mode_cat }})</div></a>
                </div>
                <div class="col-md-2">
                    <a href="{{ route('client.standard') }}" style="text-decoration: none;color: black;"><div style="margin:5px;height:35px;background-color:#00CED1;font-weight: 600;border-radius: 22px;padding:9px 0px 0px 9px;text-align: center;">Standard ({{ $std_cat }})</div></a>
                </div>
            </div>
        </div>
    @endif
    <br/>
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

    <table class="table table-striped table-bordered nowrap" cellspacing="0" width="100%" id="clienttype_table">
        <thead>
            <tr>
                <th>{{ Form::checkbox('client[]',0 ,null,array('id'=>'allcb')) }}</th>
                <th>Action</th>
                <th>Client Owner</th>
                <th>Company Name</th>
                <th>Contact Point</th>

                <?php if($isSuperAdmin || $isStrategy || $isAccountManager ) { ?>
                <th>Client Category</th>
                <?php }?>

                <th>Status</th>
                <th>City</th>
                <th>Remarks</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
@stop

@section('customscripts')
    <script type="text/javascript">

        jQuery( document ).ready(function() {

            var source = $("#source").val();

            $("#clienttype_table").dataTable({
                'bProcessing' : true,
                'serverSide' : true,
                "order" : [1,'desc'],
                "columnDefs": [ {orderable: false, targets: [0]},{orderable: false, targets: [1]}
                            ],
                "ajax" : {
                    'url' : '/client/allbytype',
                    data : {"source" : source},
                    'type' : 'get',
                    error: function(){

                    }
                },
                responsive: true,
                "pageLength": 25,
                "pagingType": "full_numbers",
            });

            $('#allcb').change(function(){
                if($(this).prop('checked')){
                    $('tbody tr td input[type="checkbox"]').each(function(){
                        $(this).prop('checked', true);
                    });
                }else{
                    $('tbody tr td input[type="checkbox"]').each(function(){
                        $(this).prop('checked', false);
                    });
                }
            });
            $('.others_client').change(function() {
                if ($(this).prop('checked')) {
                    if ($('.others_client:checked').length == $('.others_client').length) {
                        $("#allcb").prop('checked', true);
                    }
                }
                else{
                    $("#allcb").prop('checked', false);
                }
            });
        });

    </script>
@endsection