@extends('adminlte::page')

@section('title', 'Lead')

@section('content_header')

@stop

@section('content')
    
   <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Lead Managments</h2>
            </div>

            <div class="pull-right">
                <a class="btn btn-success" href="{{ route('lead.create') }}"> Create New Lead</a>
            </div>
        </div>
    </div> 
  <table class="table table-striped table-bordered nowrap" cellspacing="0" width="100%" id="candidate_table">
        <thead>
            <tr>
                <th>No</th>
                <th></th>
                <th>Action</th>
                <th>Company Name</th>
                <th>Hr/coordinator name</th>
                <th>Clinet Count</th>
                <th>Email</th>
                <th>Mobile number</th>
                <th>Secondary email</th>
                <th>Other number</th>
                <th>Display Name</th>
                <th>service</th>
                <th>City</th>
                <th>State</th>
                <th>Country</th>
                <th>Remarks</th>
            </tr>
        </thead>
        <?php $i=0; ?>
        <tbody>
            
            @foreach($lead as $key=>$value)
            <tr>
                <td>{{ ++$i }}</td>
                <td><input type="checkbox" name="id[]" value="{{$value['id']}}"></td>
                 <td>
                            <a class="fa fa-edit" title="Edit" href="{{ route('lead.edit',$value['id']) }}"></a>
                            @include('adminlte::partials.deleteModal', ['data' => $value, 'name' => 'lead','display_name'=>'lead'])
                            <a title="Convert lead to client"  class="fa fa-clone" href="{{ route('lead.clone',$value['id']) }}"></a>
                </td>
                
                <td>{{ $value['name'] }}</td>
                <td>{{ $value['coordinator_name'] }}</td>
                <td>{{ $value['convert_client'] or '0' }}</td>
                <td>{{ $value['mail'] }}</td>
                <td>{{ $value['mobile'] }}</td>
                <td>{{ $value['s_email'] }}</td>
                <td>{{ $value['other_number'] }}</td>
                <td>{{ $value['display_name'] }}</td>
                <td>{{ $value['service'] }}</td>
                <td>{{ $value['city'] }}</td>
                <td>{{ $value['state'] }}</td>
                <td>{{ $value['country'] }}</td>
                <td>{{ $value['remarks'] }}</td>
               
               
                </tr>
        @endforeach
        </tbody>
 
    </table>
@stop
@section('customscripts')
    <script type="text/javascript">
        jQuery(document).ready(function(){
            var table = jQuery('#candidate_table').DataTable( {
                responsive: true,
                "autoWidth": true,
                "pageLength": 100
            } );

            new jQuery.fn.dataTable.FixedHeader( table );
        });
    </script>
@endsection