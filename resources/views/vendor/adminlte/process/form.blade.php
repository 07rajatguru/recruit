@section('customs_css')
    <style>
        .error{
            color:#f56954 !important;
        }
    </style>
@endsection

<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="pull-left">
            @if( $action == 'edit')
                <h2>Edit Process Manual</h2>
            @else
                <h2>Add New Process Manual</h2>
            @endif
        </div>
        <div class="pull-right">
            <a class="btn btn-primary" href="{{ route('process.index') }}"> Back</a>
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

@if( $action == 'edit')
    {!! Form::model($process,['method' => 'PATCH','files' => true, 'id' => 'process_form', 'route' => ['process.update', $process->id]] ) !!}
@else
    {!! Form::open(array('route' => 'process.store','files' => true,'method'=>'POST', 'id' => 'process_form')) !!}
@endif

<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="box box-warning col-xs-12 col-sm-12 col-md-12">
            <div class="box-header col-md-6">
                <h3 class="box-title">Basic Information</h3>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="">
                    <div class="form-group {{ $errors->has('title') ? 'has-error' : '' }}">
                        <strong>Title : <span class = "required_fields">*</span></strong>
                        {!! Form::text('title', null, array('id'=>'title','placeholder' => 'Title','class' => 'form-control','tabindex' => '1')) !!}
                        @if ($errors->has('title'))
                            <span class="help-block">
                                <strong>{{ $errors->first('title') }}</strong>
                            </span>
                        @endif
                    </div>

                    <div class="form-group">
                        <strong>Select Users who can access the Process : <span class = "required_fields">*</span></strong><br/>&nbsp;&nbsp;
                        <input type="checkbox" id="departments_all"/> <strong>Select All</strong><br/>

                        @foreach($departments as $k=>$v)&nbsp;&nbsp; 
                            {!! Form::checkbox('department_ids[]', $k,in_array($k,$selected_departments), array('class' => 'department_ids','onclick' => 'displayUsers()')) !!}
                            {!! Form::label ($v) !!}
                        @endforeach

                        @if($action == "add")
                            <div class="add_user_list"></div>
                        @endif
                        @if($action == "edit")
                            <div class="add_user_list">
                                @foreach($users as $k1=>$v1)&nbsp;&nbsp; 
                                    {!! Form::checkbox('user_ids[]', $k1, in_array($k1,$selected_users), array('id'=>'user_ids','size'=>'10','class' => 'users_ids')) !!}
                                    {!! Form::label ($v1) !!}
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            
            @if($action == "add")
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Upload Documents : <span class = "required_fields">*</span> </strong>
                        <input type="file" name="upload_documents[]" id="upload_documents" multiple class="form-control"/>
                    </div>
                </div>
            @endif
        </div>

        @if($action == "edit")
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="box box-warning col-xs-12 col-sm-12 col-md-12">
                        <div class="box-header with-border col-md-12 ">
                            <h3 class="box-title">Attachments</h3>
                            &nbsp;&nbsp;
                            @include('adminlte::process.upload', ['name' => 'processattachments', 'data' => $process])
                        </div>

                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <table class="table table-bordered">
                                <tr>
                                    <th></th>
                                    <th>File Name</th>
                                    <th>Size</th>     
                                </tr>

                                @if(isset($processdetails['files']) && sizeof($processdetails['files']) > 0)
                                    @foreach($processdetails['files'] as $key => $value)
                                        <tr>
                                            <td>
                                                {{--<a download href="{{ $value['url'] }}"><i class="fa fa-fw fa-download"></i></a>--}}
                                                &nbsp;
                                                @include('adminlte::partials.confirm', ['data' => $value,'id'=>$process['id'], 'name' => 'processattachments' ,'display_name'=> 'Attachments'])
                                            </td>

                                            <td><a target="_blank" href="{{ $value['url'] }}">{{ $value['name'] }}</a></td>
                                            <td>{{ $value['size'] }}</td>
                                           </tr>
                                    @endforeach
                                @endif
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        @endif      
           
        <div class="col-xs-12 col-sm-12 col-md-12 text-center">
            {!! Form::submit(isset($process) ? 'Update' : 'Submit', ['class' => 'btn btn-primary']) !!}
        </div>
    </div>
</div>


@section('customscripts')
    <script>
        $(document).ready(function(){

            $("#process_form").validate({
                rules: {
                    "title": {
                        required: true
                    },
                    "department_ids[]": {
                        required: true
                    },
                    "upload_documents[]": {
                        required: true
                    }
                },
                messages: {
                    "title": {
                        required: "Title is Required Field."
                    },
                    "department_ids[]": {
                        required: "Please Select Department."
                    },
                    "upload_documents[]": {
                        required: "Please Select File."
                    }
                }
            });
            
            $("#departments_all").click(function () {
                
                $('.department_ids').prop('checked', this.checked);
                displayUsers();
            });

            $(".department_ids").click(function () {
                $("#departments_all").prop('checked', ($('.department_ids:checked').length == $('.department_ids').length) ? true : false);
                displayUsers();
            });

            // Edit form if all user select then select all selected
            $("#departments_all").prop('checked', ($('.department_ids:checked').length == $('.department_ids').length) ? true : false);
        });

        function displayUsers() {

            var department_ids_string = [];
            jQuery("input[name='department_ids[]']:checked").each(function(i) {
                department_ids_string.push($(this).val());
            });

            if(department_ids_string != '') {

                $.ajax({

                    url:'/process/getusers',
                    data:'department_ids_string='+department_ids_string,
                    dataType:'json',
                    success: function(data) {

                        if(data.users) {

                            $(".add_user_list").html("");

                            var html = '';

                            $.each(data.users,function(key, value) {

                                html += '<b>&nbsp;&nbsp;&nbsp;<input type="checkbox" name="user_ids[]" value="'+key+'">&nbsp;&nbsp;'+value+'</b>';
                            }); 

                            $(".add_user_list").append(html);
                        }
                    }
                });
            }
            else {

                $(".add_user_list").html("");
            }
        }
    </script>
@endsection

