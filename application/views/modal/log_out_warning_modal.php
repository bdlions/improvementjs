<script>
    $(function(){
        $("#button_log_out_warning_continue").click(function(){
            sessionRenewConfirmed = true;
            $.ajax({
                type: "POST",
                url: '<?php echo base_url()?>'+"general_process/keep_server_alive",
                data: {                
                },
                success: function () { 
                    updateClientEndOperationCounter();
                }
            });
            $('#modal_log_out_warning').modal('hide');
        });
        $("#button_log_out_confirm").click(function(){
            sessionRenewConfirmed = false;
            $.ajax({
                type: "POST",
                url: '<?php echo base_url()?>'+"general_process/logout",
                data: {                
                },
                success: function () { 
                    window.location.replace(server_base_url);
                }
            });            
        });
        $("#button_log_out_close").click(function(){
            sessionRenewConfirmed = false;
        });
    });
</script>
<div class="modal fade" id="modal_log_out_warning" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button id="button_log_out_close" name="button_log_out_close" type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">Message</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="row form-group">
                        <div class ="col-sm-2"></div>
                        <label class="col-sm-10 control-label">
                            your session is about to expire. Do you want to continue working?
                        </label>
                    </div>
                </div>                
            </div>
            <div class="modal-footer">
                <div class ="col-md-offset-8 col-md-2 col-xs-offset-4 col-xs-4">
                    <button id="button_log_out_warning_continue" name="button_log_out_warning_continue" type="button" class="btn btn-success">Yes</button>
                </div>
                <div class ="col-md-2 col-xs-4">
                    <button id="button_log_out_confirm" name="button_log_out_confirm" type="button" class="btn btn-success">No</button>
                </div>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->