@extends('adminlte::page')

@section('title', 'HRM')

@section('content_header')
    <h1></h1>
@stop

@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Team Management</h2>
            </div>

            <div class="pull-right">
                <a class="btn btn-success" href="{{ route('team.create') }}"> Create New Team</a>
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

    <table id="team_table" class="table table-striped table-bordered nowrap" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th>No</th>
                <th>Team Name</th>
                <th>Team Mates</th>
                <th width="280px">Action</th>
            </tr>
        </thead>
        <tbody>
        <?php $i = 0 ;?>
        @foreach ($team_response as $key => $value)
            <tr>
                <td>{{ ++$i }}</td>
                <td>{{ $value['team_name'] }}</td>
                <td>
                    @if(!empty($value['users']))
                        @foreach($value['users'] as $key1=>$value1)
                            <label class="label label-success">{{ $value1 }}</label>
                        @endforeach
                    @endif
                </td>
                <td>
                    <a class="fa fa-edit" href="{{ route('team.edit',$key) }}"></a>
                    @include('adminlte::partials.deleteModal', ['data' => $value, 'name' => 'team','display_name'=>'Team'])
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
@stop
@section('customscripts')
    <script type="text/javascript">
        jQuery(document).ready(function(){
            var table = jQuery('#team_table').DataTable( {
                responsive: true,
                stateSave : true,
            } );

            new jQuery.fn.dataTable.FixedHeader( table );
        });
    </script>
@endsection