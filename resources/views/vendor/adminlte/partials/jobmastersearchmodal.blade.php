
    <!-- Job Master Search Modal Popup -->

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
                            <strong>Select Field which you want to search : </strong> <br/>
                            {!! Form::select('selected_field', $field_list,null, array('id'=>'selected_field', 'class' => 'form-control','tabindex' => '1','onchange' => 'displaySelectedField()')) !!}
                        </div>
                    </div>
                </div>

                <div class="col-xs-12 col-sm-12 col-md-12 job_position_cls" style="display: none;">
                    <div class="">
                        <div class="form-group"><br/>
                            <strong>Select Job Position :</strong> <br/>
                            {!! Form::select('client_heirarchy', $client_hierarchy_name,null, array('id'=>'client_heirarchy','class' => 'form-control','tabindex' => '1')) !!}
                        </div>
                    </div>
                </div>

                <div class="col-xs-12 col-sm-12 col-md-12 mb_name_cls" style="display: none;">
                    <div class="">
                        <div class="form-group"><br/>
                            <strong>Managed By : </strong> <br/>
                            {!! Form::select('mb_name', $users,null, array('id'=>'mb_name','class' => 'form-control','tabindex' => '1')) !!}
                        </div>
                    </div>
                </div>

                <div class="col-xs-12 col-sm-12 col-md-12 company_name_cls" style="display: none;">
                    <div class="">
                        <div class="form-group"><br/>
                            <strong>Enter Company Name : </strong>
                            {!! Form::text('company_name', null, array('id'=>'company_name','placeholder' => 'Company Name','class' => 'form-control', 'tabindex' => '1')) !!}
                        </div>
                    </div>
                </div>

                <div class="col-xs-12 col-sm-12 col-md-12 posting_title_cls" style="display: none;">
                    <div class="">
                        <div class="form-group"><br/>
                            <strong>Enter Posting Title : </strong>
                            {!! Form::text('posting_title', null, array('id'=>'posting_title','placeholder' => 'Posting Title','class' => 'form-control', 'tabindex' => '1')) !!}
                        </div>
                    </div>
                </div>

                <div class="col-xs-12 col-sm-12 col-md-12 location_cls" style="display: none;">
                    <div class="">
                        <div class="form-group"><br/>
                            <strong>Enter Location : </strong>
                            {!! Form::text('location', null, array('id'=>'location','placeholder' => 'Location','class' => 'form-control', 'tabindex' => '1')) !!}
                        </div>
                    </div>
                </div>

                <div class="col-xs-12 col-sm-12 col-md-12 min_ctc_cls" style="display: none;">
                    <div class="">
                        <div class="form-group"><br/>
                            <strong>Min CTC : </strong>
                            {!! Form::select('min_ctc', $min_ctc_array,null, array('id'=>'min_ctc','class' => 'form-control','tabindex' => '1')) !!}
                        </div>
                    </div>
                </div>

                <div class="col-xs-12 col-sm-12 col-md-12 max_ctc_cls" style="display: none;">
                    <div class="">
                        <div class="form-group"><br/>
                            <strong>Max CTC : </strong>
                            {!! Form::select('max_ctc', $max_ctc_array,null, array('id'=>'max_ctc','class' => 'form-control','tabindex' => '1')) !!}
                        </div>
                    </div>
                </div>

                <div class="col-xs-12 col-sm-12 col-md-12 added_date_cls" style="display: none;">
                    <div class="">
                        <div class="form-group"><br/>
                            <strong>Enter Added Date : </strong>
                            {!! Form::text('added_date', null, array('id'=>'added_date','placeholder' => 'Added Date','class' => 'form-control', 'tabindex' => '1')) !!}
                        </div>
                    </div>
                </div>

                <div class="col-xs-12 col-sm-12 col-md-12 no_of_positions_cls" style="display: none;">
                    <div class="">
                        <div class="form-group"><br/>
                            <strong>Enter No. Of Positions : </strong>
                            {!! Form::text('no_of_positions', null, array('id'=>'no_of_positions','placeholder' => 'No. Of Positions','class' => 'form-control', 'tabindex' => '1')) !!}
                        </div>
                    </div>
                </div>
         
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" onclick="displayresults();">Search
                    </button>

                    @if(isset($type) && $type == 'Job Openings')
                        <a href="/jobs" class="btn btn-primary">Reset</a>
                    @else
                        <a href="/job/close" class="btn btn-primary">Reset</a>
                    @endif
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>