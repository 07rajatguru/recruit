@extends('adminlte::page')

@section('title', 'HRM')

@section('content_header')
    <h1></h1>
@stop

@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Training Management</h2>
            </div>

            <div class="pull-right">
                <a class="btn btn-success" href="{{ route('training.create') }}"> Create New Training</a>
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
                <th>Training Name</th>
                <th width="280px">Action</th>
            </tr>
        </thead>
        <tbody>

        @foreach($trainingFiles as $trainingFile)
      
        @endforeach
    
        <?php $i = 0 ;?>
        @foreach ($training as $key => $value)
            <tr>
                <td>{{ ++$i }}</td>
                <td>{{ $value['title'] }}</td>
                <td>
                  
                    <a class="fa fa-circle" title="show" href="{{ route('training.show',$value['id']) }}"></a>
                    {{--<a class="fa fa-fw fa-download" title="Download" target="_blank" href="{{ $trainingFile['file'] }}"></a>--}}
                              
                  <a class="fa fa-edit" title="Edit" href="{{route('training.edit',$value['id']) }}"></a>
                 <!--  {!! Form::open(['method' => 'DELETE','route' => ['training.destroy', $value['id']],'style'=>'display:inline']) !!}
                    {!! Form::submit('Delete', ['class' => 'fa fa-trash']) !!}
                    {!! Form::close() !!} -->
                @include('adminlte::partials.deleteModal', ['data' => $value, 'name' => 'training','display_name'=>'Training'])                  
                    
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