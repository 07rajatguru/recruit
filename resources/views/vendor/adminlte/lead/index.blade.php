@extends('adminlte::page')

@section('title', 'Lead')

@section('content_header')

@stop

@section('content')
    
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

   <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Total Leads({{$count}})</h2>
                <h4><span>Leads converted to client - {{ $convert_client_count }}</span></h4>
            </div>

            <div class="pull-right">
                <a class="btn btn-success" href="{{ route('lead.create') }}"> Create New Lead</a>
            </div>
        </div>
    </div>
   <div class = "table-responsive">
   <table class="table table-striped table-bordered nowrap" cellspacing="0" width="100%"" id="lead_table">
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
                <th>Convert Client</th>
            </tr>
        </thead>
        <?php $i=0; ?>
        {{--<tbody>
            
            @foreach($leads as $key=>$value)
            
            @if($value['convert_client'] == 1) 
                $color='#32CD32';
            
            @else
                $color='';
            @endif
                 
            <tr >
                <td>{{ ++$i }}</td>
                 <td>
                     @if($value['access'])
                        <a class="fa fa-edit" title="Edit" href="{{ route('lead.edit',$value['id']) }}"></a>
                     @endif

                     @if($value['access'])
                             @include('adminlte::partials.deleteModal', ['data' => $value, 'name' => 'lead','display_name'=>'lead'])
                     @endif

                    @if ($value['convert_client'] == 0)
                        @if($value['access'])
                            <a title="Convert lead to client"  class="fa fa-clone" href="{{ route('lead.clone',$value['id']) }}"></a>
                        @endif
                    @endif

                    @if ($value['convert_client'] == 0)
                        @if($value['access'])
                            @include('adminlte::partials.cancelbill', ['data' => $value, 'name' => 'lead','display_name'=>'Lead'])
                        @endif
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
        jQuery(document).ready(function(){
           /* var table = jQuery('#lead_table').DataTable( {
                responsive: true,
                "pageLength": 100,
                stateSave: true
            });
            new jQuery.fn.dataTable.FixedHeader( table );*/

            
            $("#lead_table").DataTable({
                "bProcessing": true,
                "serverSide": true,
                "order" : [0,'desc'],
                "columnDefs": [ {orderable: false, targets: [1]}],
                "ajax":{
                    url :"lead/all", // json datasource
                    type: "get",  // type of method  , by default would be get
                    error: function(){  // error handling code
                      //  $("#employee_grid_processing").css("display","none");
                    }
                },
                "pageLength": 50,
                "responsive": true,
                "pagingType": "full_numbers",
                "stateSave" : true,
                "fnRowCallback": function( Row, Data ) {
                    if ( Data[19] == "1" )
                    {
                        $('td:eq(2)', Row).css('background-color', 'LimeGreen');
                    }
                    else
                    {
                        $('td:eq(2)', Row).css('background-color', 'white');
                    }
                }
            });
            
            var table = $('#lead_table').DataTable();
            table.columns( [19] ).visible( false );
        });
    </script>
@endsection