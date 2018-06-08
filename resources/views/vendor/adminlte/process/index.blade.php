@extends('adminlte::page')

@section('title', 'HRM')

@section('content_header')
    <h1></h1>
@stop

@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Process Manual Management</h2>
            </div>

            <div class="pull-right">
                <a class="btn btn-success" href="{{ route('process.create') }}"> Create New Process</a>
            </div>
        </div>
    </div>

    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>

    @endif

    <table id="training_table" class="table table-striped table-bordered nowrap" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th>No</th>
                <th>Process Name</th>
                <!-- <th>Team Mates</th> -->
                <th width="280px">Action</th>
            </tr>
        </thead>
        <tbody>

        @foreach($processFiles as $processFile)
      
        @endforeach
    
        <?php $i = 0 ;?>
        @foreach ($processList as $key => $value)
           
            <tr>
                
                <td>{{ ++$i }}</td>
                
                <td>{{ $value['title'] }}</td>
                
                <td>
                  
                    <a class="fa fa-circle" title="show" href="{{ route('process.show',$value['id']) }}"></a>
                    {{--<a class="fa fa-fw fa-download" title="Download" target="_blank" href="{{ $processFile['file'] }}"></a>--}}
                    @if(isset($value['access']) && $value['access']==1)
                        <a class="fa fa-edit" title="Edit" href="{{route('process.edit',$value['id']) }}"></a>
                        @include('adminlte::partials.deleteModal', ['data' => $value, 'name' => 'process','display_name'=>'Process'])
                    @endif

                </td>
                
           </tr>
        
        @endforeach
        </tbody>
    </table>
    
@stop
@section('customscripts')
    <script type="text/javascript">
        jQuery(document).ready(function(){
            var table = jQuery('#training_table').DataTable( {
                responsive: true
            } );

            new jQuery.fn.dataTable.FixedHeader( table );
        });
    </script>
@endsection