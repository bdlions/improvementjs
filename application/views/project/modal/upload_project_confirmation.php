<script type="text/javascript">
    $(function() {
        $("#button_upload_project_confirmation_save").on("click", function() {
            updateClientEndOperationCounter();
            $.ajax({
                dataType: 'json',
                type: "POST",
                url: '<?php echo base_url(); ?>' + "projects/update_project_left_panel",
                data: {
                    project_id: '<?php echo $project_id;?>',
                    left_panel_content: $("#selectable").html()
                },
                success: function(data) {
                    window.location = '<?php echo base_url().'projects/upload_project/'.$project_id;?>';
                }
            });
        });
        $("#button_upload_project_confirmation_no").on("click", function() {
            updateClientEndOperationCounter();
            window.location = '<?php echo base_url().'projects/upload_project/'.$project_id;?>';
        });
    });
    function upload_project_confirmation() {
        updateClientEndOperationCounter();
        $("#modal_upload_project_confirmation").modal('show');
    }
</script>
<div class="modal fade" id="modal_upload_project_confirmation" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">Upload Project</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="row form-group">
                        <div class ="col-sm-2"></div>
                        <label class="col-sm-10 control-label">Do you want to save your current project?</label>                        
                    </div>
                </div>                
            </div>
            <div class="modal-footer">
                <div class ="col-md-6">
                    
                </div>
                <div class ="col-md-3">
                    <button style="width:100%" id="button_upload_project_confirmation_save" name="button_upload_project_confirmation_save" value="" class="form-control btn btn-success pull-right">Save</button>
                </div>
                <div class ="col-md-3">
                    <button style="width:100%" id="button_upload_project_confirmation_no" name="button_upload_project_confirmation_no" type="button" class="btn btn-success" data-dismiss="modal">No</button>
                </div>
                
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->