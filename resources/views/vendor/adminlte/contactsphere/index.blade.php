@extends('adminlte::page')

@section('title', 'Contactsphere')

@section('content_header')

@stop

@section('content')

   <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Total Contacts({{ $count or '0' }})</h2>
                <h4><span>Contact converted to Lead - {{ $convert_lead_count }}</span></h4>
            </div>

            <div class="pull-right">
                <a class="btn btn-success" href="{{ route('contactsphere.add') }}">Add New Contact</a>
                <button type="button" class="btn bg-maroon" data-toggle="modal" data-target="#searchmodal" onclick="contacts_emails_notification()">Send Mail</button>
                <a class="btn btn-primary" href="{{ route('contactsphere.importExport') }}">Import Contacts</a>
                <a class="btn btn-success" href="javascript:void(0);" onClick="export_data()">Export Contacts</a>
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

   <div class = "table-responsive">
        <table class="table table-striped table-bordered nowrap" cellspacing="0" width="100%" id="contactsphere_table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>{{ Form::checkbox('client[]',0 ,null,array('id'=>'allcb')) }}</th>
                    <th>Action</th>
                    <th>Name</th>
                    <th>Designation</th>
                    <th>Company</th>
                    <th>Contact Number</th>
                    <th>City</th>
                    <th>Referred By</th>
                    <th>Official Email ID</th>
                    <th>Personal ID</th>
                    <th>Convert Lead</th>
                </tr>
            </thead>
        </table>
   </div>

   <div id="searchmodal" class="modal text-left fade email_modal" style="display: none;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">Send Mail</h4>
                </div>
                {!! Form::open(['method' => 'POST', 'route' => 'contactsphere.emailnotification','onsubmit' => "return sendEmails()"]) !!}
                    <div class="modal-body">

                        <div class="body_class">

                            <div class="form-group">
                                <strong>Subject : <span class = "required_fields">*</span> </strong>
                                {!! Form::text('email_subject', null, array('id'=>'email_subject','placeholder' => 'Subject','tabindex' => '1','class' => 'form-control')) !!}
                            </div>

                            <label id="email_subject_error" style="color:#f56954;display:none;"></label>

                            <div class="form-group">
                                <strong>Message : <span class = "required_fields">*</span> </strong>
                                {!! Form::textarea('email_body', null, array('id'=>'email_body','placeholder' => 'Message','class' => 'form-control', 'tabindex' => '2')) !!}
                            </div>

                            <label id="email_body_error" style="color:#f56954;display:none;"></label>
                        </div>
                        <div class="email_error"></div>
                    </div>

                    <input type="hidden" name="email_contacts_ids" id="email_contacts_ids" value="">

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary" id="email_submit">Submit</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>

    <input type="hidden" name="csrf_token" id="csrf_token" value="{{ csrf_token() }}">
@stop

@section('customscripts')
<script src="https://cdn.ckeditor.com/4.6.2/standard-all/ckeditor.js"></script>
    <script type="text/javascript">
        jQuery(document).ready(function() {
            
            $("#contactsphere_table").DataTable({

                "bProcessing": true,
                "serverSide": true,
                "order" : [0,'desc'],
                "columnDefs": [{orderable: false, targets: [1]},{orderable: false, targets: [2]}],
                "ajax":{
                    url :"contactsphere/all",
                    type: "get",
                    error: function() {
                    }
                },
                "pageLength": 50,
                "responsive": true,
                "pagingType": "full_numbers",
                "stateSave" : true,
                "fnRowCallback": function( Row, Data ) {
                    if ( Data[11] == "1" ) {
                        $('td:eq(3)', Row).css('background-color', 'LimeGreen');
                    }
                    else {
                        $('td:eq(3)', Row).css('background-color', 'white');
                    }
                }
            });
            
            var table = $('#contactsphere_table').DataTable();
            table.columns( [11] ).visible( false );

            $('#allcb').change(function() {

                if($(this).prop('checked')) {
                    $('tbody tr td input[type="checkbox"]').each(function() {
                        $(this).prop('checked', true);
                    });
                }
                else {
                    $('tbody tr td input[type="checkbox"]').each(function() {
                        $(this).prop('checked', false);
                    });
                }
            });

            $('.other_contacts').change(function() {

                if ($(this).prop('checked')) {
                    if ($('.other_contacts:checked').length == $('.other_contacts').length) {
                        $("#allcb").prop('checked', true);
                    }
                }
                else {
                    $("#allcb").prop('checked', false);
                }
            });
        });

        function contacts_emails_notification() {

            var token = $('input[name="csrf_token"]').val();
            var app_url = "{!! env('APP_URL'); !!}";
            var contacts_ids = new Array();

            var table = $("#contactsphere_table").dataTable();
            
            table.$("input:checkbox[name=contact]:checked").each(function() {
                contacts_ids.push($(this).val());
            });

            $("#email_contacts_ids").val(contacts_ids);

            $.ajax({

                type : 'POST',
                url : app_url+'/contactsphere/checkContactsphereId',
                data : {contacts_ids : contacts_ids, '_token':token},
                dataType : 'json',
                success: function(msg) {

                    $(".email_modal").show();

                    if (msg.success == 'Success') {

                        $(".email_error").empty();
                        $('#email_submit').show();
                        $('.body_class').show();

                        CKEDITOR.replace( 'email_body', {

                            filebrowserUploadUrl: '{{ route('emailbody.image',['_token' => csrf_token() ]) }}',
                            customConfig: '/js/ckeditor_config.js',
                            height: '100px',
                        });

                        CKEDITOR.on('dialogDefinition', function( ev ) {

                            var dialogName = ev.data.name;  
                            var dialogDefinition = ev.data.definition;
                                         
                            switch (dialogName) { 

                                case 'image': //Image Properties dialog      
                                dialogDefinition.removeContents('Link');
                                dialogDefinition.removeContents('advanced');
                                break;

                                case 'link': //image Properties dialog          
                                dialogDefinition.removeContents('advanced');   
                                break;
                            }
                        });
                    }

                    else {
                        
                        $(".email_error").empty();
                        $('#email_submit').hide();
                        $(".email_error").append(msg.err);
                        $('.body_class').hide();
                    }
                }
            });
        }

        function sendEmails() {

            var email_subject = $("#email_subject").val();
            var email_body = CKEDITOR.instances.email_body.getData();


            if(email_subject == '') {
                $("#email_subject_error").show();
                $("#email_subject_error").text("Subject is Required Field.");
            }
            else {
                $("#email_subject_error").hide();
            }

            if(email_body == '') {
                $("#email_body_error").show();
                $("#email_body_error").text("Message is Required Field.");
                return false;
            }
            else {
                $("#email_body_error").hide();
            }
        }

        function export_data() {

            var url = '/contactsphere/export';

            var form = $('<form action="'+url+ '" method="post">' +
            '<input type="hidden" name="_token" value="<?php echo csrf_token() ?>">' +
            '</form>');

            $('body').append(form);
            form.submit();
        }
    </script>
@endsection