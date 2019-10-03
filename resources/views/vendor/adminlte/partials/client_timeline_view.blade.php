<a data-toggle="modal" href="#modal-timeline-{!! $data['id'] !!}" class="fa fa-times-circle-o" title="Timeline" style="margin:2px;"></a>
<div id="modal-timeline-{!! $data['id'] !!}" class="modal text-left fade">
	<div class="modal-dialog">
        <div class="modal-content">

        	<div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h3 class="modal-title">{{ $data['full_name'] }}</h3>
            </div>

            <div class="modal-body">
                <table id="timeline_table" class="table table-striped table-bordered nowrap" cellspacing="0" width="100%">
                    <tr>
                        <td style="text-align: center;"><b>Username</b></td>
                        <td style="text-align: center;"><b>From Date</b></td>
                        <td style="text-align: center;"><b>To Date</b></td>
                        <td style="text-align: center;"><b>Days</b></td>
                    </tr>
                </table>
            </div>

            <div class="modal-footer">
                <!-- <button type="submit" class="btn btn-primary">Ok</button> -->
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
            </div>
           
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

