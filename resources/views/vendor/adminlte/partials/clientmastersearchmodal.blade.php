<!-- Client Master Search Modal Popup -->

    <div class="modal fade mastersearchmodal" id="mastersearchmodal" aria-labelledby="mastersearchmodal" role="dialog">
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
                            {!! Form::select('selected_field', $field_list,null, array('id'=>'selected_field', 'class' => 'form-control','tabindex' => '1','onchange' => 'displaySelectedField()')) !!}
                        </div>
                    </div>
                </div>

                <div class="col-xs-12 col-sm-12 col-md-12 client_owner_cls" style="display: none;">
                    <div class="">
                        <div class="form-group"><br/>
                            <strong>Enter Client Owner : </strong>
                            {!! Form::select('client_owner', $users,null, array('id'=>'client_owner','class' => 'form-control','tabindex' => '1')) !!}
                        </div>
                    </div>
                </div>

                <div class="col-xs-12 col-sm-12 col-md-12 client_company_cls" style="display: none;">
                    <div class="">
                        <div class="form-group"><br/>
                            <strong>Enter Company Name : </strong>
                            {!! Form::text('client_company', null, array('id'=>'client_company','placeholder' => 'Company Name','class' => 'form-control', 'tabindex' => '1')) !!}
                        </div>
                    </div>
                </div>

                <div class="col-xs-12 col-sm-12 col-md-12 client_contact_point_cls" style="display: none;">
                    <div class="">
                        <div class="form-group"><br/>
                            <strong>Enter Contact Point : </strong>
                            {!! Form::text('client_contact_point', null, array('id'=>'client_contact_point','placeholder' => 'Contact Point','class' => 'form-control', 'tabindex' => '1')) !!}
                        </div>
                    </div>
                </div>

                <div class="col-xs-12 col-sm-12 col-md-12 client_cat_cls" style="display: none;">
                    <div class="">
                        <div class="form-group"><br/>
                            <strong>Enter Client Category : </strong>
                            {!! Form::select('client_cat', $category_list,null, array('id'=>'client_cat','class' => 'form-control','tabindex' => '1')) !!}
                        </div>
                    </div>
                </div>

                <div class="col-xs-12 col-sm-12 col-md-12 client_status_cls" style="display: none;">
                    <div class="">
                        <div class="form-group"><br/>
                            <strong>Enter Client Status : </strong>
                            {!! Form::text('client_status', null, array('id'=>'client_status','placeholder' => 'Client Status','class' => 'form-control', 'tabindex' => '1')) !!}
                        </div>
                    </div>
                </div>

                <div class="col-xs-12 col-sm-12 col-md-12 client_city_cls" style="display: none;">
                    <div class="">
                        <div class="form-group"><br/>
                            <strong>Enter Client City : </strong>
                            {!! Form::text('client_city', null, array('id'=>'client_city','placeholder' => 'Client City','class' => 'form-control', 'tabindex' => '1')) !!}
                        </div>
                    </div>
                </div>
         
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" onclick="displayresults();">Search
                    </button>
                    
                    <a href="/client" class="btn btn-primary">Reset</a>

                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div>