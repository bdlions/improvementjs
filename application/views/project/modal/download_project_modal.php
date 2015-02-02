<script type="text/javascript">
    $(function() {
        $("#button_download_project").on("click", function() {
            updateClientEndOperationCounter();
            $("#form_download_project").submit();
            $('#modal_download_project').modal('hide');
        });
    });
</script>
<div class="modal fade" id="modal_download_project" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">Download project</h4>
            </div>
            <div class="modal-body">
                <?php echo form_open("projects/download_project", array('id' => 'form_download_project', 'class' => 'form-horizontal'));?>
                <div class="row form-group">
                    <label for="file_name" class="col-md-3 control-label">
                        File Name:
                    </label>
                    <div class ="col-md-9">
                        <input type="text" id="project_content_file_name" name="project_content_file_name" class="form-control"/>                            
                    </div> 
                </div>
                <div class="row form-group">
                    <label for="file_name" class="col-md-3 control-label">

                    </label>
                    <div class="col-md-3 pull-right">
                        <input id="button_download_project" name="button_download_project" type="button" id="" name="" value="Download" class="form-control btn btn-success"/>                            
                    </div> 
                </div>                
                <?php echo form_close();?>
            </div>
            <div class="modal-footer">
                <div class ="row col-md-3 pull-right">
                    <button type="button" class="btn btn-success" data-dismiss="modal">Cancel</button>
                </div>                
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->