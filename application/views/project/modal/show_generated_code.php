<script type="text/javascript">
    $(function() {
        
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
                    <?php echo form_open("general_process/download_project_code");?>
                    <input style="width:100%" type="submit" class="btn btn-success" value="Download" onclick="generate_code_save_button_pressed()"/>
                    <?php echo form_close();?>
                </div>
                <div class ="col-md-3 pull-right">
                    <button style="width:100%" type="button" class="btn btn-success" data-dismiss="modal">Close</button>
                </div>
                
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->