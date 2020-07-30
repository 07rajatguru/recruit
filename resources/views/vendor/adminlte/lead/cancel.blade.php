@extends('adminlte::page')

@section('title', 'Cancel Lead')

@section('content_header')

@stop

@section('content')
    
   <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Cancel Lead ({{ $count or '0' }})</h2>
            </div>

            <!-- <div class="pull-right">
                <a class="btn btn-success" href="{{ route('lead.create') }}"> Create New Lead</a>
            </div> -->
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

    <div class = "table-responsive">
       <table class="table table-striped table-bordered nowrap" cellspacing="0" width="100%" id="lead_table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Action</th>
                    <th>Company Name</th>
                    <th>Contact Point</th>
                    <th>Email</th>
                    <th>Mobile</th>
                    <th>City</th>
                    <th>Referred By</th>
                    <th>Website</th>
                    <th>Lead Status</th>
                    <th>Designation</th>
                    <th>Secondary email</th>
                    <th>Other number</th>
                    <th>service</th>
                    <th>City</th>
                    <th>State</th>
                    <th>Country</th>
                    <th>Remarks</th>
                    <th>Source</th>
                </tr>
            </thead>
            <?php $i=0; ?>
            {{--<tbody>
                
                @foreach($leads as $key=>$value)
                php
                if($value['convert_client'] == 1) {
                    $color='#32CD32';
                }
                else{
                    $color='';
                }
                    
                <tr>
                    <td>{{ ++$i }}</td>
                     <td>
                        <a class="fa fa-edit" title="Edit" href="{{ route('lead.edit',$value['id']) }}"></a>
                        @include('adminlte::partials.deleteModal', ['data' => $value, 'name' => 'lead','display_name'=>'lead'])

                       {{-- @if ($value['convert_client'] == 0)
                        <a title="Convert lead to client"  class="fa fa-clone" href="{{ route('lead.clone',$value['id']) }}"></a>
                        @endif 

                       
                    </td>
                    
                    <td style="background-color:{{$color}};white-space: pre-wrap; word-wrap: break-word;">{{ $value['name'] }}</td>
                    <td style="white-space: pre-wrap; word-wrap: break-word;">{{ $value['coordinator_name'] }}</td>
                    <td>{{ $value['mail'] }}</td>
                    <td>{{ $value['mobile'] }}</td>
                    <td>{{ $value['city'] }}</td>
                    <td>{{ $value['referredby'] }}</td>
                    <td>{{ $value['website'] }}</td>
                    <td>{{ $value['source'] }}</td>
                    <td>{{ $value['designation'] }}</td>
                    <td>{{ $value['s_email'] }}</td>
                    <td>{{ $value['other_number'] }}</td>
                    <td>{{ $value['service'] }}</td>
                    <td>{{ $value['city'] }}</td>
                    <td>{{ $value['state'] }}</td>
                    <td>{{ $value['country'] }}</td>
                    <td>{{ $value['remarks'] }}</td>
                    <td>{{ $value['lead_status'] }}</td> 
                </tr>
            @endforeach
            </tbody>--}}
        </table>
   </div>
@stop
@section('customscripts')
    <script type="text/javascript">
        jQuery(document).ready(function() {

            $("#lead_table").DataTable({

                "bProcessing": true,
                "serverSide": true,
                "order" : [0,'desc'],
                "columnDefs": [ {orderable: false, targets: [1]}],
                "ajax": {

                    url :"../lead/cancel/all",
                    type: "get",
                    error: function() {
                    
                    }
                },
                "pageLength": 50,
                "responsive": true,
                "pagingType": "full_numbers",
                "stateSave" : true,
            });
            
            var table = $('#lead_table').DataTable();
            table.columns( [19] ).visible( false );
        });
    </script>
@endsection