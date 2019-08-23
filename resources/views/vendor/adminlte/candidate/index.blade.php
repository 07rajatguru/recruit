@extends('adminlte::page')

@section('title', 'Candidate')

@section('content_header')

@stop

@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Candidate List <span id="candidate_count">({{ $count or 0 }})</span></h2>
            </div>

            <div class="pull-right">
                <a class="btn btn-success" href="{{ route('candidate.create') }}"> Create New Candidate</a>
            </div>
        </div>
    </div>

    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="box-body col-xs-2 col-sm-2 col-md-2">
            <div class="form-group">
                <strong>Select initial letter:</strong>
                {{Form::select('letter',$letter_array, $letter, array('id'=>'letter','class'=>'form-control'))}}
            </div>
        </div>

        <div class="box-body col-xs-2 col-sm-2 col-md-2">
            <div class="form-group" style="margin-top: 19px;">
                {!! Form::submit('Select', ['class' => 'btn btn-primary', 'onclick' => 'select_data()']) !!}
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

            var initial_letter = $("#letter").val();

            $("#candidate_table").dataTable({
                "bProcessing": true,
                "serverSide": true,
                "order": [0,'desc'],
                "columnDefs": [ {orderable: false, targets: [1]},
                                ],
                "ajax":{
                    url :"candidate/all", // json datasource
                    data: {initial_letter:initial_letter},
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

            $("#letter").select2();
        });

        function select_data(){

            $("#candidate_table").dataTable().fnDestroy();

           var initial_letter = $("#letter").val();
            $("#candidate_table").dataTable({
                "bProcessing": true,
                "serverSide": true,
                "order": [0,'desc'],
                "columnDefs": [ {orderable: false, targets: [1]},
                               ],
               "ajax":{
                    url :"candidate/all", // json datasource
                    data: {initial_letter:initial_letter},
                    type: "get",  // type of method  , by default would be get
                    error: function(){  // error handling code
                      //  $("#employee_grid_processing").css("display","none");
                    }
                },
                initComplete:function( settings, json){
                    var count = json.recordsTotal;

                    $("#candidate_count").html("(" + count + ")");
                },
                "pageLength": 50,
                "responsive": true,
                "autoWidth": false,
                "pagingType": "full_numbers",
                "stateSave" : true,
            });
        }

    </script>


@endsection