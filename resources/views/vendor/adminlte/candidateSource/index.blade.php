@extends('adminlte::page')

@section('title', 'HRM')

@section('content_header')
    <h1></h1>
@stop

@section('content')
    <div class="row">

        <div class="col-lg-12 margin-tb">

            <div class="pull-left">

                <h2>Candidate Source Management</h2>

            </div>

            <div class="pull-right">

                {{--@permission(('permision-create'))--}}

                <a class="btn btn-success" href="{{ route('candidateSource.create') }}"> Create New Candidate Source</a>

                {{--@endpermission--}}

            </div>

        </div>

    </div>

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

    <table class="table table-striped table-bordered nowrap" cellspacing="0" width="100%" id="source_table">
        <thead>

            <tr>

                <th>No</th>

                <th>Name</th>

                <th width="280px">Action</th>

            </tr>

        </thead>
        <tbody>
        <?php $i=0; ?>
        @foreach ($candidateSource as $key => $source)

            <tr>

                <td>{{ ++$i }}</td>

                <td>{{ $source->name }}</td>


                <td>

                    <a class="btn btn-primary" href="{{ route('candidateSource.edit',$source->id) }}">Edit</a>


                    @include('adminlte::partials.deleteModal', ['data' => $source, 'name' => 'candidateSource','display_name'=>'Candidate Source'])


                </td>

            </tr>

        @endforeach
        </tbody>
    </table>

@endsection


@section('customscripts')
    <script type="text/javascript">
        jQuery(document).ready(function(){
            var table = jQuery('#source_table').DataTable( {
                responsive: true
            } );

            new jQuery.fn.dataTable.FixedHeader( table );
        });
    </script>
@endsection