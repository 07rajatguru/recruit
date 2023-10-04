@extends('adminlte::page')

@section('title', 'Candidate Detail')

@section('content_header')
    <h1></h1>
    <style>
    .imported-row {
         color: grey !important;
    }

    </style>
@stop

@section('content')

    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
    @endif

    @if ($message = Session::get('error'))
        <div class="alert alert-danger">
            <p>{{ $message }}</p>
        </div>
    @endif

    @if(isset($messages) && sizeof($messages)>0)
        <div class="alert alert-success">
            @foreach($messages as $key=>$value)
                <p>{{ $value }}</p>
            @endforeach
        </div>
    @endif

    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Import Excel</h2>
            </div>
            <div class="pull-right">
                <a href="{{ getenv('APP_URL') }}/uploads/import_files/candidate_sample.xlsx" target="_blank" class="btn btn-warning">Sample format of Excel</a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="box box-warning col-xs-12 col-sm-12 col-md-12">
               <div class="box-body col-xs-6 col-sm-6 col-md-6">
                    <div class="">
                 {{--      <strong>Candidate Source:</strong>
                        {!! Form::select('candidateSource', $candidateSource,null, array('id'=>'candidateSource','class' => 'form-control', 'tabindex' => '25')) !!}<br> --}}
                        <form style="" action="{{ URL::to('candidate/importExcel') }}" class="form-horizontal" method="post" enctype="multipart/form-data">
                            <input type="file" name="import_file" />{{ csrf_field() }}<br/>
                            <button class="btn btn-primary">Import CSV or Excel File</button>
                        </form><br>
                    </div>
                </div>
            </div>
        </div>
    </div>
  
      </form>
    <h2>Candidate List</h2>
    <table class="table table-striped table-bordered nowrap" cellspacing="0" width="100%" id="candidate_table">
        <thead>
            <tr>
                <th>No</th>
                <th>Full Name</th>
                <th>Mobile Number</th>
                <th>Phone:</th>
                <th>Email</th>
                <th>Marital Status</th>
                <th>Candidate Sex</th>
            </tr>
            </thead>
    </table>

@stop
  
@section('customscripts') 
 <script type="text/javascript">
        jQuery(document).ready(function(){
            var app_url = "{!! env('APP_URL'); !!}";
            var imported = "{!! json_encode($imported) !!}";

            $("#candidate_table").DataTable({
                
                'bProcessing' : true,
                'serverSide' : true,
                "order" : [0, 'desc'],
                "columnDefs": [{orderable: false, targets: [1]} ],
                "ajax" : {
                    'url' : app_url+"/candidate/alls",
                    'type' : 'get',
                    error: function(){

                    }
                },
                "responsive" : true,
                // 'pageLength': 10, // set the number of records per page
                // 'pagingType': 'full_numbers', // set the pagination type
                "stateSave" : true,
                "createdRow": function (row, data, dataIndex) {
                  // Check if the row contains imported data
                   if (imported) {
                     $(row).addClass('imported-row'); 
              }
            }
            });
        });

        function export_data() {

            var app_url = "{!! env('APP_URL'); !!}";
            var url = app_url+'/candidate/export';

               var form = $('<form action="'+url+ '" method="post">' +
            '<input type="hidden" name="_token" value="<?php echo csrf_token() ?>">' +
             '</form>');

            $('body').append(form);
         form.submit();
         }
 </script>   
@endsection
