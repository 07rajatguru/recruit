@extends('adminlte::page')

@section('title', 'Client')

@section('content_header')
    <h1></h1>

@stop

@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Client List ({{ $count }})</h2>
            </div>

            <div class="pull-right">
                {{--<a class="btn btn-success" onclick="client_emails_notification();"> Submit</a>--}}
                <a class="btn btn-success" href="{{ route('client.create') }}"> Create New Client</a>
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

 
    <table class="table table-striped table-bordered nowrap" cellspacing="0" width="100%" id="client_table">
        <thead>
            <tr>
                <th>{{ Form::checkbox('client[]',0 ,null,array('id'=>'allcb')) }}</th>
                <th>Client Owner</th>
                <th>Company Name</th>   
                <th>HR/Coordinator Name</th>
                <th>Status</th>
                <th>Client Address</th>
                <th width="280px">Action</th>
            </tr>
        </thead>
        <tbody>
       <?php $i=0; ?>
        @foreach ($client_array as $key => $client)
            <tr>
                <td>{{ Form::checkbox('client', $client['id'],null,array('class'=>'others_client' ,'id'=>$client['id'] )) }}</td>
                <td>{{ $client['am_name'] }}</td>
                <td style="white-space: pre-wrap; word-wrap: break-word;">{{ $client['name'] }}</td>
                <td>{{ $client['hr_name'] }}</td>
                <td>{{ $client['status']}} </td>
                <td style="white-space: pre-wrap; word-wrap: break-word;">{{ $client['address'] }}</td>
                <td>

                    <?php if($isSuperAdmin || $isAdmin || $isStrategy || $client['client_visibility']) { ?>
                        <a title="Show" class="fa fa-circle"  href="{{ route('client.show',$client['id']) }}"></a>
                     <?php } ?>

                    {{-- Only Client Owner, Admin and Super admin have access to edit rights--}}
                    <?php if($isSuperAdmin || $isAdmin || $client['client_owner']) { ?>
                        <a title="Edit" class="fa fa-edit" href="{{ route('client.edit',$client['id']) }}"></a>
                    <?php  }?>

                    <?php if($isSuperAdmin) { ?>
                    @include('adminlte::partials.deleteModalNew', ['data' => $client, 'name' => 'client','display_name'=>'Client'])
                        @if(isset($client['url']) && $client['url']!='')
                            <a target="_blank" href="{{$client['url']}}"><i  class="fa fa-fw fa-download"></i></a>
                        @endif
                    <?php  }?>

                </td>

            </tr>
        @endforeach
        </tbody>
    </table>

        <input type="hidden" id="token" value="{{ csrf_token() }}">
@stop

@section('customscripts')
    <script type="text/javascript">
      jQuery( document ).ready(function() {

        var table = jQuery('#client_table').DataTable( {
                    responsive: true,
                    "pageLength": 100
            } );

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

            new jQuery.fn.dataTable.FixedHeader( table );
        });

        function client_emails_notification(){
            var client_ids = new Array();
            var token = $("#token").val();

                $("input:checkbox[name=client]:checked").each(function(){
                    client_ids.push($(this).val());


                });

                var url = '/client/emailnotification';

                if(client_ids.length > 0){
                    var form = $('<form action="' + url + '" method="post">' +
                            '<input type="hidden" name="_token" value="'+token+'" />' +
                            '<input type="text" name="client_ids" value="'+client_ids+'" />' +
                            '</form>');

                    $('body').append(form);
                    form.submit();
                }
            
        }
    </script>
@endsection
