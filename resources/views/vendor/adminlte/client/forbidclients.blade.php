@extends('adminlte::page')

@section('title', 'Forbidden Clients')

@section('content_header')
    <h1></h1>

@stop

@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-right">
                <a class="btn btn-success" href="{{ route('client.create') }}"> Create New Client</a>
                
            </div>
            <div  class="pull-left">
                <h2>Forbidden Clients ({{ $count }}) </h2>
            </div>
        </div>
    </div>

    <input type="hidden" name="source" id="source" value="Forbid">

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
    </table>
@stop

@section('customscripts')
    <script type="text/javascript">

        jQuery( document ).ready(function() {
            /*var table = jQuery('#clienttype_table').DataTable( {
                responsive: true,
                "pageLength": 50,
                stateSave : true,
            });*/

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