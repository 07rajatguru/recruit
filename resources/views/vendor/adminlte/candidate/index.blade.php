@extends('adminlte::page')

@section('title', 'Candidate')

@section('content_header')

@stop

@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Candidate List ({{ $count or 0 }})</h2>
            </div>

            <div class="pull-right">
                <a class="btn btn-success" href="{{ route('candidate.create') }}"> Create New Candidate</a>
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

    <table class="table table-striped table-bordered nowrap" cellspacing="0" width="100%" id="candidate_table">
        <thead>
            <tr>
                <th>No</th>
                <th>Action</th>
                <th>Candidate Name</th>
                <th>Candidate Owner</th>
                <th>Candidate Email</th>
                <th>Mobile Number</th>
            </tr>
        </thead>
        <?php $i=0; ?>
        {{--<tbody>
        @foreach ($candidates as $candidate)
            <tr>
                <td>{{ ++$i }}</td>
                <td>
                    <a class="fa fa-circle" href="{{ route('candidate.show',$candidate->id) }}" title="Show"></a>
                    <a class="fa fa-edit" href="{{ route('candidate.edit',$candidate->id) }}" title="Edit"></a>
             

                  @if($isSuperAdmin)
                    @include('adminlte::partials.deleteModal', ['data' => $candidate, 'name' => 'candidate','display_name'=>'Candidate'])
                  @endif
          
                </td>
                <td>{{ $candidate->fname or '' }}</td>
                <td>{{ $candidate->owner or '' }}</td>
                <td style="white-space: pre-wrap; word-wrap: break-word;">{{ $candidate->email or ''}}</td>
                <td style="white-space: pre-wrap; word-wrap: break-word;">{{ $candidate->mobile or ''}}</td>

            </tr>
        @endforeach
        </tbody>--}}
    </table>
@stop

@section('customscripts')
    <script type="text/javascript">
        jQuery(document).ready(function(){
           /* var table = jQuery('#candidate_table').DataTable( {
                responsive: true,
                stateSave : true,
                "autoWidth": false,
                "pageLength": 100
            } );

            new jQuery.fn.dataTable.FixedHeader( table );*/

            $("#candidate_table").dataTable({
                "bProcessing": true,
                "serverSide": true,
                "order": [0,'desc'],
                "columnDefs": [ {orderable: false, targets: [1]},
                                ],
                "ajax":{
                    url :"/candidate/all", // json datasource
                    type: "get",  // type of method  , by default would be get
                    error: function(){  // error handling code
                      //  $("#employee_grid_processing").css("display","none");
                    }
                },
                "pageLength": 50,
                "responsive": true,
                "autoWidth": false,
                "pagingType": "full_numbers",
                "stateSave" : true,
            });
        });
    </script>
@endsection