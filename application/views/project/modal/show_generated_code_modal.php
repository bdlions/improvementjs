<script type="text/javascript">
    $(function() {
        $("#button_download_code").click(function(){
            updateClientEndOperationCounter();
            $("#form_download_project_code").submit();
            $("#modal_show_generated_code").modal('hide');
        });
    });
</script>
<div class="modal fade" id="modal_show_generated_code" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="show_generated_code_title" name="show_generated_code_title">Code</h4>
            </div>
            <div class="modal-body">
                <div class="row" style="padding-left:15px; padding-right:15px;">                    
                    <textarea style="width:100%" id="textarea_generated_code" rows="15"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <div class ="col-md-6">
                    
                </div>
                <div class ="col-md-3">
                    <?php echo form_open("projects/download_project_code", array('id' => 'form_download_project_code', 'class' => 'form-horizontal'));?>
                    <input id="button_download_code" name="button_download_code" type="button" class="btn btn-success" value="Download"/>
                    <?php echo form_close();?>
                </div>
                <div class ="col-md-3 pull-right">
                    <button style="width:100%" type="button" class="btn btn-success" data-dismiss="modal">Close</button>
                </div>                
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->