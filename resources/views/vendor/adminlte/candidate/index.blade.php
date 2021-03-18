@extends('adminlte::page')

@section('title', 'Candidate')

@section('content_header')

@stop

@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Candidates List <span id="candidate_count">({{ $count or 0 }})</span></h2>
                <h4><span>Total No. of Candidates - {{ $total_count }}</span></h4>
            </div>
            <div class="pull-right">
                 <button type="button" class="btn btn-success" data-toggle="modal" data-target="#searchmodal">Master Search</button>
                <a class="btn btn-primary" href="{{ route('all.jobs') }}"> Advanced Search</a>
                <a class="btn btn-success" href="{{ route('candidate.create') }}"> Create New Candidate</a>
            </div>
        </div>

        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="box-body col-xs-12 col-sm-12 col-md-12">
                <div class="form-group select-initial-letter">
                    <strong>Select initial letter:</strong>
                    {{Form::select('letter',$letter_array, $letter, array('id'=>'letter','class'=>'form-control'))}}
                </div>
                <div class="form-group select-initial-letter" style="margin-top: 19px;">
                    {!! Form::submit('Select', ['class' => 'btn btn-primary', 'onclick' => 'select_data()','id' => 'select_btn']) !!}
                </div>
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
                <th>Jobs associated to candidate</th>
                <th>Added Date</th>
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
                    @include('adminlte::partials.deleteModal', ['data' => $candidate, 'name' => 'candidate','display_name'=>'Candidate'])
                </td>
                <td>{{ $candidate->fname or '' }}</td>
                <td>{{ $candidate->owner or '' }}</td>
                <td style="white-space: pre-wrap; word-wrap: break-word;">{{ $candidate->email or ''}}</td>
                <td style="white-space: pre-wrap; word-wrap: break-word;">{{ $candidate->mobile or ''}}</td>
            </tr>
        @endforeach
        </tbody>--}}
    </table>

    <div class="modal fade searchmodal" id="searchmodal" aria-labelledby="searchmodal" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">Search Options</h4>
                </div>

                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="">
                        <div class="form-group"><br/>
                            <strong>Select filed Which you want to search : </strong>
                            {!! Form::select('selected_field', $field_list,null, array('id'=>'selected_field', 'class' => 'form-control','tabindex' => '1','onchange' => 'displayField()')) !!}
                        </div>
                    </div>
                </div>

                <div class="col-xs-12 col-sm-12 col-md-12 candidate_nm_cls" style="display: none;">
                    <div class="">
                        <div class="form-group"><br/>
                            <strong>Enter Candidate Name : </strong>
                            {!! Form::text('cname', null, array('id'=>'cname','placeholder' => 'Candidate Name','class' => 'form-control', 'tabindex' => '1')) !!}
                        </div>
                    </div>
                </div>

                <div class="col-xs-12 col-sm-12 col-md-12 candidate_email_cls" style="display: none;">
                    <div class="">
                        <div class="form-group"><br/>
                            <strong>Enter Candidate Email : </strong>
                            {!! Form::text('cemail', null, array('id'=>'cemail','placeholder' => 'Candidate Email','class' => 'form-control', 'tabindex' => '1')) !!}
                        </div>
                    </div>
                </div>

                <div class="col-xs-12 col-sm-12 col-md-12 candidate_mno_cls" style="display: none;">
                    <div class="">
                        <div class="form-group"><br/>
                            <strong>Enter Candidate Mobile No. : </strong>
                            {!! Form::text('cmno', null, array('id'=>'cmno','placeholder' => 'Candidate Mobile No.','class' => 'form-control', 'tabindex' => '1')) !!}
                        </div>
                    </div>
                </div>

                <div class="col-xs-12 col-sm-12 col-md-12 candidate_title_cls" style="display: none;">
                    <div class="">
                        <div class="form-group"><br/>
                            <strong>Enter Posting Title : </strong>
                            {!! Form::text('job_title', null, array('id'=>'job_title','placeholder' => 'Posting Title','class' => 'form-control', 'tabindex' => '1')) !!}
                        </div>
                    </div>
                </div>
         
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" onclick="displayresults()">Search
                    </button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div>
@stop

@section('customscripts')
    <script type="text/javascript">
        jQuery(document).ready(function() {

            var initial_letter = $("#letter").val();

            var table = $("#candidate_table").dataTable({

                "bProcessing": true,
                "serverSide": true,
                "order": [0,'desc'],
                "columnDefs": [ {orderable: false, targets: [1]},],
                "ajax":{

                    url :"candidate/all", // json datasource
                    data: {initial_letter:initial_letter},
                    type: "get",  // type of method  , by default would be get
                    beforeSend: function() {
                        document.getElementById("select_btn").value="Loading...";
                        document.getElementById("select_btn").disabled = true;
                    },
                    complete: function (data) {
                        document.getElementById("select_btn").value="Select";
                        document.getElementById("select_btn").disabled = false;
                    },
                    error: function(){  // error handling code
                      //  $("#employee_grid_processing").css("display","none");
                    },
                },
                "pageLength": 50,
                "responsive": true,
                "autoWidth": false,
                "pagingType": "full_numbers",
                "stateSave" : true,
            });

            $("#letter").select2();
        });

        function select_data() {

            $("#candidate_table").dataTable().fnDestroy();

            var initial_letter = $("#letter").val();

            $("#candidate_table").dataTable({

                "bProcessing": true,
                "serverSide": true,
                "order": [0,'desc'],
                "columnDefs": [ {orderable: false, targets: [1]},],
               "ajax":{
                    url :"candidate/all", // json datasource
                    data: {initial_letter:initial_letter},
                    type: "get",  // type of method  , by default would be get
                    beforeSend: function() {
                        document.getElementById("select_btn").value="Loading...";
                        document.getElementById("select_btn").disabled = true;
                    },
                    complete: function (data) {
                        document.getElementById("select_btn").value="Select";
                        document.getElementById("select_btn").disabled = false;
                    },
                    error: function(){  // error handling code
                      //  $("#employee_grid_processing").css("display","none");
                    },
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

        function displayField() {

            var selected_field = $("#selected_field").val();

            if(selected_field == 'Candidate Name') {

                $(".candidate_nm_cls").show();
                $(".candidate_email_cls").hide();
                $(".candidate_mno_cls").hide();
                $(".candidate_title_cls").hide();
            }
            if(selected_field == 'Candidate Email') {

                $(".candidate_email_cls").show();
                $(".candidate_nm_cls").hide();
                $(".candidate_mno_cls").hide();
                $(".candidate_title_cls").hide();
            }
            if(selected_field == 'Candidate Mobile No.') {

                $(".candidate_mno_cls").show();
                $(".candidate_nm_cls").hide();
                $(".candidate_email_cls").hide();
                $(".candidate_title_cls").hide();
            }
            if(selected_field == 'Posting Title') {

                $(".candidate_title_cls").show();
                $(".candidate_nm_cls").hide();
                $(".candidate_email_cls").hide();
                $(".candidate_mno_cls").hide();
            }
        }

        function displayresults() {

            var cname = $("#cname").val();
            var cemail = $("#cemail").val();
            var cmno = $("#cmno").val();
            var job_title = $("#job_title").val();

            var url = '/candidate';

            if(cname != '') {

                
            }
        }
    </script>
@endsection