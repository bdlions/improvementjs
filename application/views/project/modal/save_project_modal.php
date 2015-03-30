<div class="modal fade" id="modal_save_confirmation" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">Save As Project</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-offset-1"></div>
                    <?php
                    $attributes = array('name' => 'form_submission', 'onsubmit' => 'return save_as_project_save_button_clicked()');
                    echo form_open("general_process/save_as_project", $attributes);
                    ?>
                    <div class="col-md-3"><label >Project Name</label></div>
                    <div class="col-md-6">
                        <input type="text" id="save_as_project_project_name" name="save_as_project_project_name" value="" class="form-control"/>
                    </div>
                    <div class="col-md-2">
                        <input type="hidden" id="save_as_project_left_panel_content" name="save_as_project_left_panel_content" value=""/>
                        <input type="submit" id="save_as_project_save_button" value="Save" class="btn btn-success"/>
                    </div>
                    <?php echo form_close(); ?>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<div class="modal fade" id="modal_save_as_replace_project" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">Save As Project</h4>
            </div>
            <?php echo form_open("general_process/save_as_replace_project"); ?>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-offset-1 col-md-10">
                        <label >Project already exists. Do you want to replace it?</label><br/>
                        <input type="hidden" id="save_as_replace_project_left_panel_content" name="save_as_replace_project_left_panel_content" value=""/>
                        <input type="hidden" id="save_as_replace_project_name" name="save_as_replace_project_name" value=""/>
                        <input type="hidden" id="save_as_replace_project_id" name="save_as_replace_project_id" value=""/>


                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <input type="submit" id="save_as_replace_project_yes_button" class="btn btn-default"value="Yes" onclick="return save_as_replace_project_yes_button_clicked()"/>
                <input type="submit" id="save_as_replace_project_no_button"  class="btn btn-default" value="No" onclick="return save_as_replace_project_no_button_clicked()"/>
                <!--<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>-->
            <?php echo form_close(); ?>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->