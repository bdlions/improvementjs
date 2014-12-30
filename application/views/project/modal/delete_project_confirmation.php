<script type="text/javascript">
    $(function() {
        $("#button_delete").on("click", function() {
            waitScreen.show();
            $.ajax({
                dataType: 'json',
                type: "POST",
                url: '<?php echo base_url(); ?>' + "projects/delete_project",
                data: {
                    project_id: $("#input_project_id").val()
                },
                success: function(data) {
                    $("#modal_delete_project_confirmation").modal('hide');
                    waitScreen.hide();                    
                    window.location.reload();
                }
            });
        });
    });
    function modal_delete_project_confirmation(project_id) {
        $('#input_project_id').val(project_id);
        $("#modal_delete_project_confirmation").modal('show');
    }
</script>
<div class="modal fade" id="modal_delete_project_confirmation" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">Delete project</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="row form-group">
                        <div class ="col-sm-2"></div>
                        <label class="col-sm-10 control-label">Are you sure to delete this project?</label>
                        <input id="input_project_id" name="input_project_id" value="" type="hidden" class="form-control"/>
                    </div>
                </div>                
            </div>
            <div class="modal-footer">
                <div class ="col-md-6">
                    
                </div>
                <div class ="col-md-3">
                    <button style="width:100%" id="button_delete" name="button_delete" value="" class="form-control btn btn-success pull-right">Delete</button>
                </div>
                <div class ="col-md-3">
                    <button style="width:100%" type="button" class="btn btn-success" data-dismiss="modal">Cancel</button>
                </div>
                
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->