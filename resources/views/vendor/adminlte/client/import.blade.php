@extends('adminlte::page')

@section('title', 'Client')

@section('content_header')
    <h1></h1>
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
                <a href="{{ getenv('APP_URL') }}/uploads/import_files/client_sample.xlsx" target="_blank" class="btn btn-warning">Sample format of Excel</a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="box box-warning col-xs-12 col-sm-12 col-md-12">
            <h4 class="box-title">Import Excel</h4>
                <form style="" action="{{ URL::to('client/importExcel') }}" class="form-horizontal" method="post" enctype="multipart/form-data">
                    <input type="file" name="import_file" />{{ csrf_field() }}<br/>
                    <button class="btn btn-primary">Import CSV or Excel File</button>
                </form>
                <br>
            </div>
        </div>
    </div>

    </form>
    <h2>Client List</h2>
    <table class="table table-striped table-bordered nowrap" cellspacing="0" width="100%" id="client_table">
        <thead>
            <tr>
                <th>No</th>
                <th>{{ Form::checkbox('client[]',0 ,null,array('id'=>'allcb')) }}</th>
                <th>Action</th>
                <th>Company Owner</th>
                <th>Company Name</th>
                <th>Contact Point</th>
                <th>Category</th>
                <th>Status</th>
                <th>City</th>
                <th>Industry Name</th>
            </tr>
        </thead>
    </table>

   
@endsection
  
@section('customscripts') 
  <script type="text/javascript">
        
        var app_url = "{!! env('APP_URL'); !!}";
        jQuery(document).ready(function(){

            var client_owner = $("#client_owner").val();
            var client_company = $("#client_company").val();
            var client_contact_point = $("#client_contact_point").val();
            var client_cat = $("#client_cat").val();
            var client_status = $("#client_status").val();
            var client_city = $("#client_city").val();
            var client_industry = $("#client_industry").val();

            $("#client_table").DataTable({
                
                'bProcessing' : true,
                'serverSide' : true,
                "order" : [0, 'desc'],
                "columnDefs": [ {orderable: false, targets: [1]},{orderable: false, targets: [2]} ],
                "ajax" : {
                    'url' : app_url+"/client/alls",
                    'type' : 'get',
                    "data": {
                       "client_owner": client_owner,
                       "client_company": client_company,
                       "client_contact_point": client_contact_point,
                       "client_cat": client_cat,
                       "client_status": client_status,
                       "client_city": client_city,
                       "client_industry": client_industry
                  }
                },
                responsive : true,
                // 'pageLength': 10, // set the number of records per page
                // 'pagingType': 'full_numbers', // set the pagination type
                stateSave : true
                
            });
            
        });
    </script>   
@endsection